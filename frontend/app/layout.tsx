import type { Metadata } from "next";
import { Geist, Geist_Mono, Baloo_2 } from "next/font/google";
import "./globals.css";
import { SiteHeader } from "@/components/site-header";
import { SiteFooter } from "@/components/site-footer";
import { getLotteries, getPages, getPosts } from "@/lib/api";
import { guidePages } from "@/lib/content";

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

const baloo = Baloo_2({
  variable: "--font-baloo",
  subsets: ["latin"],
  weight: ["600", "700", "800"],
});

export const metadata: Metadata = {
  title: {
    default: "World Lottery — Big Jackpots, Latest Results & Lottery News",
    template: "%s | World Lottery",
  },
  description:
    "Big jackpots, latest results, no nonsense. Track jackpots, winning numbers and draw dates for lotteries around the world.",
};

export default async function RootLayout({ children }: LayoutProps<"/">) {
  const [lotteries, pages, posts] = await Promise.all([getLotteries(), getPages(), getPosts()]);
  const guides = guidePages(pages);

  return (
    <html
      lang="en"
      className={`${geistSans.variable} ${geistMono.variable} ${baloo.variable} h-full antialiased`}
    >
      <body className="min-h-full flex flex-col bg-background text-foreground">
        <SiteHeader />
        <main className="flex-1">{children}</main>
        <SiteFooter lotteries={lotteries} pages={guides} posts={posts} />
      </body>
    </html>
  );
}
