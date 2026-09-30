import type { Metadata } from 'next'
import { notFound } from 'next/navigation'
import { BookOpen } from 'lucide-react'
import { getPage } from '@/lib/api'

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>
}): Promise<Metadata> {
  const { slug } = await params
  const page = await getPage(slug)

  if (!page) {
    return { title: 'Page not found' }
  }

  return {
    title: page.meta_title || page.title,
    description: page.meta_description || page.excerpt || undefined,
  }
}

export default async function ContentPage({
  params,
}: {
  params: Promise<{ slug: string }>
}) {
  const { slug } = await params
  const page = await getPage(slug)

  if (!page || page.page_type !== 'guide') {
    notFound()
  }

  return (
    <article className="mx-auto max-w-3xl px-4 py-10 sm:px-6">
      <span className="inline-flex h-10 w-10 items-center justify-center rounded-full bg-rose-500 text-white">
        <BookOpen size={18} />
      </span>
      <h1 className="font-display mt-4 text-4xl font-extrabold">{page.title}</h1>
      {page.excerpt && <p className="mt-4 text-lg text-muted">{page.excerpt}</p>}
      {page.content && (
        // Admin-authored CMS content, not user-submitted — safe to render as HTML.
        <div className="prose prose-neutral mt-8 max-w-none" dangerouslySetInnerHTML={{ __html: page.content }} />
      )}
    </article>
  )
}
