import type { Metadata } from 'next'
import Link from 'next/link'
import { Fragment, type ReactNode } from 'react'
import { notFound } from 'next/navigation'
import { Calendar, ChevronRight, Clock, MapPin, Ticket, TrendingUp } from 'lucide-react'
import { getLottery, getLotteries, getPage } from '@/lib/api'
import { accentFor } from '@/lib/theme'
import { formatCompactMoney, formatDrawDate, formatDrawTime, formatMoney, formatRelativeDraw, formatUSD } from '@/lib/format'
import { theLotterPlayUrl } from '@/lib/thelotter'
import { LotteryBall } from '@/components/lottery-ball'
import { SectionHeading } from '@/components/section-heading'
import { NumberFrequency } from '@/components/number-frequency'
import { LotteryCard } from '@/components/lottery-card'
import { DecorativeCircles } from '@/components/decorative-circles'
import { RoundFlag } from '@/components/round-flag'

// CMS `pages` rows (matched by lottery slug) can template these sections into their content via {token} markers.
const SECTION_TOKENS = ['hero', 'latest', 'results', 'stats', 'explainer', 'related'] as const
type SectionToken = (typeof SECTION_TOKENS)[number]

function renderTemplatedContent(content: string, sections: Partial<Record<SectionToken, ReactNode>>): ReactNode {
  const tokenPattern = new RegExp(`\\{(${SECTION_TOKENS.join('|')})\\}`, 'g')

  return content.split(tokenPattern).map((part, index) => {
    if ((SECTION_TOKENS as readonly string[]).includes(part)) {
      return <Fragment key={index}>{sections[part as SectionToken]}</Fragment>
    }
    if (!part.trim()) {
      return null
    }
    return <div key={index} className="prose prose-neutral pt-10" dangerouslySetInnerHTML={{ __html: part }} />
  })
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>
}): Promise<Metadata> {
  const { slug } = await params
  const [lottery, page] = await Promise.all([getLottery(slug), getPage(slug)])

  if (!lottery) {
    return { title: 'Lottery not found' }
  }

  return {
    title: page?.meta_title || `${lottery.name} Results & Jackpot`,
    description:
      page?.meta_description ||
      lottery.description ||
      `Latest ${lottery.name} jackpot, winning numbers, next draw date and results.`,
  }
}

export default async function LotteryPage({
  params,
}: {
  params: Promise<{ slug: string }>
}) {
  const { slug } = await params
  const [lottery, allLotteries, page] = await Promise.all([getLottery(slug), getLotteries(), getPage(slug)])

  if (!lottery) {
    notFound()
  }

  const accent = accentFor(lottery.slug)
  const draws = lottery.draws ?? []
  const [latestDraw, ...previousDraws] = draws
  const showUsd = lottery.jackpot_currency !== 'USD' && lottery.jackpot_usd
  const templateContent = page?.content?.trim() || null
  const playUrl = theLotterPlayUrl(lottery.thelotter_slug)

  const mainNumbers = (latestDraw?.numbers ?? [])
    .filter((n) => n.type === 'main')
    .sort((a, b) => a.position - b.position)
  const otherNumbers = (latestDraw?.numbers ?? [])
    .filter((n) => n.type !== 'main')
    .sort((a, b) => a.position - b.position)

  const related = allLotteries
    .filter((l) => l.id !== lottery.id)
    .sort((a, b) => (a.country === lottery.country ? -1 : 0) - (b.country === lottery.country ? -1 : 0))
    .slice(0, 3)

  const sections: Record<SectionToken, ReactNode> = {
    hero: (
      <section className={`relative overflow-hidden rounded-3xl border ${accent.softBorder} ${accent.soft} px-6 py-8 sm:px-10 sm:py-10`}>
        <DecorativeCircles accent={accent} />
        <div className="relative">
          <h1 className="font-display flex justify-center items-center gap-3 text-4xl font-extrabold sm:text-5xl">
            <RoundFlag country={lottery.country} size="lg" />
            {lottery.name}
          </h1>

          <div className="mt-3 flex flex-col text-center gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
              <p className={`font-display text-sm font-bold uppercase tracking-wider ${accent.text}`}>Current jackpot</p>
              <p className={`font-display text-6xl font-extrabold leading-none ${accent.text} sm:text-7xl`}>
                {formatCompactMoney(lottery.jackpot, lottery.jackpot_currency)}
              </p>
              {playUrl && (
                <a
                  href={playUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className={`font-display mt-2 flex w-fit items-center gap-2 rounded-full ${accent.solid} ${accent.solidHover} px-6 py-2 text-2xl font-bold text-white shadow-sm transition`}
                >
                  <Ticket size={16} /> Play {lottery.name} Now
                </a>
              )}
            </div>

            <div className={`flex items-center gap-3 rounded-2xl bg-surface px-5 py-3`}>
              <span className={`flex h-11 w-11 items-center justify-center rounded-full ${accent.solid} text-white`}>
                <Calendar size={20} />
              </span>
              <div>
                <p className={`font-display text-xs font-bold uppercase tracking-wider ${accent.text}`}>Next draw</p>
                <p className="font-display text-lg font-bold">{formatRelativeDraw(lottery.next_draw_at)}</p>
                {lottery.next_draw_at && (
                  <p className="flex items-center gap-1 text-sm text-muted">
                    <Clock size={13} /> {formatDrawDate(lottery.next_draw_at)} · {formatDrawTime(lottery.next_draw_at)}
                  </p>
                )}
              </div>
            </div>
          </div>
        </div>
      </section>
    ),

    latest: latestDraw && (
      <section className="pt-10">
        <SectionHeading
          kicker={formatDrawDate(latestDraw.draw_date)}
          title="Latest winning numbers"
          description={latestDraw.jackpot ? `Draw jackpot: ${formatMoney(latestDraw.jackpot, latestDraw.jackpot_currency ?? lottery.jackpot_currency)}` : undefined}
        />
        <div className="flex flex-wrap items-center gap-3 rounded-3xl bg-surface p-6">
          {mainNumbers.map((n) => (
            <LotteryBall key={n.id} number={n.number} accent={accent} size="lg" />
          ))}
          {otherNumbers.map((n) => (
            <LotteryBall key={n.id} number={n.number} accent={accent} size="lg" variant="bonus" />
          ))}
        </div>
      </section>
    ),

    results: previousDraws.length > 0 && (
      <section className="pt-10">
        <SectionHeading kicker="History" title="Recent results" />
        <div className="space-y-3">
          {previousDraws.map((draw) => {
            const drawMain = (draw.numbers ?? []).filter((n) => n.type === 'main').sort((a, b) => a.position - b.position)
            const drawOther = (draw.numbers ?? []).filter((n) => n.type !== 'main').sort((a, b) => a.position - b.position)
            return (
              <div
                key={draw.id}
                className={`flex flex-col gap-3 rounded-2xl border ${accent.softBorder} bg-surface p-5 sm:flex-row sm:items-center sm:justify-between`}
              >
                <p className="text-sm font-semibold text-muted">{formatDrawDate(draw.draw_date)}</p>
                <div className="flex flex-wrap gap-1.5">
                  {drawMain.map((n) => (
                    <LotteryBall key={n.id} number={n.number} accent={accent} size="sm" />
                  ))}
                  {drawOther.map((n) => (
                    <LotteryBall key={n.id} number={n.number} accent={accent} size="sm" variant="bonus" />
                  ))}
                </div>
                {draw.jackpot && (
                  <p className="text-sm text-muted sm:text-right">
                    Jackpot: {formatMoney(draw.jackpot, draw.jackpot_currency ?? lottery.jackpot_currency)}
                  </p>
                )}
              </div>
            )
          })}
        </div>
      </section>
    ),

    stats: draws.length > 0 && (
      <section className="pt-10">
        <SectionHeading
          kicker="Trends"
          title="Number frequency"
          description={`How often each number has appeared across the last ${draws.length} recorded draw${draws.length === 1 ? '' : 's'}.`}
        />
        <div className="rounded-3xl bg-surface p-6">
          <NumberFrequency draws={draws} lottery={lottery} />
        </div>
      </section>
    ),

    explainer: (
      <section className="pt-10">
        <SectionHeading kicker="Good to know" title={`How ${lottery.name} works`} />
        <div className={`grid gap-4 rounded-3xl border ${accent.softBorder} ${accent.soft} p-6 sm:grid-cols-3`}>
          <div className="flex items-center gap-3">
            <span className={`flex h-10 w-10 items-center justify-center rounded-full ${accent.solid} text-white`}>
              <TrendingUp size={18} />
            </span>
            <div>
              <p className={`font-display text-xs font-bold uppercase tracking-wider ${accent.text}`}>Main numbers</p>
              <p className="font-display font-bold">{lottery.main_numbers_count || mainNumbers.length || '—'}</p>
            </div>
          </div>
          <div className="flex items-center gap-3">
            <span className="flex h-10 w-10 items-center justify-center rounded-full bg-foreground text-white">
              <TrendingUp size={18} />
            </span>
            <div>
              <p className={`font-display text-xs font-bold uppercase tracking-wider ${accent.text}`}>Bonus numbers</p>
              <p className="font-display font-bold">{lottery.bonus_numbers_count || otherNumbers.length || '—'}</p>
            </div>
          </div>
          <div className="flex items-center gap-3">
            <span className={`flex h-10 w-10 items-center justify-center rounded-full ${accent.solid} text-white`}>
              <MapPin size={18} />
            </span>
            <div>
              <p className={`font-display text-xs font-bold uppercase tracking-wider ${accent.text}`}>Country</p>
              <p className="font-display font-bold">{lottery.country ?? '—'}</p>
            </div>
          </div>
        </div>
      </section>
    ),

    related: related.length > 0 && (
      <section className="pt-10">
        <SectionHeading
          kicker="Keep exploring"
          title="Related lotteries"
          action={
            <Link href="/lotteries" className="inline-flex items-center gap-1 text-sm font-semibold text-rose-600 hover:text-rose-700">
              View all <ChevronRight size={16} />
            </Link>
          }
        />
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {related.map((l) => (
            <LotteryCard key={l.id} lottery={l} />
          ))}
        </div>
      </section>
    ),
  }

  return (
    <div className="mx-auto max-w-6xl px-4 pb-16 sm:px-6">
      {templateContent
        ? renderTemplatedContent(templateContent, sections)
        : SECTION_TOKENS.map((token) => <Fragment key={token}>{sections[token]}</Fragment>)}
    </div>
  )
}
