
import { useState } from 'react'
import { Link, useLocation, useNavigate } from 'react-router-dom'
import api, { errorMessage } from '../api'

const Bolt = ({ className = '' }) => (
  <svg viewBox="0 0 100 160" className={className} aria-hidden="true">
    <path d="M60 0 L8 92 H44 L28 160 L92 58 H56 Z" fill="currentColor" />
  </svg>
)

const Login = () => {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [show, setShow] = useState(false)
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const navigate = useNavigate()
  const location = useLocation()

    const handleSubmit = async (e) => {
    e.preventDefault()
    if (!email.trim() || !password) {
      setError('Enter your email and password to log in.')
      return
    }
    setError('')
    setLoading(true)
    try {
      const { data } = await api.post('/login', { email: email.trim(), password })
      if (data.user.role !== 'client') {
        setError('This account is not a customer account.')
        return
      }
      localStorage.setItem('token', data.token)
      localStorage.setItem('user', data.user.name)
      navigate(location.state?.from || '/mes-commandes')
    } catch (err) {
      setError(errorMessage(err, 'Wrong email or password.'))
    } finally {
      setLoading(false)
    }
  }

  const input =
    'w-full border-2 border-[#1d1226] bg-white px-4 py-3 placeholder:text-[#1d1226]/40 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e01e26]'

  return (
    <div className="flash-root grid min-h-screen bg-white text-[#1d1226] md:grid-cols-2">
      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@1,800;1,900&family=Barlow:wght@400;500;600&display=swap');
        .flash-root { font-family: 'Barlow', system-ui, sans-serif; }
        .flash-display { font-family: 'Barlow Condensed', Impact, sans-serif; font-style: italic; font-weight: 900; letter-spacing: -0.01em; line-height: .95; }
        .slam { animation: slam .6s cubic-bezier(.2,1.4,.4,1) .2s both; }
        .slant { clip-path: polygon(0 0, 100% 0, 100% 88%, 0 100%); }
        @media (min-width: 768px) { .slant { clip-path: polygon(0 0, 100% 0, 88% 100%, 0 100%); } }
        @keyframes slam { from { transform: translateY(-40px) scale(1.3) rotate(8deg); opacity: 0; } to { transform: none; opacity: 1; } }
        @media (prefers-reduced-motion: reduce) { .slam { animation: none; } }
      `}</style>

      {/* Brand panel */}
      <section className="slant relative flex flex-col justify-between overflow-hidden bg-[#e01e26] px-6 pb-20 pt-6 text-white md:px-12 md:pb-12 md:pt-8">
        <Link to="/" className="flex w-fit items-center gap-1 focus-visible:outline-2 focus-visible:outline-[#ffc61a]">
          <Bolt className="h-7 w-7 text-[#ffc61a]" />
          <span className="flash-display text-3xl">Flash</span>
        </Link>
        <Bolt className="slam absolute -right-2 top-16 h-40 w-24 text-[#ffc61a] md:right-20 md:top-1/3 md:h-72 md:w-44" />
        <div className="relative mt-16 max-w-sm md:mt-0">
          <h1 className="flash-display text-6xl md:text-7xl">Welcome back. Your food is waiting.</h1>
          <p className="mt-4 text-white/90">Log in to reorder your favorites and track your rider live.</p>
        </div>
      </section>

      {/* Form panel */}
      <section className="flex items-center justify-center px-6 py-12 md:px-12">
        <form onSubmit={handleSubmit} className="w-full max-w-sm" noValidate>
          <h2 className="flash-display text-5xl">Log in</h2>

          {error && (
            <p role="alert" className="mt-5 border-l-4 border-[#e01e26] bg-[#e01e26]/10 px-4 py-3 text-sm font-semibold">
              {error}
            </p>
          )}

          <label htmlFor="email" className="mt-6 block font-semibold">Email</label>
          <input
            id="email"
            type="email"
            autoComplete="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            placeholder="you@example.com"
            className={`${input} mt-1`}
          />

          <label htmlFor="password" className="mt-5 block font-semibold">Password</label>
          <div className="relative mt-1">
            <input
              id="password"
              type={show ? 'text' : 'password'}
              autoComplete="current-password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="Your password"
              className={`${input} pr-16`}
            />
            <button
              type="button"
              onClick={() => setShow((s) => !s)}
              aria-label={show ? 'Hide password' : 'Show password'}
              className="absolute right-2 top-1/2 -translate-y-1/2 px-2 py-1 text-sm font-bold hover:text-[#e01e26] focus-visible:outline-2 focus-visible:outline-[#e01e26]"
            >
              {show ? 'Hide' : 'Show'}
            </button>
          </div>

          <button
            type="submit"
            disabled={loading}
            className="mt-7 w-full bg-[#e01e26] py-3 text-lg font-bold text-white hover:bg-[#1d1226] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1d1226] disabled:cursor-wait disabled:opacity-60"
          >
            {loading ? 'Logging in...' : 'Log in'}
          </button>

          <p className="mt-6 text-center text-sm">
            New to Flash?{' '}
            <Link to="/register" state={location.state} className="font-bold underline hover:text-[#e01e26]">
              Create an account
            </Link>
          </p>
        </form>
      </section>
    </div>
  )
}

export default Login