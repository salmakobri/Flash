import { Routes, Route } from 'react-router-dom'
import { CartProvider } from './cart'
import Home from './pages/Home'
import Store from './pages/Store'
import Login from './pages/Login'
import Register from './pages/Register'
import Checkout from './pages/Checkout'
import MesCommandes from './pages/MesCommandes'
import Join from './pages/Join'

export default function App() {
  return (
    <CartProvider>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="store/:storeId" element={<Store />} />
        <Route path="login" element={<Login />} />
        <Route path="register" element={<Register />} />
        <Route path="checkout" element={<Checkout />} />
        <Route path="mes-commandes" element={<MesCommandes />} />
        <Route path="join/:type" element={<Join />} />
      </Routes>
    </CartProvider>
  )
}