import { Link } from 'react-router-dom'
import delevary from '../assets/delevary.jpg'
import shop from '../assets/shop.jpg'
import appg from '../assets/appg.jpg'
const cards = [
  {
    img: delevary, 
    bg: 'bg-[#ffe9a8]',
    title: 'Ride with Flash',
    text: 'Deliver food, set your own hours and get paid for every run. Sign up in minutes.',
    cta: 'Start earning',
    to:'/join/rider' ,
  },
  {
    img: shop,
    bg: 'bg-[#fbd5d7]',
    title: 'Partner with Flash',
    text: 'Reach new customers and grow your sales, with zero commission on your first 30 days.',
    cta: 'Add your restaurant',
    to:'/join/partner',
  },
  {
   img: appg, 
    bg: 'bg-[#e6e0f0]',
    title: 'Get the Flash app',
    text: 'Order in a few taps, track your rider live and reorder your favorites in seconds.',
    cta: 'Create an account',
    to: '/register',
  },
]
const Joinsec = () => {
  return (
    <section className="px-5 py-16 md:px-10">
      <h2 className="flash-display text-center text-4xl md:text-5xl">There is a place for you at Flash</h2>
      <div className="mx-auto mt-12 grid max-w-6xl gap-12 md:grid-cols-3 md:gap-8">
        {cards.map((c) => (
          <article key={c.title} className="group flex flex-col items-center text-center">
            <div
              className={`${c.bg} flex size-44 items-center justify-center text-8xl transition-transform group-hover:-rotate-3`}
              style={{ clipPath: 'polygon(0 0, 100% 0, 100% 80%, 84% 100%, 0 100%)' }}
            >
              <img src={c.img} alt="" className="size-30 " />
            </div>
            <h3 className="flash-display mt-6 text-4xl">{c.title}</h3>
            <p className="mt-3 max-w-xs text-[#1d1226]/75">{c.text}</p>
            <Link
              to={c.to}
              className="mt-4 font-bold text-[#e01e26] hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e01e26]"
            >
              {c.cta}{' '}
              <span aria-hidden="true" className="inline-block transition-transform group-hover:translate-x-1">
                →
              </span>
            </Link>
          </article>
        ))}
      </div>
    </section>
  )
}
export default Joinsec