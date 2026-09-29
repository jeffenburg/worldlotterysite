import type { ReactNode } from 'react'

export function SectionHeading({
  kicker,
  title,
  description,
  action,
}: {
  kicker?: string
  title: string
  description?: string
  action?: ReactNode
}) {
  return (
    <div className="font-display mb-5 flex flex-wrap items-end justify-between gap-4">
      <div>
        {kicker && (
          <p className="mb-1 text-sm font-semibold uppercase tracking-wide text-rose-500">{kicker}</p>
        )}
        <h2 className="font-display text-3xl font-bold sm:text-4xl">{title}</h2>
        {description && <p className="mt-1 max-w-2xl text-muted">{description}</p>}
      </div>
      {action}
    </div>
  )
}
