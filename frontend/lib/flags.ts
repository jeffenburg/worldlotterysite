// ISO 3166-1 alpha-2 codes (lowercase, for the `flag-icons` package) for countries
// present in our lottery data, with a "world" fallback for anything unmapped.
const COUNTRY_CODES: Record<string, string> = {
  'United States': 'us',
  'United Kingdom': 'gb',
  Australia: 'au',
  Italy: 'it',
  Spain: 'es',
  'South Africa': 'za',
  France: 'fr',
  Canada: 'ca',
  Germany: 'de',
  Ireland: 'ie',
  Austria: 'at',
  Portugal: 'pt',
  Netherlands: 'nl',
  Belgium: 'be',
  Switzerland: 'ch',
  Europe: 'eu',
}

export function countryCode(country: string | null | undefined): string {
  if (!country) return 'xx'
  return COUNTRY_CODES[country] ?? 'xx'
}

