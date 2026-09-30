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

export interface ClickPayload {
  slug: string
  placement: string | null
  target_url: string
  ip: string | null
  country: string | null
  region: string | null
  city: string | null
  user_agent: string | null
  referer: string | null
}

export async function trackClick(payload: ClickPayload): Promise<void> {
  try {
    await fetch(`${API_BASE}/clicks`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify(payload),
      signal: AbortSignal.timeout(3000),
    })
  } catch {
    // Tracking must never block or break the redirect.
  }
}
