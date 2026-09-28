import Link from 'next/link'
import { Ticket, Newspaper, BookOpen, LayoutGrid } from 'lucide-react'
import { MobileNav } from './mobile-nav'

const NAV_LINKS = [
  { href: '/lotteries', label: 'Lotteries', icon: LayoutGrid },
  { href: '/guides', label: 'Guides', icon: BookOpen },
  { href: '/news', label: 'News', icon: Newspaper },
]

export function SiteHeader() {
  return (
    <header className="sticky top-0 z-30 border-b border-black/5 bg-background/90 backdrop-blur">
      <div className="relative mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3 sm:px-6">
        <Link href="/" className="flex items-center gap-2">
          <span className="flex h-9 w-9 items-center justify-center rounded-full bg-rose-500 text-white shadow-sm">
            <Ticket size={18} />
          </span>
          <span className="font-display text-xl font-bold">World Lottery Site</span>
        </Link>

        <nav className="hidden items-center gap-1 sm:flex sm:gap-2">
          {NAV_LINKS.map(({ href, label, icon: Icon }) => (
            <Link
              key={href}
              href={href}
              className="flex items-center gap-1.5 rounded-full px-3 py-2 text-sm font-semibold text-muted transition hover:bg-surface hover:text-foreground"
            >
              <Icon size={16} />
              {label}
            </Link>
          ))}
        </nav>

        <MobileNav
          links={NAV_LINKS.map(({ href, label, icon: Icon }) => ({ href, label, icon: <Icon size={16} /> }))}
        />
      </div>
    </header>
  )
}

