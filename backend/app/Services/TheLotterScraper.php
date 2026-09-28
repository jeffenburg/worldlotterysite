<?php

namespace App\Services;

use App\Models\Lottery;
use App\Models\LotteryDraw;
use App\Models\LotteryDrawNumber;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class TheLotterScraper
{
    public function scrape(Lottery $lottery): array
    {
        if (!$lottery->thelotter_slug) {
            throw new RuntimeException('No TheLotter slug configured.');
        }

        $url = 'https://www.thelotter.com/lottery-results/'
            . trim($lottery->thelotter_slug, '/')
            . '/';

        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0',
        ])->get($url);

        if (!$response->successful()) {
            throw new RuntimeException(
                'Failed to fetch page: HTTP ' . $response->status()
            );
        }

        $html = $response->body();

        /*
         * TheLotter embeds the result data as JSON in the page.
         */
        if (!preg_match(
            '/window\.TLE\["__LOTTERY-RESULT-DETAILS_STATE__"\]\s*=\s*(\{.*?\})<\/script>/s',
            $html,
            $match
        )) {
            throw new RuntimeException('Could not find TheLotter result data.');
        }

        $state = json_decode($match[1], true);

        if (!$state) {
            throw new RuntimeException('Could not decode TheLotter result data.');
        }

        $viewModel = $state['lotteryResultDetailsViewModel'] ?? null;

        $lotteryInfo =
            $viewModel['lotteryInfoViewModel']['lotteryInformation'] ?? null;

        $draw =
            $viewModel['drawResultViewModel']
            ['drawResult'] ?? null;

        if (!$draw) {
            throw new RuntimeException('Could not find draw result.');
        }

        if (!empty($draw['lotteryRef'])) {
            $lottery->update([
                'thelotter_id' => $draw['lotteryRef'],
            ]);
        }

        /*
         * Draw date
         */
        $drawDateTime =
            $draw['drawLocalDateTime']
            ?? $draw['drawDateTime']
            ?? null;

        if (!$drawDateTime) {
            throw new RuntimeException('Could not find draw date.');
        }

        $drawDate = date(
            'Y-m-d',
            strtotime($drawDateTime)
        );

        /*
         * Result numbers
         */
        $game = $draw['drawGameResultsInfo'][0] ?? null;

        if (!$game) {
            throw new RuntimeException('Could not find winning numbers.');
        }

        $regularNumbers = $this->parseNumbers(
            $game['winningNumbersRegular'] ?? null
        );

        $additionalNumbers = $this->parseNumbers(
            $game['winningNumbersAdditional'] ?? null
        );

        $bonusNumbers = $this->parseNumbers(
            $game['winningNumbersBonus'] ?? null
        );

        $multiplyNumbers = $this->parseNumbers(
            $game['winningNumbersMultiply'] ?? null
        );

        /*
         * Jackpot for this draw
         */
        $jackpot = null;

        $activeDrawId =
            $viewModel['drawResultViewModel']['activeDrawId']
            ?? null;

        $draws =
            $viewModel['drawsInfo']['draws']
            ?? [];

        foreach ($draws as $drawInfo) {
            if (
                $activeDrawId !== null
                && isset($drawInfo['id'])
                && (int) $drawInfo['id'] === (int) $activeDrawId
            ) {
                $jackpot = $drawInfo['localJackpot'] ?? null;
                break;
            }
        }

        /*
         * Currency
         */
        $currencySign =
            $viewModel['cardViewModel']
            ['lottery']
            ['jackpot']
            ['sign'] ?? null;

        $currency = match ($currencySign) {
            '€' => 'EUR',
            '$' => 'USD',
            '£' => 'GBP',
            default => null,
        };

        /*
         * Save draw
         */
        $lotteryDraw = LotteryDraw::updateOrCreate(
            [
                'lottery_id' => $lottery->id,
                'draw_date' => $drawDate,
            ],
            [
                'jackpot' => $jackpot,
                'jackpot_currency' => $currency,
                'source_url' => $url,
            ]
        );

        /*
         * Replace number rows for this draw.
         */
        LotteryDrawNumber::where(
            'lottery_draw_id',
            $lotteryDraw->id
        )->delete();

        $this->saveNumbers(
            $lotteryDraw,
            'main',
            $regularNumbers
        );

        if ($additionalNumbers) {
            $additionalName =
                $lotteryInfo['additionalNumberName']
                ?? 'additional';

            $this->saveNumbers(
                $lotteryDraw,
                $this->normaliseType($additionalName),
                $additionalNumbers
            );
        }

        if ($bonusNumbers) {
            $this->saveNumbers(
                $lotteryDraw,
                'bonus',
                $bonusNumbers
            );
        }

        if ($multiplyNumbers) {
            $multiplyName =
                $lotteryInfo['multiplyProgramName']
                ?? 'multiplier';

            $this->saveNumbers(
                $lotteryDraw,
                $this->normaliseType($multiplyName),
                $multiplyNumbers
            );
        }

        return [
            'draw_date' => $drawDate,
            'main' => $regularNumbers,
            'additional' => $additionalNumbers,
            'bonus' => $bonusNumbers,
            'multiply' => $multiplyNumbers,
            'jackpot' => $jackpot,
        ];
    }

    private function parseNumbers(?string $value): array
    {
        if (!$value) {
            return [];
        }

        return array_map(
            'intval',
            array_filter(
                explode(';', $value),
                fn ($number) => $number !== ''
            )
        );
    }

    private function saveNumbers(
        LotteryDraw $draw,
        string $type,
        array $numbers
    ): void {
        foreach ($numbers as $index => $number) {
            LotteryDrawNumber::create([
                'lottery_draw_id' => $draw->id,
                'type' => $type,
                'number' => $number,
                'position' => $index + 1,
            ]);
        }
    }

    private function normaliseType(string $type): string
    {
        return strtolower(
            preg_replace(
                '/[^a-zA-Z0-9]+/',
                '_',
                trim($type)
            )
        );
    }
}