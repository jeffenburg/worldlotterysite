import Link from 'next/link'
import { Ticket } from 'lucide-react'

export default function NotFound() {
  return (
    <div className="mx-auto flex max-w-6xl flex-col items-center gap-4 px-4 py-20 text-center sm:px-6">
      <span className="flex h-14 w-14 items-center justify-center rounded-full bg-rose-500 text-white">
        <Ticket size={24} />
      </span>
      <h1 className="font-display text-4xl font-extrabold">That draw didn&apos;t come up</h1>
      <p className="max-w-md text-muted">We couldn&apos;t find the page you were looking for. It may have moved, or never existed.</p>
      <Link href="/" className="mt-2 rounded-full bg-rose-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-rose-600">
        Back to homepage
      </Link>
    </div>
  )
}
