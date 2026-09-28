import type { Accent } from '@/lib/theme'

// Purely decorative background circles (like scattered lottery balls) — aria-hidden, no content.
export function DecorativeCircles({ accent, className = '' }: { accent: Accent; className?: string }) {
  return (
    <div aria-hidden className={`pointer-events-none absolute inset-0 overflow-hidden ${className}`}>
      <div className={`absolute -right-6 -top-8 h-28 w-28 rounded-full ${accent.solid} opacity-10`} />
      <div className={`absolute -bottom-10 -left-8 h-24 w-24 rounded-full ${accent.solid} opacity-10`} />
      <div className={`absolute bottom-6 right-10 h-8 w-8 rounded-full ${accent.solid} opacity-10`} />
    </div>
  )
}
