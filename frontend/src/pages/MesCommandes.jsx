import { useCallback, useEffect, useState } from 'react'
import { Link, useLocation, useNavigate } from 'react-router-dom'
import api, { errorMessage } from '../api'
import Navbar from '../components/Navbar'
import Footer from '../components/Footer'

const STATUTS = {
  en_attente: { label: 'Waiting for a rider', cls: 'bg-gray-200 text-gray-800' },
  affectee: { label: 'Rider assigned', cls: 'bg-blue-100 text-blue-800' },
  en_cours: { label: 'On the way', cls: 'bg-orange-100 text-orange-800' },
  livree: { label: 'Delivered', cls: 'bg-green-100 text-green-800' },
}

export default function MesCommandes() {
  const navigate = useNavigate()
  const { state } = useLocation()
  const [commandes, setCommandes] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  const load = useCallback(async () => {
    if (!localStorage.getItem('token')) {
      navigate('/login')
      return
    }
    try {
      const { data } = await api.get('/client/commandes')
      setCommandes(data.data)
      setError('')
    } catch (err) {
      if (err.response?.status === 401 || err.response?.status === 403) {
        localStorage.removeItem('token')
        navigate('/login')
        return
      }
      setError(errorMessage(err, 'Could not load your orders.'))
    } finally {
      setLoading(false)
    }
  }, [navigate])

  useEffect(() => {
    load()
    const t = setInterval(load, 10000) // le statut se met à jour tout seul
    return () => clearInterval(t)
  }, [load])

  
  return (
    <div className="flex min-h-screen flex-col bg-white text-[#1d1226]">
      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@1,900&family=Barlow:wght@400;500;600&display=swap');
        .flash-root { font-family: 'Barlow', system-ui, sans-serif; }
        .flash-display { font-family: 'Barlow Condensed', Impact, sans-serif; font-style: italic; font-weight: 900; line-height: .95; }
      `}</style>

      <Navbar />

      <main className="flash-root mx-auto w-full max-w-3xl flex-1 px-5 py-10">
        <h1 className="flash-display text-5xl">My orders</h1>

        {state?.placed && (
          <p className="mt-5 border-l-4 border-green-600 bg-green-50 px-4 py-3 text-sm font-semibold">
            Order placed. We will assign a rider shortly.
          </p>
        )}
        {error && (
          <p role="alert" className="mt-5 border-l-4 border-[#e01e26] bg-[#e01e26]/10 px-4 py-3 text-sm font-semibold">
            {error}
          </p>
        )}

        {loading ? (
          <p className="mt-8">Loading...</p>
        ) : commandes.length === 0 ? (
          <p className="mt-8 text-[#1d1226]/70">You have no orders yet.</p>
        ) : (
          <ul className="mt-8 space-y-4">
            {commandes.map((c) => {
              const s = STATUTS[c.statut] ?? { label: c.statut, cls: 'bg-gray-100' }
              return (
                <li key={c.id} className="border-2 border-[#1d1226] p-5">
                  <div className="flex items-center justify-between gap-3">
                    <span className="font-bold">Order #{c.id}</span>
                    <span className={`px-3 py-1 text-xs font-bold ${s.cls}`}>{s.label}</span>
                  </div>
                  <p className="mt-2 text-sm text-[#1d1226]/70">{c.date} · {c.adresse}</p>
                  {c.details && <p className="mt-2 whitespace-pre-line text-sm">{c.details}</p>}
                  {c.chauffeur && <p className="mt-2 text-sm">Rider: <b>{c.chauffeur}</b></p>}
                </li>
              )
            })}
          </ul>
        )}
      </main>
      <Footer />
    </div>
  )
}