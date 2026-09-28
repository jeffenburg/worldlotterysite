import Link from 'next/link'
import { BookOpen, ChevronRight, Globe2, Trophy } from 'lucide-react'
import { getLotteries, getPages } from '@/lib/api'
import { guidePages } from '@/lib/content'
import { LotteryCard } from '@/components/lottery-card'
import { ResultRow } from '@/components/result-row'
import { SectionHeading } from '@/components/section-heading'
import { formatUSD } from '@/lib/format'
import { accentFor } from '@/lib/theme'

export default async function Home() {
  const [lotteries, allPages] = await Promise.all([getLotteries(), getPages()])
  const pages = guidePages(allPages, lotteries)

  const featured = lotteries.slice(0, 3)
  const rest = lotteries.slice(3)

  const combinedUsd = lotteries.reduce((sum, l) => sum + (l.jackpot_usd ? parseFloat(l.jackpot_usd) : 0), 0)

  const latestResults = lotteries
    .filter((l) => l.latest_draw)
    .sort((a, b) => (a.latest_draw!.draw_date < b.latest_draw!.draw_date ? 1 : -1))
    .slice(0, 6)

  return (
    <div className="mx-auto max-w-6xl px-4 pb-16 sm:px-6">
      {/* Hero */}
      <section className="relative overflow-hidden rounded-3xl bg-surface px-6 py-10 text-center sm:py-14">
        <div aria-hidden className="pointer-events-none absolute inset-0">
          <div className="absolute -left-10 top-6 h-24 w-24 rounded-full bg-rose-500 opacity-10" />
          <div className="absolute right-6 top-16 h-16 w-16 rounded-full bg-sky-500 opacity-10" />
          <div className="absolute bottom-4 left-1/3 h-12 w-12 rounded-full bg-amber-500 opacity-10" />
        </div>

        <div className="relative">
          <p className="mb-3 inline-flex items-center gap-1.5 rounded-full bg-background px-4 py-1.5 text-sm font-semibold text-muted">
            <Globe2 size={15} /> {lotteries.length} lotteries tracked worldwide
          </p>
          <h1 className="font-display mx-auto max-w-3xl text-4xl font-extrabold leading-tight sm:text-6xl">
            Big jackpots. Latest results. <span className="text-rose-500">No nonsense.</span>
          </h1>
          <p className="mx-auto mt-3 max-w-xl text-lg text-muted">
            Track jackpots, winning numbers and next draw dates for lotteries around the world — clean, fast, and to the point.
          </p>
          <div className="mt-6 flex flex-wrap items-center justify-center gap-3">
            <Link
              href="#featured"
              className="rounded-full bg-rose-500 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-600"
            >
              See today&apos;s biggest jackpots
            </Link>
            <Link
              href="/lotteries"
              className="rounded-full bg-background px-6 py-3 text-sm font-semibold text-foreground shadow-sm transition hover:bg-white"
            >
              Browse all lotteries
            </Link>
          </div>
          {combinedUsd > 0 && (
            <p className="mt-4 text-sm text-muted">
              Combined active jackpots right now:{' '}
              <span className="font-semibold text-foreground">{formatUSD(combinedUsd)} USD</span>
            </p>
          )}
        </div>
      </section>

      {/* Featured / biggest jackpots */}
      <section id="featured" className="scroll-mt-24 pt-10">
        <SectionHeading
          kicker="Don't miss out"
          title="Biggest jackpots right now"
          description="The lotteries with the most life-changing prizes on the table today."
        />
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {featured.map((lottery) => (
            <LotteryCard key={lottery.id} lottery={lottery} featured />
          ))}
        </div>
      </section>

      {/* All lotteries */}
      {rest.length > 0 && (
        <section className="pt-10">
          <SectionHeading
            kicker="Full lineup"
            title="All lotteries"
            description="Every lottery we track, updated as jackpots and draws change."
            action={
              <Link href="/lotteries" className="inline-flex items-center gap-1 text-sm font-semibold text-rose-600 hover:text-rose-700">
                View all <ChevronRight size={16} />
              </Link>
            }
          />
          <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            {rest.map((lottery) => (
              <LotteryCard key={lottery.id} lottery={lottery} />
            ))}
          </div>
        </section>
      )}

      {/* Latest results */}
      {latestResults.length > 0 && (
        <section className="pt-10">
          <SectionHeading
            kicker="Fresh off the draw"
            title="Latest results"
            description="The most recent winning numbers from lotteries around the world."
          />
          <div className="space-y-3">
            {latestResults.map((lottery) => (
              <ResultRow key={lottery.id} draw={lottery.latest_draw!} lottery={lottery} />
            ))}
          </div>
        </section>
      )}

      {/* Guides / editorial content */}
      {pages.length > 0 && (
        <section className="pt-10">
          <SectionHeading
            kicker="Play smarter"
            title="Lottery guides"
            description="Straightforward, no-nonsense explainers to help you understand the games you play."
          />
          <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            {pages.map((page) => {
              const accent = accentFor(page.slug)
              return (
                <Link
                  key={page.id}
                  href={`/pages/${page.slug}`}
                  className={`group rounded-3xl border ${accent.softBorder} ${accent.soft} p-6 transition hover:-translate-y-1 hover:shadow-md`}
                >
                  <span className={`inline-flex h-10 w-10 items-center justify-center rounded-full ${accent.solid} text-white`}>
                    <BookOpen size={18} />
                  </span>
                  <h3 className="font-display mt-4 text-lg font-bold">{page.title}</h3>
                  {page.excerpt && <p className="mt-2 text-sm text-muted">{page.excerpt}</p>}
                  <span className={`mt-4 inline-flex items-center gap-1 text-sm font-semibold ${accent.text}`}>
                    Read guide <ChevronRight size={15} className="transition group-hover:translate-x-0.5" />
                  </span>
                </Link>
              )
            })}
          </div>
        </section>
      )}

      {lotteries.length === 0 && (
        <div className="flex flex-col items-center gap-3 rounded-3xl bg-surface py-16 text-center">
          <Trophy className="text-muted" />
          <p className="text-muted">No lottery data available right now. Check back soon.</p>
        </div>
      )}
    </div>
  )
}