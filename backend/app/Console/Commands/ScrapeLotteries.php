<?php

namespace App\Console\Commands;

use App\Models\Lottery;
use App\Services\TheLotterScraper;
use Illuminate\Console\Command;

class ScrapeLotteries extends Command
{
    protected $signature = 'lotteries:results';

    protected $description = 'Update latest lottery results from TheLotter';

    public function handle(TheLotterScraper $scraper): int
    {
        $lotteries = Lottery::where('active', true)
            ->whereNotNull('thelotter_slug')
            ->get();

        foreach ($lotteries as $lottery) {
            $this->info("Scraping {$lottery->name}...");

            try {
                $result = $scraper->scrape($lottery);

                $this->info(
                    sprintf(
                        'Saved %s: %s',
                        $result['draw_date'],
                        implode(', ', $result['main'])
                    )
                );
            } catch (\Throwable $e) {
                $this->error("Failed {$lottery->name}: {$e->getMessage()}");
            }
        }

        return self::SUCCESS;
    }
}