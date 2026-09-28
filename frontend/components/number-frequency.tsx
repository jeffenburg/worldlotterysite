import type { Lottery } from '@/lib/types'
import { accentFor } from '@/lib/theme'
import { LotteryBall } from './lottery-ball'

export function NumberFrequency({ draws, lottery }: { draws: { numbers?: { type: string; number: number }[] }[]; lottery: Lottery }) {
  const accent = accentFor(lottery.slug)
  const counts = new Map<number, number>()

  draws.forEach((draw) => {
    draw.numbers
      ?.filter((n) => n.type === 'main')
      .forEach((n) => counts.set(n.number, (counts.get(n.number) ?? 0) + 1))
  })

  const entries = [...counts.entries()].sort((a, b) => b[1] - a[1]).slice(0, 10)

  if (entries.length === 0) {
    return <p className="text-sm text-muted">Not enough draw history yet to show number trends.</p>
  }

  const max = Math.max(...entries.map(([, count]) => count))

  return (
    <div className="space-y-3">
      {entries.map(([number, count]) => (
        <div key={number} className="flex items-center gap-3">
          <LotteryBall number={number} accent={accent} size="sm" />
          <div className="h-3 flex-1 overflow-hidden rounded-full bg-surface">
            <div className={`h-3 rounded-full ${accent.solid}`} style={{ width: `${(count / max) * 100}%` }} />
          </div>
          <span className="w-14 shrink-0 text-right text-sm text-muted">
            {count}× drawn
          </span>
        </div>
      ))}
    </div>
  )
}
