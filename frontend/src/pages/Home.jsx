import { Link } from 'react-router-dom'
import Footer from '../components/Footer'
import Joinsec from '../components/joinsec' // must match your file name exactly
import Navbar from '../components/Navbar'
import { STORE_IMG } from '../storeImages'

const categories = [
  { id: 'mcdo', name: 'Macdonald' },
  { id: 'kfc', name: 'KFC' },
  { id: 'pizzahut', name: 'Pizza Hut' },
  { id: 'otacos', name: 'O Tacos' },
  { id: 'marjan', name: 'Marjan' },
  { id: 'carrefour', name: 'Carrefour' },
  { id: 'pharmacie', name: 'Pharmacie' },
]

const speedLines = [
  { top: '14%', w: '38%', delay: '.05s' },
  { top: '30%', w: '55%', delay: '.15s' },
  { top: '52%', w: '30%', delay: '.1s' },
  { top: '70%', w: '48%', delay: '.22s' },
  { top: '86%', w: '26%', delay: '.3s' },
]

const Bolt = ({ className = '' }) => (
  <svg viewBox="0 0 100 160" className={className} aria-hidden="true">
    <path d="M60 0 L8 92 H44 L28 160 L92 58 H56 Z" fill="currentColor" />
  </svg>
)

const tile =
  'flex h-38.5 w-41.5 flex-col items-center justify-center gap-3 border-[3px] border-[#1b1424] bg-white text-xl font-bold text-[#1b1424] no-underline hover:bg-[#ffe9a8] focus-visible:outline-2 focus-visible:outline-[#e01e26]'

const Home = () => {
  const goToStores = (e) => {
    e.preventDefault()
    document.getElementById('stores')?.scrollIntoView({ behavior: 'smooth' })
  }

  return (
    <div className="flash-root min-h-screen bg-white text-[#1d1226]">
      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@1,800;1,900&family=Barlow:wght@400;500;600&display=swap');
        .flash-root { font-family: 'Barlow', system-ui, sans-serif; }
        .flash-display { font-family: 'Barlow Condensed', Impact, sans-serif; font-style: italic; font-weight: 900; letter-spacing: -0.01em; line-height: .95; }
        .streak { animation: streak .7s cubic-bezier(.2,.9,.2,1) both; }
        .slam { animation: slam .6s cubic-bezier(.2,1.4,.4,1) .35s both; }
        .cut { clip-path: polygon(0 0, 100% 0, 100% 84%, 90% 100%, 0 100%); }
        @keyframes streak { from { transform: translateX(-120%) skewX(-25deg); opacity: 0; } to { transform: skewX(-25deg); opacity: 1; } }
        @keyframes slam { from { transform: translateY(-40px) scale(1.3) rotate(8deg); opacity: 0; } to { transform: none; opacity: 1; } }
        @media (prefers-reduced-motion: reduce) { .streak, .slam { animation: none; } }
      `}</style>

      <Navbar />

      {/* Hero */}
      <header className="cut relative overflow-hidden bg-[#e01e26] px-5 pb-28 pt-14 text-white md:px-10 md:pb-36 md:pt-20">
        {speedLines.map((l, i) => (
          <div
            key={i}
            className="streak absolute right-0 h-0.75 bg-white/25"
            style={{ top: l.top, width: l.w, animationDelay: l.delay }}
          />
        ))}
        <Bolt className="slam absolute -right-4 top-6 h-64 w-40 text-[#ffc61a] md:right-24 md:top-10 md:h-104 md:w-64" />

        <div className="relative max-w-xl">
          <h1 className="flash-display text-6xl md:text-8xl">Hungry now? Fed in 15.</h1>
          <p className="mt-5 max-w-md text-lg text-white/90">
            Your favorite fast food, groceries and pharmacy, brought straight to your door.
          </p>
          <a
            href="#stores"
            onClick={goToStores}
            className="mt-8 inline-block bg-[#1d1226] px-6 py-3 font-bold text-white hover:bg-[#ffc61a] hover:text-[#1d1226] focus-visible:outline-2 focus-visible:outline-white"
          >
            Choose a store
          </a>
        </div>
      </header>

      {/* Stores: the same square tiles, centered */}
      <section id="stores" className="px-5 py-16 md:px-10">
        <ul className="mx-auto flex max-w-3xl flex-wrap justify-center gap-6">
          {categories.map((c) => (
            <li key={c.id}>
              <Link to={`/store/${c.id}`} className={tile}>
                <img src={STORE_IMG[c.id]} alt="" className="h-18 w-18 object-contain" />
                <span>{c.name}</span>
              </Link>
            </li>
          ))}
        </ul>
      </section>

      <Joinsec />

      <Footer />
    </div>
  )
}

export default Home