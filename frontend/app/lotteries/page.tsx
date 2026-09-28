import type { Metadata } from 'next'
import Link from 'next/link'
import { getLotteries } from '@/lib/api'
import { LotteryCard } from '@/components/lottery-card'
import { SectionHeading } from '@/components/section-heading'

export const metadata: Metadata = {
  title: 'All Lotteries',
  description: 'Browse every lottery we track, with live jackpots, next draw dates and recent results.',
}

export default async function LotteriesPage({
  searchParams,
}: {
  searchParams: Promise<{ country?: string }>
}) {
  const { country } = await searchParams
  const lotteries = await getLotteries()

  const filtered = country ? lotteries.filter((l) => l.country === country) : lotteries
  const countries = [...new Set(lotteries.map((l) => l.country).filter(Boolean))] as string[]

  return (
    <div className="mx-auto max-w-6xl px-4 py-10 sm:px-6">
      <SectionHeading
        kicker="Full lineup"
        title={country ? `Lotteries in ${country}` : 'All lotteries'}
        description="Every lottery we track, updated as jackpots and draws change."
      />

      <div className="mb-6 flex flex-wrap gap-2">
        <Link
          href="/lotteries"
          className={`rounded-full px-4 py-1.5 text-sm font-semibold transition ${
            !country ? 'bg-rose-500 text-white' : 'bg-surface text-muted hover:text-foreground'
          }`}
        >
          All countries
        </Link>
        {countries.map((c) => (
          <Link
            key={c}
            href={`/lotteries?country=${encodeURIComponent(c)}`}
            className={`rounded-full px-4 py-1.5 text-sm font-semibold transition ${
              country === c ? 'bg-rose-500 text-white' : 'bg-surface text-muted hover:text-foreground'
            }`}
          >
            {c}
          </Link>
        ))}
      </div>

      {filtered.length > 0 ? (
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {filtered.map((lottery) => (
            <LotteryCard key={lottery.id} lottery={lottery} />
          ))}
        </div>
      ) : (
        <p className="text-muted">No lotteries found for this country yet.</p>
      )}
    </div>
  )
}
