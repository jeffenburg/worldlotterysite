import type { ReactNode } from 'react'

export function Badge({ children, className = '' }: { children: ReactNode; className?: string }) {
  return (
    <span
      className={`inline-flex items-center gap-1 rounded-full bg-surface px-3 py-1 text-xs font-semibold text-muted ${className}`}
    >
      {children}
    </span>
  )
}
