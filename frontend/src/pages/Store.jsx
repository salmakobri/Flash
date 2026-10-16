import { useEffect, useState } from 'react'
import { Link, useNavigate, useParams } from 'react-router-dom'
import api, { errorMessage } from '../api'
import { useCart } from '../cart'
import Navbar from '../components/Navbar'
import { STORE_EMOJI, STORE_IMG, itemEmoji } from '../storeImages'
import Footer from '../components/Footer'

const STYLE = `
  @import url('https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@1,800;1,900&family=Barlow:wght@400;500;600&display=swap');
  .flash-root { font-family: 'Barlow', system-ui, sans-serif; }
  .flash-display { font-family: 'Barlow Condensed', Impact, sans-serif; font-style: italic; font-weight: 900; letter-spacing: -0.01em; line-height: .95; }
  .cut { clip-path: polygon(0 0, 100% 0, 100% 84%, 90% 100%, 0 100%); }
`

function Thumb({ src, emoji, alt }) {
  const [broken, setBroken] = useState(false)
  if (!src || broken) {
    return (
      <div className="cut flex h-20 w-20 shrink-0 items-center justify-center bg-[#ffe9a8] text-4xl">{emoji}</div>
    )
  }
  return <img src={src} alt={alt} onError={() => setBroken(true)} className="h-20 w-20 shrink-0 object-cover" />
}

function MenuView({ store }) {
  const navigate = useNavigate()
  const { cart, count, subtotal, add, change } = useCart()
  const [tab, setTab] = useState(0)

  const section = store.options[tab]
  const mine = cart.store?.id === store.id
  const qtyOf = (id) => (mine ? cart.items.find((i) => i.id === id)?.qty || 0 : 0)

  const addItem = (item) => {
    if (cart.store && !mine && (cart.items.length || cart.request)) {
      const ok = window.confirm(`Your cart has items from ${cart.store.name}. Start a new cart with ${store.name}?`)
      if (!ok) return
    }
    add(store, item)
  }

  if (!store.options.length) {
    return <p className="mt-8 text-[#1d1226]/70">The menu is coming soon.</p>
  }

  return (
    <>
      <div className="mt-6 flex gap-2 overflow-x-auto pb-2">
        {store.options.map((o, i) => (
          <button
            key={o.name}
            onClick={() => setTab(i)}
            className={`shrink-0 border-2 border-[#1d1226] px-4 py-2 text-sm font-bold ${
              i === tab ? 'bg-[#1d1226] text-white' : 'bg-white hover:bg-[#ffe9a8]'
            }`}
          >
            {o.name}
          </button>
        ))}
      </div>

      <ul className="mt-4 divide-y-2 divide-[#1d1226]/10 border-y-2 border-[#1d1226]/10">
        {section.items.map((item) => {
          const qty = qtyOf(item.id)
          return (
            <li key={item.id} className="flex items-center gap-4 py-4">
              <Thumb src={item.image} emoji={itemEmoji(item.name, section.emoji || STORE_EMOJI[store.id])} alt={item.name} />
              <div className="flex-1">
                <h3 className="font-bold">{item.name}</h3>
                {item.desc && <p className="text-sm text-[#1d1226]/70">{item.desc}</p>}
                <p className="mt-1 font-semibold">{item.price} MAD</p>
              </div>
              {qty > 0 ? (
                <div className="flex items-center gap-2 font-bold">
                  <button
                    onClick={() => change(item.id, -1)}
                    aria-label={`Remove one ${item.name}`}
                    className="size-8 border-2 border-[#1d1226] hover:bg-[#ffe9a8]"
                  >
                    −
                  </button>
                  <span className="w-5 text-center">{qty}</span>
                  <button
                    onClick={() => change(item.id, 1)}
                    aria-label={`Add one ${item.name}`}
                    className="size-8 border-2 border-[#1d1226] hover:bg-[#ffe9a8]"
                  >
                    +
                  </button>
                </div>
              ) : (
                <button onClick={() => addItem(item)} className="bg-[#e01e26] px-4 py-2 font-bold text-white hover:bg-[#1d1226]">
                  Add
                </button>
              )}
            </li>
          )
        })}
      </ul>

      {mine && count > 0 && (
        <div className="sticky bottom-0 mt-6 flex items-center justify-between gap-4 bg-[#1d1226] px-5 py-4 text-white">
          <span className="font-semibold">
            {count} item{count > 1 ? 's' : ''} · {subtotal} MAD
          </span>
          <button
            onClick={() => navigate('/checkout')}
            className="bg-[#ffc61a] px-5 py-2 font-bold text-[#1d1226] hover:bg-white"
          >
            Checkout
          </button>
        </div>
      )}
    </>
  )
}

function FreeOrderView({ store }) {
  const navigate = useNavigate()
  const { cart, setRequest } = useCart()
  const [text, setText] = useState(cart.store?.id === store.id ? cart.request : '')
  const [error, setError] = useState('')

  const next = () => {
    if (text.trim().length < 3) {
      setError('Write what you need, one item per line.')
      return
    }
    setRequest(store, text.trim())
    navigate('/checkout')
  }

  return (
    <div className="mt-6 max-w-xl">
      <label htmlFor="need" className="block font-semibold">What do you need?</label>
      <textarea
        id="need"
        rows={8}
        value={text}
        onChange={(e) => setText(e.target.value)}
        placeholder={'One item per line, for example:\nDoliprane 1000 mg x2\nSérum physiologique\nCrème solaire SPF 50'}
        className="mt-1 w-full border-2 border-[#1d1226] bg-white px-4 py-3 placeholder:text-[#1d1226]/40"
      />
      <p className="mt-2 text-sm text-[#1d1226]/70">
        The pharmacy confirms the prices when it prepares your order. For medicines that need a prescription,
        the rider will ask to see it at delivery.
      </p>
      {error && (
        <p role="alert" className="mt-4 border-l-4 border-[#e01e26] bg-[#e01e26]/10 px-4 py-3 text-sm font-semibold">
          {error}
        </p>
      )}
      <button onClick={next} className="mt-5 bg-[#e01e26] px-6 py-3 text-lg font-bold text-white hover:bg-[#1d1226]">
        Continue to checkout
      </button>
    </div>
  )
}

export default function Store() {
  const { storeId } = useParams()
  const [stores, setStores] = useState(null)
  const [error, setError] = useState('')

  useEffect(() => {
    let alive = true
    api
      .get('/catalog')
      .then(({ data }) => alive && setStores(data))
      .catch((e) => alive && setError(errorMessage(e, 'Could not load the menu.')))
    return () => {
      alive = false
    }
  }, [])

  const store = stores?.find((s) => s.id === storeId)

  return (
    <div className="flash-root flex min-h-screen flex-col bg-white text-[#1d1226]">
      <style>{STYLE}</style>
      <Navbar />

      <main className="mx-auto w-full max-w-3xl flex-1 px-5 py-8">
        <Link to="/" className="text-sm font-semibold text-[#e01e26] hover:underline">← All stores</Link>

        {error && (
          <p role="alert" className="mt-5 border-l-4 border-[#e01e26] bg-[#e01e26]/10 px-4 py-3 text-sm font-semibold">
            {error}
          </p>
        )}
        {!stores && !error && <p className="mt-8">Loading...</p>}
        {stores && !store && <p className="mt-8">This store does not exist.</p>}

        {store && (
          <>
            <div className="mt-4 flex items-center gap-4">
              {STORE_IMG[store.id] && <img src={STORE_IMG[store.id]} alt="" className="h-16 w-16 object-contain" />}
              <h1 className="flash-display text-5xl md:text-6xl">{store.name}</h1>
            </div>
            {store.type === 'free_order' ? (
              <FreeOrderView key={store.id} store={store} />
            ) : (
              <MenuView key={store.id} store={store} />
            )}
          </>
        )}
      </main>
      <Footer />
    </div>
  )
}