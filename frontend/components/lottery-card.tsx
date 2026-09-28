import Link from 'next/link'
import { Calendar, ChevronRight } from 'lucide-react'
import type { Lottery } from '@/lib/types'
import { accentFor } from '@/lib/theme'
import { formatCompactMoney, formatUSD, formatRelativeDraw, formatDrawTime } from '@/lib/format'
import { DecorativeCircles } from './decorative-circles'
import { LotteryBall } from './lottery-ball'
import { RoundFlag } from './round-flag'

export function LotteryCard({ lottery, featured = false }: { lottery: Lottery; featured?: boolean }) {
  const accent = accentFor(lottery.slug)
  const showUsd = lottery.jackpot_currency !== 'USD' && lottery.jackpot_usd
  const numbers = lottery.latest_draw?.numbers ?? []
  const mainNumbers = numbers.filter((n) => n.type === 'main').sort((a, b) => a.position - b.position)

  return (
    <Link
      href={`/lotteries/${lottery.slug}`}
      className={`group relative flex flex-col overflow-hidden rounded-3xl border ${accent.softBorder} ${accent.soft} p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg ${
        featured ? 'sm:p-8' : ''
      }`}
    >
      <DecorativeCircles accent={accent} />

      <div className="relative flex flex-1 flex-col">

        <h3 className={`font-display mt-1 flex items-center gap-2 font-bold ${featured ? 'text-2xl' : 'text-xl'}`}>
          <RoundFlag country={lottery.country} size={featured ? 'lg' : 'md'} />
            {lottery.name}
            {featured && (
                <div className="flex justify-end">
                    <span className={`rounded-full ${accent.solid} px-3 py-1 text-xs font-bold text-white`}>
                    Big jackpot
                    </span>
                </div>
            )}
        </h3>

        <div className="mt-3">
          <p className="text-xs font-semibold uppercase tracking-wide text-muted">Jackpot</p>
          <p
            className={`font-display font-extrabold leading-none ${accent.text} ${
              featured ? 'text-5xl' : 'text-4xl'
            }`}
          >
            {formatCompactMoney(lottery.jackpot, lottery.jackpot_currency)}
          </p>
          {showUsd && <p className="mt-1 text-sm text-muted">≈ {formatUSD(lottery.jackpot_usd)} USD</p>}
        </div>

        {mainNumbers.length > 0 && (
          <div className="mt-3 flex flex-wrap gap-1.5">
            {mainNumbers.map((n) => (
              <LotteryBall key={n.id} number={n.number} accent={accent} size="sm" />
            ))}
          </div>
        )}

        <div className="mt-3 flex items-center gap-1.5 text-sm font-medium text-muted">
          <Calendar size={15} />
          Next draw: {formatRelativeDraw(lottery.next_draw_at)}
          {lottery.next_draw_at && <span className="text-muted/70">· {formatDrawTime(lottery.next_draw_at)}</span>}
        </div>

        <div className="mt-4">
          <span
            className={`inline-flex items-center gap-1 rounded-full ${accent.solid} ${accent.solidHover} px-4 py-2 text-sm font-semibold text-white transition`}
          >
            View details & results
            <ChevronRight size={16} className="transition group-hover:translate-x-0.5" />
          </span>
        </div>
      </div>
    </Link>
  )
}
