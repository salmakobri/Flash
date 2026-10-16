import { createContext, useContext, useEffect, useMemo, useState } from 'react'

const CartContext = createContext(null)
const KEY = 'flash_cart'
const EMPTY = { store: null, items: [], request: '' }

function load() {
  try {
    return { ...EMPTY, ...JSON.parse(localStorage.getItem(KEY)) }
  } catch {
    return EMPTY
  }
}

// Frais de livraison : même règle que app/Services/DeliveryFee.php
// (affichage seulement : le serveur recalcule le vrai total)
export function deliveryFee(subtotal, hasMeal) {
  if (hasMeal) return 15
  if (subtotal < 20) return 5
  if (subtotal < 35) return 8
  if (subtotal < 50) return 10
  if (subtotal < 70) return 12
  return 15
}
export const FREE_ORDER_FEE = 10

export function CartProvider({ children }) {
  const [cart, setCart] = useState(load)

  useEffect(() => {
    try {
      localStorage.setItem(KEY, JSON.stringify(cart))
    } catch {
      /* stockage indisponible : le panier reste en mémoire */
    }
  }, [cart])

  const value = useMemo(() => {
    const count = cart.items.reduce((n, i) => n + i.qty, 0)
    const subtotal = cart.items.reduce((s, i) => s + i.price * i.qty, 0)
    const hasMeal = cart.items.some((i) => i.meal)
    const hasCart = count > 0 || cart.request.trim().length > 0

    // Un panier = un seul magasin
    const add = (store, item) =>
      setCart((c) => {
        const base =
          c.store?.id === store.id
            ? c
            : { ...EMPTY, store: { id: store.id, name: store.name } }
        const found = base.items.find((i) => i.id === item.id)
        const items = found
          ? base.items.map((i) => (i.id === item.id ? { ...i, qty: i.qty + 1 } : i))
          : [...base.items, { id: item.id, name: item.name, price: item.price, meal: !!item.meal, qty: 1 }]
        return { ...base, items }
      })

    const change = (id, delta) =>
      setCart((c) => ({
        ...c,
        items: c.items.map((i) => (i.id === id ? { ...i, qty: i.qty + delta } : i)).filter((i) => i.qty > 0),
      }))

    const setRequest = (store, text) =>
      setCart({ ...EMPTY, store: { id: store.id, name: store.name }, request: text })

    const clear = () => setCart(EMPTY)

    return { cart, count, subtotal, hasMeal, hasCart, add, change, setRequest, clear }
  }, [cart])

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>
}

export const useCart = () => useContext(CartContext)