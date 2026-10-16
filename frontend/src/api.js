import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL ?? 'http://localhost:8000/api',
  headers: { Accept: 'application/json' },
  timeout: 10000,
})

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})

export function errorMessage(err, fallback) {
  const data = err.response?.data
  if (data?.errors) return Object.values(data.errors)[0][0]
  if (data?.message) return data.message
  if (!err.response) return 'Cannot reach the server. Is it running?'
  return fallback
}

export default api