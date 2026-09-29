const AFFILIATE_ID = '8436'

export function theLotterPlayUrl(thelotterSlug: string | null | undefined): string | null {
  if (!thelotterSlug) return null

  return `https://www.thelotter.com/lottery-tickets/${thelotterSlug}/?tl_affid=${AFFILIATE_ID}`
}
