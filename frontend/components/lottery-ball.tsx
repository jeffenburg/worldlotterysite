import type { Accent } from '@/lib/theme'

const SIZES = {
  sm: 'h-8 w-8 text-xs',
  md: 'h-10 w-10 text-sm',
  lg: 'h-14 w-14 text-lg',
}

export function LotteryBall({
  number,
  variant = 'main',
  accent,
  size = 'md',
}: {
  number: number
  variant?: 'main' | 'bonus'
  accent: Accent
  size?: keyof typeof SIZES
}) {
  const base = `font-display inline-flex shrink-0 items-center justify-center rounded-full font-bold shadow-sm ${SIZES[size]}`

  if (variant === 'main') {
    return <span className={`${base} ${accent.solid} text-white`}>{number}</span>
  }

  return (
    <span className={`${base} bg-foreground text-white ring-2 ring-amber-400 ring-offset-2 ring-offset-surface`}>
      {number}
    </span>
  )
}
