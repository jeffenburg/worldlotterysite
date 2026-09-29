const CURRENCY_SYMBOLS: Record<string, string> = {
  USD: '$',
  EUR: '€',
  GBP: '£',
  AUD: 'A$',
  CAD: 'C$',
  ZAR: 'R',
}

export function currencySymbol(currency: string | null | undefined): string {
  if (!currency) return ''
  return CURRENCY_SYMBOLS[currency] ?? `${currency} `
}

// Compact jackpot number for display, e.g. 389000000 -> "389M"
export function formatCompactAmount(value: string | number | null | undefined): string {
  const amount = typeof value === 'string' ? parseFloat(value) : value

  if (amount === null || amount === undefined || Number.isNaN(amount)) {
    return '—'
  }

  return new Intl.NumberFormat('en-US', {
    notation: 'compact',
    maximumFractionDigits: 1,
  }).format(amount)
}

export function formatMoney(value: string | number | null | undefined, currency?: string | null): string {
  const amount = typeof value === 'string' ? parseFloat(value) : value

  if (amount === null || amount === undefined || Number.isNaN(amount)) {
    return '—'
  }

  return `${currencySymbol(currency)}${new Intl.NumberFormat('en-US').format(amount)}`
}

export function formatCompactMoney(value: string | number | null | undefined, currency?: string | null): string {
  return `${currencySymbol(currency)}${formatCompactAmount(value)}`
}

export function formatUSD(value: string | number | null | undefined): string {
  const amount = typeof value === 'string' ? parseFloat(value) : value

  if (amount === null || amount === undefined || Number.isNaN(amount)) {
    return '—'
  }

  return `$${new Intl.NumberFormat('en-US', {
    notation: 'compact',
    maximumFractionDigits: 1,
  }).format(amount)}`
}

export function formatDrawDate(value: string | null | undefined): string {
  if (!value) return 'TBA'

  return new Intl.DateTimeFormat('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  }).format(new Date(value))
}

export function formatDrawTime(value: string | null | undefined): string {
  if (!value) return ''

  return new Intl.DateTimeFormat('en-US', {
    hour: 'numeric',
    minute: '2-digit'
  }).format(new Date(value))
}

// "Today", "Tomorrow", "in 4 days", or a short date for anything further out / in the past.
export function formatRelativeDraw(value: string | null | undefined): string {
  if (!value) return 'Date TBA'

  const target = new Date(value)
  const now = new Date()

  const startOfDay = (d: Date) => new Date(d.getFullYear(), d.getMonth(), d.getDate())
  const diffDays = Math.round(
    (startOfDay(target).getTime() - startOfDay(now).getTime()) / (1000 * 60 * 60 * 24)
  )

  if (diffDays < 0) return formatDrawDate(value)
  if (diffDays === 0) return 'Today'
  if (diffDays === 1) return 'Tomorrow'
  if (diffDays <= 6) return `In ${diffDays} days`

  return formatDrawDate(value)
}
