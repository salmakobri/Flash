import { useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import api, { errorMessage } from '../api'
import Footer from '../components/Footer'
import Navbar from '../components/Navbar'

const STYLE = `
  @import url('https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@1,800;1,900&family=Barlow:wght@400;500;600&display=swap');
  .flash-root { font-family: 'Barlow', system-ui, sans-serif; }
  .flash-display { font-family: 'Barlow Condensed', Impact, sans-serif; font-style: italic; font-weight: 900; letter-spacing: -0.01em; line-height: .95; }
`

const CONFIG = {
  rider: {
    title: 'Ride with Flash',
    intro: 'Deliver food, set your own hours and get paid for every run. Send your application: the admin reviews it and approves or declines it.',
    success:
      'Your application is with the admin. Once it is approved, log in to the Flash driver app with the email and password you just chose.',
  },
  partner: {
    title: 'Partner with Flash',
    intro: 'Reach new customers and grow your sales. Tell us about your store: the admin reviews your request and contacts you.',
    success: 'Your request is with the admin. We will contact you by phone or email once it has been reviewed.',
  },
}

const input =
  'w-full border-2 border-[#1d1226] bg-white px-4 py-3 placeholder:text-[#1d1226]/40 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e01e26]'

function Field({ id, label, children }) {
  return (
    <div className="mt-5">
      <label htmlFor={id} className="block font-semibold">{label}</label>
      <div className="mt-1">{children}</div>
    </div>
  )
}

export default function Join() {
  const { type } = useParams()
  const conf = CONFIG[type]

  const [form, setForm] = useState({
    nom: '', email: '', telephone: '', ville: '',
    vehicule: 'moto', password: '', confirm: '',
    magasin: '', categorie: 'restaurant', message: '',
  })
  const [error, setError] = useState('')
  const [sending, setSending] = useState(false)
  const [done, setDone] = useState(false)

  const update = (field) => (e) => setForm({ ...form, [field]: e.target.value })

  const handleSubmit = async (e) => {
    e.preventDefault()
    if (sending) return

    const required = type === 'rider'
      ? ['nom', 'email', 'telephone', 'ville', 'password']
      : ['nom', 'email', 'telephone', 'ville', 'magasin']
    if (required.some((f) => !form[f].trim())) {
      setError('Please fill in all the required fields.')
      return
    }
    if (type === 'rider') {
      if (form.password.length < 8) {
        setError('Your password needs at least 8 characters.')
        return
      }
      if (form.password !== form.confirm) {
        setError('The passwords do not match. Type the same password twice.')
        return
      }
    }

    const base = {
      type,
      nom: form.nom.trim(),
      email: form.email.trim(),
      telephone: form.telephone.trim(),
      ville: form.ville.trim(),
      message: form.message.trim() || null,
    }
    const payload = type === 'rider'
      ? { ...base, vehicule: form.vehicule, password: form.password, password_confirmation: form.confirm }
      : { ...base, magasin: form.magasin.trim(), categorie: form.categorie }

    setError('')
    setSending(true)
    try {
      await api.post('/applications', payload)
      setDone(true)
    } catch (err) {
      setError(errorMessage(err, 'Could not send your request. Try again.'))
    } finally {
      setSending(false)
    }
  }

  return (
    <div className="flash-root flex min-h-screen flex-col bg-white text-[#1d1226]">
      <style>{STYLE}</style>
      <Navbar />

      <main className="mx-auto w-full max-w-xl flex-1 px-5 py-10">
        {!conf ? (
          <p>This page does not exist. <Link to="/" className="font-bold underline">Back to home</Link></p>
        ) : done ? (
          <div>
            <h1 className="flash-display text-5xl">Request sent</h1>
            <p className="mt-4 border-l-4 border-green-600 bg-green-50 px-4 py-3 font-semibold">{conf.success}</p>
            <Link to="/" className="mt-6 inline-block bg-[#ffc61a] px-6 py-3 font-bold hover:bg-[#1d1226] hover:text-white">
              Back to home
            </Link>
          </div>
        ) : (
          <form onSubmit={handleSubmit} noValidate>
            <h1 className="flash-display text-5xl">{conf.title}</h1>
            <p className="mt-3 text-[#1d1226]/75">{conf.intro}</p>

            {error && (
              <p role="alert" className="mt-5 border-l-4 border-[#e01e26] bg-[#e01e26]/10 px-4 py-3 text-sm font-semibold">
                {error}
              </p>
            )}

            <Field id="nom" label="Full name">
              <input id="nom" type="text" value={form.nom} onChange={update('nom')} className={input} />
            </Field>
            <Field id="email" label="Email">
              <input id="email" type="email" value={form.email} onChange={update('email')} className={input} />
            </Field>
            <Field id="telephone" label="Phone number">
              <input id="telephone" type="tel" value={form.telephone} onChange={update('telephone')} className={input} />
            </Field>
            <Field id="ville" label="City">
              <input id="ville" type="text" value={form.ville} onChange={update('ville')} className={input} />
            </Field>

            {type === 'rider' ? (
              <>
                <Field id="vehicule" label="Your vehicle">
                  <select id="vehicule" value={form.vehicule} onChange={update('vehicule')} className={input}>
                    <option value="moto">Motorbike</option>
                    <option value="scooter">Scooter</option>
                    <option value="velo">Bicycle</option>
                    <option value="voiture">Car</option>
                  </select>
                </Field>
                <Field id="password" label="Choose a password (for the driver app)">
                  <input id="password" type="password" autoComplete="new-password" value={form.password}
                    onChange={update('password')} placeholder="At least 8 characters" className={input} />
                </Field>
                <Field id="confirm" label="Confirm password">
                  <input id="confirm" type="password" autoComplete="new-password" value={form.confirm}
                    onChange={update('confirm')} className={input} />
                </Field>
              </>
            ) : (
              <>
                <Field id="magasin" label="Store name">
                  <input id="magasin" type="text" value={form.magasin} onChange={update('magasin')} className={input} />
                </Field>
                <Field id="categorie" label="Store type">
                  <select id="categorie" value={form.categorie} onChange={update('categorie')} className={input}>
                    <option value="restaurant">Restaurant</option>
                    <option value="supermarche">Supermarket</option>
                    <option value="pharmacie">Pharmacy</option>
                    <option value="autre">Other</option>
                  </select>
                </Field>
              </>
            )}

            <Field id="message" label="Message (optional)">
              <textarea id="message" rows={3} value={form.message} onChange={update('message')} className={input} />
            </Field>

            <button type="submit" disabled={sending}
              className="mt-7 w-full bg-[#e01e26] py-3 text-lg font-bold text-white hover:bg-[#1d1226] disabled:cursor-wait disabled:opacity-60">
              {sending ? 'Sending...' : type === 'rider' ? 'Send my application' : 'Send my request'}
            </button>
          </form>
        )}
      </main>

      <Footer />
    </div>
  )
}