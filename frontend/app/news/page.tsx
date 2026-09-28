import type { Metadata } from 'next'
import Link from 'next/link'
import { Newspaper } from 'lucide-react'
import { getPosts } from '@/lib/api'
import { SectionHeading } from '@/components/section-heading'
import { accentFor } from '@/lib/theme'
import { formatDrawDate } from '@/lib/format'

export const metadata: Metadata = {
  title: 'Lottery News',
  description: 'The latest lottery news, jackpot updates and winner stories.',
}

export default async function NewsIndexPage() {
  const posts = await getPosts()

  return (
    <div className="mx-auto max-w-6xl px-4 py-10 sm:px-6">
      <SectionHeading kicker="Stay in the loop" title="Lottery news" description="Jackpot updates, winner stories and lottery headlines." />

      {posts.length > 0 ? (
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {posts.map((post) => {
            const accent = accentFor(post.slug)
            return (
              <Link
                key={post.id}
                href={`/news/${post.slug}`}
                className={`group rounded-3xl border ${accent.softBorder} ${accent.soft} p-6 transition hover:-translate-y-1 hover:shadow-md`}
              >
                <span className={`inline-flex h-10 w-10 items-center justify-center rounded-full ${accent.solid} text-white`}>
                  <Newspaper size={18} />
                </span>
                {post.published_at && <p className="mt-4 text-xs font-semibold uppercase tracking-wide text-muted">{formatDrawDate(post.published_at)}</p>}
                <h3 className="font-display mt-1 text-lg font-bold">{post.title}</h3>
                {post.excerpt && <p className="mt-2 text-sm text-muted">{post.excerpt}</p>}
              </Link>
            )
          })}
        </div>
      ) : (
        <p className="text-muted">No news yet. Check back soon.</p>
      )}
    </div>
  )
}
