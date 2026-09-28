'use client'

import { useState } from 'react'
import type { ReactNode } from 'react'
import Link from 'next/link'
import { Menu, X } from 'lucide-react'

export function MobileNav({ links }: { links: { href: string; label: string; icon: ReactNode }[] }) {
  const [open, setOpen] = useState(false)

  return (
    <div className="sm:hidden">
      <button
        type="button"
        onClick={() => setOpen((v) => !v)}
        aria-expanded={open}
        aria-label="Toggle menu"
        className="flex h-10 w-10 items-center justify-center rounded-full text-muted transition hover:bg-surface hover:text-foreground"
      >
        {open ? <X size={20} /> : <Menu size={20} />}
      </button>

      {open && (
        <div className="absolute inset-x-0 top-full border-b border-black/5 bg-background px-4 pb-4 shadow-sm">
          <nav className="flex flex-col gap-1">
            {links.map(({ href, label, icon }) => (
              <Link
                key={href}
                href={href}
                onClick={() => setOpen(false)}
                className="flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-semibold text-muted transition hover:bg-surface hover:text-foreground"
              >
                {icon}
                {label}
              </Link>
            ))}
          </nav>
        </div>
      )}
    </div>
  )
}
