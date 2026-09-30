import type { Metadata } from 'next'
import Image from 'next/image'
import { notFound } from 'next/navigation'
import { Newspaper } from 'lucide-react'
import { getPost } from '@/lib/api'
import { formatDrawDate } from '@/lib/format'

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>
}): Promise<Metadata> {
  const { slug } = await params
  const post = await getPost(slug)

  if (!post) {
    return { title: 'Article not found' }
  }

  return {
    title: post.meta_title || post.title,
    description: post.meta_description || post.excerpt || undefined,
    openGraph: post.image_url ? { images: [post.image_url] } : undefined,
  }
}

export default async function NewsPage({
  params,
}: {
  params: Promise<{ slug: string }>
}) {
  const { slug } = await params
  const post = await getPost(slug)

  if (!post) {
    notFound()
  }

  return (
    <article className="mx-auto max-w-3xl px-4 py-10 sm:px-6">
      <span className="inline-flex h-10 w-10 items-center justify-center rounded-full bg-rose-500 text-white">
        <Newspaper size={18} />
      </span>
      {post.published_at && <p className="mt-4 text-sm font-semibold text-muted">{formatDrawDate(post.published_at)}</p>}
      <h1 className="font-display mt-2 text-4xl font-extrabold">{post.title}</h1>
      {post.excerpt && <p className="mt-4 text-lg text-muted">{post.excerpt}</p>}
      {post.image_url && (
        <div className="relative mt-8 aspect-[16/9] w-full overflow-hidden rounded-3xl">
          <Image src={post.image_url} alt={post.title} fill priority sizes="(min-width: 768px) 768px, 100vw" className="object-cover" />
        </div>
      )}
      {post.content && (
        // Admin-authored CMS content, not user-submitted — safe to render as HTML.
        <div className="prose prose-neutral mt-8 max-w-none" dangerouslySetInnerHTML={{ __html: post.content }} />
      )}
    </article>
  )
}
