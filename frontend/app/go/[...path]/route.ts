import { NextResponse } from 'next/server'
import { theLotterPlayUrl } from '@/lib/thelotter'

export const dynamic = 'force-dynamic'

// /go/{slug} or /go/{slug}/{placement} — placement is ignored for now.
export async function GET(_request: Request, { params }: { params: Promise<{ path: string[] }> }) {
  const { path } = await params
  const url = theLotterPlayUrl(path[0])

  if (!url) {
    return new NextResponse('Not found', { status: 404 })
  }

  return NextResponse.redirect(url, 302)
}
