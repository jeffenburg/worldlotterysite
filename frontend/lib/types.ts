export interface LotteryDrawNumber {
  id: number
  lottery_draw_id: number
  type: string
  number: number
  position: number
}

export interface LotteryDraw {
  id: number
  lottery_id: number
  draw_date: string
  jackpot: string | null
  jackpot_currency: string | null
  source_url: string | null
  numbers?: LotteryDrawNumber[]
  lottery?: Lottery
}

export interface Lottery {
  id: number
  name: string
  slug: string
  country: string | null
  description: string | null
  jackpot: string | null
  jackpot_currency: string | null
  jackpot_usd: string | null
  next_draw_at: string | null
  active: boolean
  thelotter_name: string | null
  thelotter_slug: string | null
  thelotter_id: number | null
  main_numbers_count: number | null
  bonus_numbers_count: number | null
  latest_draw?: LotteryDraw | null
  draws?: LotteryDraw[]
}

export interface Page {
  id: number
  title: string
  slug: string
  excerpt: string | null
  content: string | null
  meta_title: string | null
  meta_description: string | null
  active: boolean
}

export interface Post {
  id: number
  title: string
  slug: string
  excerpt: string | null
  content: string | null
  image_url: string | null
  meta_title: string | null
  meta_description: string | null
  published_at: string | null
  active: boolean
}
