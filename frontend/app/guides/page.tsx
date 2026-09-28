import type { Metadata } from 'next'
import Link from 'next/link'
import { BookOpen } from 'lucide-react'
import { getLotteries, getPages } from '@/lib/api'
import { guidePages } from '@/lib/content'
import { SectionHeading } from '@/components/section-heading'
import { accentFor } from '@/lib/theme'

export const metadata: Metadata = {
  title: 'Lottery Guides',
  description: 'Straightforward guides to help you understand how lotteries work.',
}

export default async function GuidesIndexPage() {
  const [allPages, lotteries] = await Promise.all([getPages(), getLotteries()])
  const pages = guidePages(allPages, lotteries)

  return (
    <div className="mx-auto max-w-6xl px-4 py-10 sm:px-6">
      <SectionHeading kicker="Play smarter" title="Lottery guides" description="No-nonsense explainers to help you understand the games you play." />

      {pages.length > 0 ? (
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {pages.map((page) => {
            const accent = accentFor(page.slug)
            return (
              <Link
                key={page.id}
                href={`/pages/${page.slug}`}
                className={`group rounded-3xl border ${accent.softBorder} ${accent.soft} p-6 transition hover:-translate-y-1 hover:shadow-md`}
              >
                <span className={`inline-flex h-10 w-10 items-center justify-center rounded-full ${accent.solid} text-white`}>
                  <BookOpen size={18} />
                </span>
                <h3 className="font-display mt-4 text-lg font-bold">{page.title}</h3>
                {page.excerpt && <p className="mt-2 text-sm text-muted">{page.excerpt}</p>}
              </Link>
            )
          })}
        </div>
      ) : (
        <p className="text-muted">No guides published yet. Check back soon.</p>
      )}
    </div>
  )
}
