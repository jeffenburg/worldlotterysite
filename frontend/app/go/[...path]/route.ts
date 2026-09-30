import { NextResponse, after } from 'next/server'
import { theLotterPlayUrl } from '@/lib/thelotter'
import { trackClick } from '@/lib/api'

export const dynamic = 'force-dynamic'

function header(request: Request, name: string): string | null {
  const value = request.headers.get(name)
  return value ? decodeURIComponent(value) : null
}

// /go/{slug} or /go/{slug}/{placement}
export async function GET(request: Request, { params }: { params: Promise<{ path: string[] }> }) {
  const { path } = await params
  const [slug, placement] = path
  const url = theLotterPlayUrl(slug)

  if (!url) {
    return new NextResponse('Not found', { status: 404 })
  }

  // Geo headers are populated by Vercel (x-vercel-ip-*) or Cloudflare (cf-*); null elsewhere.
  const payload = {
    slug,
    placement: placement ?? null,
    target_url: url,
    ip: header(request, 'x-forwarded-for')?.split(',')[0].trim() ?? header(request, 'x-real-ip'),
    country: header(request, 'x-vercel-ip-country') ?? header(request, 'cf-ipcountry'),
    region: header(request, 'x-vercel-ip-country-region'),
    city: header(request, 'x-vercel-ip-city'),
    user_agent: header(request, 'user-agent'),
    referer: header(request, 'referer'),
  }

  after(() => trackClick(payload))

  return NextResponse.redirect(url, 302)
}
