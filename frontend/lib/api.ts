import type { Lottery, Page, Post } from './types'

const API_BASE = process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000/api'

async function getJson<T>(path: string): Promise<T | null> {
  const response = await fetch(`${API_BASE}${path}`, { cache: 'no-store' })

  if (!response.ok) {
    return null
  }

  return response.json() as Promise<T>
}

export function getLotteries() {
  return getJson<Lottery[]>('/lotteries').then((data) => data ?? [])
}

export function getLottery(slug: string) {
  return getJson<Lottery>(`/lotteries/${slug}`)
}

export function getPages() {
  return getJson<Page[]>('/pages').then((data) => data ?? [])
}

export function getPage(slug: string) {
  return getJson<Page>(`/pages/${slug}`)
}

export function getPosts() {
  return getJson<Post[]>('/posts').then((data) => data ?? [])
}

export function getPost(slug: string) {
  return getJson<Post>(`/posts/${slug}`)
}
