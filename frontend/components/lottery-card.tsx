import Link from 'next/link'
import { Calendar, ChevronRight, Ticket } from 'lucide-react'
import type { Lottery } from '@/lib/types'
import { accentFor } from '@/lib/theme'
import { formatCompactMoney, formatUSD, formatRelativeDraw, formatDrawTime } from '@/lib/format'
import { theLotterPlayUrl } from '@/lib/thelotter'
import { DecorativeCircles } from './decorative-circles'
import { LotteryBall } from './lottery-ball'
import { RoundFlag } from './round-flag'

export function LotteryCard({ lottery, featured = false }: { lottery: Lottery; featured?: boolean }) {
  const accent = accentFor(lottery.slug)
  const showUsd = lottery.jackpot_currency !== 'USD' && lottery.jackpot_usd
  const numbers = lottery.latest_draw?.numbers ?? []
  const mainNumbers = numbers.filter((n) => n.type === 'main').sort((a, b) => a.position - b.position)
  const playUrl = theLotterPlayUrl(lottery.thelotter_slug)

  return (
    <div
      className={`group relative flex flex-col overflow-hidden rounded-3xl border ${accent.softBorder} ${accent.soft} p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg ${
        featured ? 'sm:p-8' : ''
      }`}
    >
      <DecorativeCircles accent={accent} />

      {/* Stretched link: makes the whole card clickable while the "Play" button below stays its own target. */}
      <Link href={`/lotteries/${lottery.slug}`} className="absolute inset-0" aria-label={`View ${lottery.name} results and details`} />

      <div className="flex flex-1 flex-col items-center">
        <div className="flex items-center justify-between gap-2">
          <h3 className={`font-display flex items-center gap-2 font-bold ${featured ? 'text-2xl' : 'text-2xl'}`}>
            <RoundFlag country={lottery.country} size={featured ? 'lg' : 'md'} />
            {lottery.name}
          </h3>
        </div>

        <div className="mt-3">
          <p className={`font-display text-xs font-bold uppercase tracking-wider text-center ${accent.text}`}>Jackpot</p>
          <p
            className={`font-display font-extrabold leading-none ${accent.text} ${
              featured ? 'text-5xl' : 'text-4xl'
            }`}
          >
            {formatCompactMoney(lottery.jackpot, lottery.jackpot_currency)}
          </p>
        </div>

        <p className={`font-display mt-1 text-xs font-bold uppercase tracking-wider ${accent.text}`}>Last Winning numbers</p>
        {mainNumbers.length > 0 && (
          <div className="mt-1 flex flex-wrap gap-1.5">
            {mainNumbers.map((n) => (
              <LotteryBall key={n.id} number={n.number} accent={accent} size="sm" />
            ))}
          </div>
        )}

        <p className={`mt-3 font-display text-xs font-bold uppercase tracking-wider ${accent.text}`}>Next draw</p>
        <div className="font-display mt-1 inline-flex w-fit items-center gap-1.5 rounded-full bg-surface px-3 py-1.5 text-xs font-semibold text-foreground">
          <Calendar size={14} className={accent.text} />
          <span>{formatRelativeDraw(lottery.next_draw_at)}</span>
          {lottery.next_draw_at && <span className="text-muted">· {formatDrawTime(lottery.next_draw_at)}</span>}
        </div>

        <div className="mt-4 flex flex-wrap items-center gap-2">
          <span
            className={`font-display inline-flex items-center gap-1 rounded-full ${accent.solid} ${accent.solidHover} px-4 py-2 text-sm font-bold text-white transition`}
          >
            View details
            <ChevronRight size={16} className="transition group-hover:translate-x-0.5" />
          </span>
          {playUrl && (
            <a
              href={playUrl}
              target="_blank"
              rel="noopener noreferrer"
              className="font-display relative z-10 inline-flex items-center gap-1 rounded-full border border-foreground/15 bg-surface px-4 py-2 text-sm font-bold text-foreground transition hover:bg-white"
            >
              <Ticket size={14} /> Play Now
            </a>
          )}
        </div>
      </div>
    </div>
  )
}

