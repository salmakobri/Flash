import { Link, useNavigate } from 'react-router-dom'
import api from '../api'
import { useCart } from '../cart'

const Bolt = ({ className = '' }) => (
  <svg viewBox="0 0 100 160" className={className} aria-hidden="true">
    <path d="M60 0 L8 92 H44 L28 160 L92 58 H56 Z" fill="currentColor" />
  </svg>
)

export default function Navbar() {
  const navigate = useNavigate()
  const { count, hasCart } = useCart()
  const token = localStorage.getItem('token')
  const name = localStorage.getItem('user')

  const logout = async () => {
    try {
      await api.post('/logout')
    } catch {
      /* on supprime le token local dans tous les cas */
    }
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    navigate('/login')
  }

  return (
    <nav className="flash-root sticky top-0 z-20 flex items-center justify-between bg-[#e01e26] px-5 py-3 text-white md:px-10">
      <Link to="/" className="flex items-center gap-1 focus-visible:outline-2 focus-visible:outline-[#ffc61a]">
        <Bolt className="h-7 w-7 text-[#ffc61a]" />
        <span className="flash-display text-3xl">Flash</span>
      </Link>

      <div className="flex items-center gap-4 text-sm font-semibold">
        <Link to="/checkout" className="hover:underline">
          Cart
          {hasCart && (
            <span className="ml-1 bg-[#ffc61a] px-2 py-0.5 text-xs font-bold text-[#1d1226]">{count || 1}</span>
          )}
        </Link>

        {token ? (
          <>
            <Link to="/mes-commandes" className="hover:underline">My orders</Link>
            <button onClick={logout} className="hover:underline">
              Log out{name ? ` (${name.split(' ')[0]})` : ''}
            </button>
          </>
        ) : (
          <>
            <Link to="/login" className="hover:underline">Log in</Link>
            <Link to="/register" className="bg-[#ffc61a] px-4 py-2 text-[#1d1226] hover:bg-white">
              Sign up
            </Link>
          </>
        )}
      </div>
    </nav>
  )
}