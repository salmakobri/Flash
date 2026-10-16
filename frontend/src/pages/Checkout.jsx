import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import api, { errorMessage } from '../api'
import { FREE_ORDER_FEE, deliveryFee, useCart } from '../cart'
import Navbar from '../components/Navbar'
import Footer from '../components/Footer'

const STYLE = `
  @import url('https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@1,800;1,900&family=Barlow:wght@400;500;600&display=swap');
  .flash-root { font-family: 'Barlow', system-ui, sans-serif; }
  .flash-display { font-family: 'Barlow Condensed', Impact, sans-serif; font-style: italic; font-weight: 900; letter-spacing: -0.01em; line-height: .95; }
`

const input =
  'w-full border-2 border-[#1d1226] bg-white px-4 py-3 placeholder:text-[#1d1226]/40 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e01e26]'

export default function Checkout() {
  const navigate = useNavigate()
  const { cart, count, subtotal, hasMeal, hasCart, change, setRequest, clear } = useCart()
  const [form, setForm] = useState({ address: '', phone: '', notes: '' })
  const [error, setError] = useState('')
  const [sending, setSending] = useState(false)

  const update = (field) => (e) => setForm({ ...form, [field]: e.target.value })
  const loggedIn = !!localStorage.getItem('token')
  const freeOrder = cart.items.length === 0 && cart.request.trim().length > 0
  const fee = freeOrder ? FREE_ORDER_FEE : deliveryFee(subtotal, hasMeal)

  const handleSubmit = async (e) => {
    e.preventDefault()
    if (sending) return
    if (!loggedIn) {
      navigate('/login', { state: { from: '/checkout' } }) // le panier est conservé
      return
    }
    if (!form.address.trim() || !form.phone.trim()) {
      setError('Add your delivery address and phone number so the rider can find you.')
      return
    }
    setError('')
    setSending(true)
    try {
      await api.post('/orders', {
        store: cart.store.id,
        items: cart.items.map(({ id, qty }) => ({ id, qty })),
        request_text: freeOrder ? cart.request : null,
        adresse_livraison: form.address.trim(),
        telephone: form.phone.trim(),
        notes: form.notes.trim() || null,
      })
      clear()
      navigate('/mes-commandes', { state: { placed: true } })
    } catch (err) {
      if (err.response?.status === 401 || err.response?.status === 403) {
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        navigate('/login', { state: { from: '/checkout' } })
        return
      }
      setError(errorMessage(err, 'Could not place your order. Try again.'))
    } finally {
      setSending(false)
    }
  }

  return (
    <div className="flash-root flex min-h-screen flex-col bg-white text-[#1d1226]">
      <style>{STYLE}</style>
      <Navbar />

      <main className="mx-auto w-full max-w-5xl flex-1 px-5 py-8">
        <h1 className="flash-display text-6xl">Checkout</h1>

        {!hasCart ? (
          <div className="mt-8">
            <p className="text-[#1d1226]/70">Your cart is empty.</p>
            <Link to="/" className="mt-4 inline-block bg-[#ffc61a] px-6 py-3 font-bold hover:bg-[#1d1226] hover:text-white">
              Browse the stores
            </Link>
          </div>
        ) : (
          <form onSubmit={handleSubmit} noValidate className="mt-8 grid gap-8 lg:grid-cols-[1fr_22rem]">
            <div>
              {!loggedIn && (
                <p className="mb-6 border-l-4 border-[#ffc61a] bg-[#ffe9a8] px-4 py-3 text-sm font-semibold">
                  You need an account to place an order. Your cart will be waiting for you after you log in.
                </p>
              )}
              {error && (
                <p role="alert" className="mb-6 border-l-4 border-[#e01e26] bg-[#e01e26]/10 px-4 py-3 text-sm font-semibold">
                  {error}
                </p>
              )}

              <h2 className="flash-display text-4xl">Where should we bring it?</h2>
              <label htmlFor="address" className="mt-5 block font-semibold">Delivery address</label>
              <input id="address" type="text" autoComplete="street-address" value={form.address}
                onChange={update('address')} placeholder="Street, building, apartment" className={`${input} mt-1`} />

              <label htmlFor="phone" className="mt-5 block font-semibold">Phone number</label>
              <input id="phone" type="tel" autoComplete="tel" value={form.phone}
                onChange={update('phone')} placeholder="So the rider can reach you" className={`${input} mt-1`} />

              <label htmlFor="notes" className="mt-5 block font-semibold">Note for the rider (optional)</label>
              <textarea id="notes" rows={3} value={form.notes} onChange={update('notes')}
                placeholder="Ring the bell twice, leave at the door..." className={`${input} mt-1`} />

              <h2 className="flash-display mt-10 text-4xl">Payment</h2>
              <p className="mt-3 border-2 border-[#1d1226] bg-[#ffe9a8] p-4 font-semibold">
                Cash on delivery. Pay the rider at your door.
              </p>
            </div>

            <aside className="h-fit border-2 border-[#1d1226] bg-white p-5 lg:sticky lg:top-20">
              <h2 className="flash-display text-3xl">Your order</h2>
              <p className="mt-1 text-sm text-[#1d1226]/70">{cart.store?.name}</p>

              {freeOrder ? (
                <>
                  <textarea rows={6} value={cart.request}
                    onChange={(e) => setRequest(cart.store, e.target.value)}
                    aria-label="Your request" className={`${input} mt-4 text-sm`} />
                  <p className="mt-2 text-sm text-[#1d1226]/70">Prices are confirmed by the pharmacy.</p>
                </>
              ) : (
                <ul className="mt-4 space-y-3 text-sm">
                  {cart.items.map((i) => (
                    <li key={i.id} className="flex items-center justify-between gap-2">
                      <span className="flex-1">{i.name}</span>
                      <span className="flex items-center gap-1 font-bold">
                        <button type="button" onClick={() => change(i.id, -1)} aria-label={`Remove one ${i.name}`}
                          className="size-6 border-2 border-[#1d1226] hover:bg-[#ffe9a8]">−</button>
                        {i.qty}
                        <button type="button" onClick={() => change(i.id, 1)} aria-label={`Add one ${i.name}`}
                          className="size-6 border-2 border-[#1d1226] hover:bg-[#ffe9a8]">+</button>
                      </span>
                      <span className="w-16 text-right font-semibold">{i.price * i.qty} MAD</span>
                    </li>
                  ))}
                </ul>
              )}

              <dl className="mt-4 space-y-1 border-t-2 border-[#1d1226]/10 pt-3 text-sm">
                {!freeOrder && (
                  <div className="flex justify-between"><dt>Subtotal ({count})</dt><dd>{subtotal} MAD</dd></div>
                )}
                <div className="flex justify-between"><dt>Delivery</dt><dd>{fee} MAD</dd></div>
              </dl>
              <div className="mt-3 flex justify-between border-t-2 border-[#1d1226] pt-3 text-lg font-bold">
                <span>Total</span>
                <span>{freeOrder ? `${fee} MAD + products` : `${subtotal + fee} MAD`}</span>
              </div>

              <button type="submit" disabled={sending}
                className="mt-5 w-full bg-[#e01e26] py-3 text-lg font-bold text-white hover:bg-[#1d1226] disabled:cursor-wait disabled:opacity-60">
                {sending ? 'Sending...' : loggedIn ? 'Place order' : 'Log in to order'}
              </button>
            </aside>
          </form>
        )}
      </main>
      <Footer />
    </div>
  )
}