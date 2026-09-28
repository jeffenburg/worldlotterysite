<?php

namespace App\Console\Commands;

use App\Models\Lottery;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ScrapeJackpots extends Command
{
    protected $signature = 'lotteries:jackpots';

    protected $description = 'Update current lottery jackpots and next draw dates from TheLotter';

    public function handle(): int
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0',
        ])->get('https://www.thelotter.com/lottery-tickets/');

        if (!$response->successful()) {
            throw new RuntimeException(
                'Failed to fetch TheLotter catalog: HTTP ' . $response->status()
            );
        }

        $html = $response->body();

        /*
         * Main current lottery data
         */
        if (!preg_match(
            '/"lotteryCatalogItems":(\[.*?\]),"lotteryResults"/s',
            $html,
            $catalogMatch
        )) {
            throw new RuntimeException('Could not find lottery catalog data.');
        }

        $catalogItems = json_decode($catalogMatch[1], true);

        if (!is_array($catalogItems)) {
            throw new RuntimeException('Could not decode lottery catalog data.');
        }

        /*
         * Currency ISO codes
         */
        if (!preg_match(
            '/"lotteryJackpotInfos":(\[.*?\])/s',
            $html,
            $currencyMatch
        )) {
            throw new RuntimeException('Could not find lottery currency data.');
        }

        $jackpotInfos = json_decode($currencyMatch[1], true);

        if (!is_array($jackpotInfos)) {
            throw new RuntimeException('Could not decode lottery currency data.');
        }

        /*
         * Build currency lookup by TheLotter ID
         */
        $currencyById = [];

        foreach ($jackpotInfos as $info) {
            if (!isset($info['lotteryId'])) {
                continue;
            }

            $currencyById[(int) $info['lotteryId']] =
                $info['currencyIsoCode'] ?? null;
        }

        $rows = [];
        $updated = 0;

        foreach ($catalogItems as $item) {
            if (!isset($item['lotteryRef'])) {
                continue;
            }

            $theLotterId = (int) $item['lotteryRef'];

            $lottery = Lottery::where(
                'thelotter_id',
                $theLotterId
            )->first();

            if (!$lottery) {
                continue;
            }

            $currency = $currencyById[$theLotterId] ?? null;
            $jackpot = $item['jackpot'] ?? null;
            $jackpotUsd = $item['jackpotUsd'] ?? null;
            $nextDraw = $item['drawDateTime'] ?? null;

            $lottery->update([
                'jackpot' => $jackpot,
                'jackpot_currency' => $currency,
                'jackpot_usd' => $jackpotUsd,
                'next_draw_at' => $nextDraw,
            ]);

            $rows[] = [
                $lottery->name,
                $theLotterId,
                $currency ?? '-',
                $jackpot !== null
                    ? number_format($jackpot)
                    : '-',
                $jackpotUsd !== null
                    ? '$' . number_format($jackpotUsd)
                    : '-',
                $nextDraw ?? '-',
            ];

            $updated++;
        }

        $this->table(
            [
                'Lottery',
                'TheLotter ID',
                'Currency',
                'Jackpot',
                'Jackpot USD',
                'Next Draw',
            ],
            $rows
        );

        $this->info("Updated {$updated} lotteries.");

        return self::SUCCESS;
    }
}