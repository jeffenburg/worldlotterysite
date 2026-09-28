import type { Lottery, Page } from './types'

// Pages used as lottery detail templates (matched by slug) aren't standalone editorial guides.
export function guidePages(pages: Page[], lotteries: Lottery[]): Page[] {
  const lotterySlugs = new Set(lotteries.map((l) => l.slug))
  return pages.filter((page) => !lotterySlugs.has(page.slug))
}
