import type { Page } from './types'

// Only 'guide' pages are standalone editorial content; 'lottery' and 'homepage' pages render in place.
export function guidePages(pages: Page[]): Page[] {
  return pages.filter((page) => page.page_type === 'guide')
}

export function homepagePage(pages: Page[]): Page | undefined {
  return pages.find((page) => page.page_type === 'homepage')
}
