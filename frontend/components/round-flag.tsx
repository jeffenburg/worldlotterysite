import { countryCode } from '@/lib/flags'

const SIZES = {
  sm: 20,
  md: 24,
  lg: 32,
}

export function RoundFlag({ country, size = 'md' }: { country: string | null | undefined; size?: keyof typeof SIZES }) {
  const px = SIZES[size]

  return (
    <span
      className={`fi fi-${countryCode(country)} inline-block shrink-0 rounded-full ring-1 ring-black/5`}
      style={{ width: px, height: px, backgroundSize: 'cover', backgroundPosition: 'center' }}
      title={country ?? undefined}
    />
  )
}

