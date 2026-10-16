import { Link } from 'react-router-dom'

const Bolt = ({ className = '' }) => (
  <svg viewBox="0 0 100 160" className={className} aria-hidden="true">
    <path d="M60 0 L8 92 H44 L28 160 L92 58 H56 Z" fill="currentColor" />
  </svg>
)

const stores = [
  { id: 'mcdo', name: 'Macdonald' },
  { id: 'kfc', name: 'KFC' },
  { id: 'pizzahut', name: 'Pizza Hut' },
  { id: 'otacos', name: 'O Tacos' },
  { id: 'marjan', name: 'Marjan' },
  { id: 'carrefour', name: 'Carrefour' },
  { id: 'pharmacie', name: 'Pharmacie' },
]

const link = 'hover:text-[#ffc61a] hover:underline'

export default function Footer() {
  const loggedIn = !!localStorage.getItem('token')

  return (
    <footer className="flash-root bg-[#1d1226] px-5 pb-6 pt-12 text-white md:px-10">
      <div className="grid gap-10 md:grid-cols-4">
        <div>
          <Link to="/" className="flex items-center gap-1">
            <Bolt className="h-7 w-7 text-[#ffc61a]" />
            <span className="flash-display text-3xl">Flash</span>
          </Link>
          <p className="mt-3 max-w-xs text-sm text-white/70">
            Fast food, groceries and pharmacy, delivered to your door by the closest rider.
          </p>
        </div>

        <div>
          <h2 className="font-bold text-[#ffc61a]">Stores</h2>
          <ul className="mt-3 space-y-2 text-sm text-white/80">
            {stores.map((s) => (
              <li key={s.id}>
                <Link to={`/store/${s.id}`} className={link}>{s.name}</Link>
              </li>
            ))}
          </ul>
        </div>

        <div>
          <h2 className="font-bold text-[#ffc61a]">Your account</h2>
          <ul className="mt-3 space-y-2 text-sm text-white/80">
            {loggedIn ? (
              <li><Link to="/mes-commandes" className={link}>My orders</Link></li>
            ) : (
              <>
                <li><Link to="/login" className={link}>Log in</Link></li>
                <li><Link to="/register" className={link}>Sign up</Link></li>
              </>
            )}
            <li><Link to="/checkout" className={link}>Cart</Link></li>
          </ul>
        </div>

        <div>
          <h2 className="font-bold text-[#ffc61a]">Contact</h2>
          {/* Exemples : remplacez par vos vraies coordonnées */}
          <ul className="mt-3 space-y-2 text-sm text-white/80">
            <li>contact@flash.com</li>
            <li>+212 6 00 00 00 00</li>
            <li>Every day, 9:00 to 00:00</li>
          </ul>
        </div>
      </div>

      <p className="mt-10 border-t border-white/15 pt-5 text-sm text-white/60">
        © {new Date().getFullYear()} Flash. Food at the speed of lightning.
      </p>
    </footer>
  )
}