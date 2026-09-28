import Link from 'next/link'
import type { Lottery, LotteryDraw } from '@/lib/types'
import { accentFor } from '@/lib/theme'
import { formatDrawDate, formatMoney } from '@/lib/format'
import { LotteryBall } from './lottery-ball'

export function ResultRow({ draw, lottery }: { draw: LotteryDraw; lottery: Lottery }) {
  const accent = accentFor(lottery.slug)
  const numbers = draw.numbers ?? []
  const mainNumbers = numbers.filter((n) => n.type === 'main').sort((a, b) => a.position - b.position)
  const otherNumbers = numbers.filter((n) => n.type !== 'main').sort((a, b) => a.position - b.position)

  return (
    <Link
      href={`/lotteries/${lottery.slug}`}
      className={`flex flex-col gap-3 rounded-2xl border ${accent.softBorder} bg-surface p-5 transition hover:-translate-y-0.5 hover:shadow-md sm:flex-row sm:items-center sm:justify-between`}
    >
      <div className="min-w-0">
        <p className={`text-xs font-semibold uppercase tracking-wide ${accent.text}`}>{lottery.name}</p>
        <p className="text-sm text-muted">{formatDrawDate(draw.draw_date)}</p>
      </div>

      <div className="flex flex-wrap items-center gap-1.5">
        {mainNumbers.map((n) => (
          <LotteryBall key={n.id} number={n.number} accent={accent} size="sm" />
        ))}
        {otherNumbers.map((n) => (
          <LotteryBall key={n.id} number={n.number} accent={accent} size="sm" variant="bonus" />
        ))}
        {numbers.length === 0 && <span className="text-sm text-muted">Numbers pending</span>}
      </div>

      {draw.jackpot && (
        <p className="text-sm font-semibold text-muted sm:text-right">
          Jackpot: {formatMoney(draw.jackpot, draw.jackpot_currency ?? lottery.jackpot_currency)}
        </p>
      )}
    </Link>
  )
}
