// Deterministic accent color system — each lottery gets a consistent accent
// across the whole site, derived from its slug. Class names are written out
// in full (never templated) so Tailwind's scanner can pick them up.

export interface Accent {
  name: string
  solid: string // background fill for balls / buttons
  solidHover: string
  text: string // accent-colored text
  soft: string // pale card tint background
  softBorder: string // pale card border
  ring: string
}

const ACCENTS: Accent[] = [
  {
    name: 'coral',
    solid: 'bg-rose-500',
    solidHover: 'hover:bg-rose-600',
    text: 'text-rose-600',
    soft: 'bg-rose-50',
    softBorder: 'border-rose-200',
    ring: 'ring-rose-300',
  },
  {
    name: 'teal',
    solid: 'bg-teal-500',
    solidHover: 'hover:bg-teal-600',
    text: 'text-teal-600',
    soft: 'bg-teal-50',
    softBorder: 'border-teal-200',
    ring: 'ring-teal-300',
  },
  {
    name: 'indigo',
    solid: 'bg-indigo-500',
    solidHover: 'hover:bg-indigo-600',
    text: 'text-indigo-600',
    soft: 'bg-indigo-50',
    softBorder: 'border-indigo-200',
    ring: 'ring-indigo-300',
  },
  {
    name: 'amber',
    solid: 'bg-amber-500',
    solidHover: 'hover:bg-amber-600',
    text: 'text-amber-600',
    soft: 'bg-amber-50',
    softBorder: 'border-amber-200',
    ring: 'ring-amber-300',
  },
  {
    name: 'grass',
    solid: 'bg-emerald-500',
    solidHover: 'hover:bg-emerald-600',
    text: 'text-emerald-600',
    soft: 'bg-emerald-50',
    softBorder: 'border-emerald-200',
    ring: 'ring-emerald-300',
  },
  {
    name: 'berry',
    solid: 'bg-pink-500',
    solidHover: 'hover:bg-pink-600',
    text: 'text-pink-600',
    soft: 'bg-pink-50',
    softBorder: 'border-pink-200',
    ring: 'ring-pink-300',
  },
  {
    name: 'sky',
    solid: 'bg-sky-500',
    solidHover: 'hover:bg-sky-600',
    text: 'text-sky-600',
    soft: 'bg-sky-50',
    softBorder: 'border-sky-200',
    ring: 'ring-sky-300',
  },
  {
    name: 'sunset',
    solid: 'bg-orange-500',
    solidHover: 'hover:bg-orange-600',
    text: 'text-orange-600',
    soft: 'bg-orange-50',
    softBorder: 'border-orange-200',
    ring: 'ring-orange-300',
  },
]

function hash(input: string): number {
  let h = 0
  for (let i = 0; i < input.length; i++) {
    h = (h << 5) - h + input.charCodeAt(i)
    h |= 0
  }
  return Math.abs(h)
}

export function accentFor(seed: string): Accent {
  return ACCENTS[hash(seed) % ACCENTS.length]
}
