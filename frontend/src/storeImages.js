import macdonald from './assets/macdonald.jpg'
import kfc from './assets/kfc.jpg'
import pizzahut from './assets/pizzahut.jpg'
import otacos from './assets/otacos.jpg'
import marjan from './assets/marjan.jpg'
import carrefour from './assets/carrefour.jpg'
import pharmacie from './assets/pharmacie.jpg'

export const STORE_IMG = { mcdo: macdonald, kfc, pizzahut, otacos, marjan, carrefour, pharmacie }

export const STORE_EMOJI = {
  mcdo: '🍔',
  kfc: '🍗',
  pizzahut: '🍕',
  otacos: '🌮',
  marjan: '🛒',
  carrefour: '🛒',
  pharmacie: '💊',
}

// Image de secours par article, tant qu'il n'y a pas de vraie photo.
// L'ordre compte : la première règle qui correspond gagne.
const RULES = [
  [/pommes? de terre/, '🥔'],
  [/eau de javel|liquide vaisselle|shampoing/, '🧴'],
  [/papier toilette/, '🧻'],
  [/dentifrice/, '🪥'],
  [/savon/, '🧼'],
  [/lessive/, '🧺'],
  [/huile d.?olive/, '🫒'],
  [/huile/, '🫗'],
  [/pates|spaghetti/, '🍝'],
  [/pizza/, '🍕'],
  [/tacos/, '🌮'],
  [/twister|wrap/, '🌯'],
  [/burger|big mac|mcchicken/, '🍔'],
  [/mcflurry|sundae|glace/, '🍦'],
  [/brownie/, '🍫'],
  [/cookie/, '🍪'],
  [/pain a l.?ail/, '🥖'],
  [/petit dejeuner/, '🥐'],
  [/frites/, '🍟'],
  [/coleslaw|salade/, '🥗'],
  [/sauce/, '🥫'],
  [/nuggets|tenders|strips|wings|ailes|poulet|bucket|zinger|box|combo|menu|royal|star/, '🍗'],
  [/cappuccino|cafe|latte/, '☕'],
  [/\bthe\b/, '🍵'],
  [/eau minerale|^eau/, '💧'],
  [/jus/, '🧃'],
  [/coca|sprite|pepsi|canette|fanta/, '🥤'],
  [/tomate/, '🍅'],
  [/oignon/, '🧅'],
  [/carotte/, '🥕'],
  [/banane/, '🍌'],
  [/pommes?/, '🍎'],
  [/orange/, '🍊'],
  [/citron/, '🍋'],
  [/lait|yaourt/, '🥛'],
  [/fromage/, '🧀'],
  [/beurre/, '🧈'],
  [/oeufs?|œufs?/, '🥚'],
  [/sucre/, '🍬'],
  [/farine/, '🌾'],
  [/riz/, '🍚'],
  [/thon/, '🐟'],
  [/\bsel\b/, '🧂'],
]

export function itemEmoji(name, fallback = '🍽️') {
  const n = name.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase()
  for (const [re, emoji] of RULES) if (re.test(n)) return emoji
  return fallback
}