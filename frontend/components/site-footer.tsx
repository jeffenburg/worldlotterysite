import Link from 'next/link'
import { Ticket } from 'lucide-react'
import type { Lottery, Page, Post } from '@/lib/types'

function FooterColumn({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <div>
      <p className="text-sm font-semibold uppercase tracking-wide text-muted">{title}</p>
      <ul className="mt-3 space-y-2 text-sm">{children}</ul>
    </div>
  )
}

export function SiteFooter({ lotteries, pages, posts }: { lotteries: Lottery[]; pages: Page[]; posts: Post[] }) {
  const countries = [...new Set(lotteries.map((l) => l.country).filter(Boolean))] as string[]

  return (
    <footer className="mt-12 border-t border-black/5 bg-surface">
      <div className="mx-auto max-w-6xl px-4 py-10 sm:px-6">
        <div className="grid grid-cols-2 gap-8 sm:grid-cols-4">
          <FooterColumn title="Countries">
            {countries.slice(0, 8).map((country) => (
              <li key={country}>
                <Link href={`/lotteries?country=${encodeURIComponent(country)}`} className="text-muted hover:text-foreground">
                  {country}
                </Link>
              </li>
            ))}
          </FooterColumn>

          <FooterColumn title="Lotteries">
            {lotteries.slice(0, 8).map((lottery) => (
              <li key={lottery.id}>
                <Link href={`/lotteries/${lottery.slug}`} className="text-muted hover:text-foreground">
                  {lottery.name}
                </Link>
              </li>
            ))}
            <li>
              <Link href="/lotteries" className="font-semibold text-rose-600 hover:text-rose-700">
                View all lotteries
              </Link>
            </li>
          </FooterColumn>

          <FooterColumn title="Guides">
            {pages.slice(0, 8).map((page) => (
              <li key={page.id}>
                <Link href={`/pages/${page.slug}`} className="text-muted hover:text-foreground">
                  {page.title}
                </Link>
              </li>
            ))}
            <li>
              <Link href="/guides" className="font-semibold text-rose-600 hover:text-rose-700">
                All guides
              </Link>
            </li>
          </FooterColumn>

          <FooterColumn title="News">
            {posts.slice(0, 8).map((post) => (
              <li key={post.id}>
                <Link href={`/news/${post.slug}`} className="text-muted hover:text-foreground">
                  {post.title}
                </Link>
              </li>
            ))}
            <li>
              <Link href="/news" className="font-semibold text-rose-600 hover:text-rose-700">
                All news
              </Link>
            </li>
          </FooterColumn>
        </div>

        <div className="mt-8 flex flex-col items-center justify-between gap-4 border-t border-black/5 pt-6 text-sm text-muted sm:flex-row">
          <div className="flex items-center gap-2">
            <span className="flex h-7 w-7 items-center justify-center rounded-full bg-rose-500 text-white">
              <Ticket size={14} />
            </span>
            <span>© {new Date().getFullYear()} World Lottery. Independent results & information site.</span>
          </div>
          <p>Not affiliated with any lottery operator. Always play responsibly.</p>
        </div>
      </div>
    </footer>
  )
}
