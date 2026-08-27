import { zodResolver } from '@hookform/resolvers/zod'
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useEffect, useMemo, useState } from 'react'
import { useForm } from 'react-hook-form'
import { useTranslation } from 'react-i18next'
import { NavLink, Navigate, Route, Routes, useNavigate } from 'react-router-dom'
import { z } from 'zod'
import { api, authHeaders } from './lib/api'

type User = {
  id: number
  name: string
  email: string
  account_type: string
  locale?: string
}

type AuthResponse = {
  token: string
  user: User
}

type Language = {
  id: number
  code: string
  name: string
  native_name: string
  is_default: boolean
  is_enabled: boolean
}

type Category = {
  id: number
  category_group: 'residential' | 'commercial'
  slug: string
  name: string
  is_active: boolean
}

type City = {
  id: number
  name: string
  state_name: string
  country_name: string
  is_active: boolean
}

type Property = {
  id: number
  title: string
  property_type: string
  rent_amount: string
  status: string
  city?: { name: string; state_name: string }
}

const loginSchema = z.object({
  email: z.string().email(),
  password: z.string().min(8),
})

type LoginInput = z.infer<typeof loginSchema>

const languageSchema = z.object({
  code: z.string().min(2).max(10),
  name: z.string().min(2).max(100),
  native_name: z.string().min(2).max(100),
  is_default: z.boolean().default(false),
  is_enabled: z.boolean().default(true),
})

type LanguageFormInput = z.input<typeof languageSchema>

const categorySchema = z.object({
  category_group: z.enum(['residential', 'commercial']),
  slug: z.string().min(2).max(80),
  name: z.string().min(2).max(120),
  is_active: z.boolean().default(true),
  sort_order: z.number().int().min(0).default(0),
})

type CategoryFormInput = z.input<typeof categorySchema>

const citySchema = z.object({
  name: z.string().min(2).max(120),
  state_name: z.string().min(2).max(120),
  country_name: z.string().min(2).max(120).default('India'),
  is_active: z.boolean().default(true),
})

type CityFormInput = z.input<typeof citySchema>

const TOKEN_KEY = 'rentdirect-admin-token'
const USER_KEY = 'rentdirect-admin-user'

function useSession() {
  const [token, setToken] = useState<string | null>(() => localStorage.getItem(TOKEN_KEY))
  const [user, setUser] = useState<User | null>(() => {
    const raw = localStorage.getItem(USER_KEY)
    return raw ? (JSON.parse(raw) as User) : null
  })

  const login = (payload: AuthResponse) => {
    localStorage.setItem(TOKEN_KEY, payload.token)
    localStorage.setItem(USER_KEY, JSON.stringify(payload.user))
    setToken(payload.token)
    setUser(payload.user)
  }

  const logout = () => {
    localStorage.removeItem(TOKEN_KEY)
    localStorage.removeItem(USER_KEY)
    setToken(null)
    setUser(null)
  }

  return { token, user, login, logout }
}

function LoginPage({ onLogin }: { onLogin: (payload: AuthResponse) => void }) {
  const navigate = useNavigate()
  const { t } = useTranslation()
  const form = useForm<LoginInput>({
    resolver: zodResolver(loginSchema),
    defaultValues: { email: '', password: '' },
  })

  const mutation = useMutation({
    mutationFn: async (values: LoginInput) => {
      const { data } = await api.post<AuthResponse>('/auth/login', { ...values, device_name: 'react-admin' })
      return data
    },
    onSuccess: (data) => {
      onLogin(data)
      navigate('/')
    },
  })

  return (
    <div className="auth-shell">
      <form
        className="card form"
        onSubmit={form.handleSubmit((values) => {
          mutation.mutate(values)
        })}
      >
        <h1>{t('appName')}</h1>
        <h2>{t('login')}</h2>

        <label>{t('email')}</label>
        <input type="email" {...form.register('email')} />
        <small>{form.formState.errors.email?.message}</small>

        <label>{t('password')}</label>
        <input type="password" {...form.register('password')} />
        <small>{form.formState.errors.password?.message}</small>

        {mutation.isError ? <small>Login failed. Please check credentials.</small> : null}

        <button disabled={mutation.isPending} type="submit">
          {t('login')}
        </button>
      </form>
    </div>
  )
}

function ProtectedLayout({
  token,
  userName,
  onLogout,
}: {
  token: string
  userName: string
  onLogout: () => void
}) {
  const { t, i18n } = useTranslation()

  return (
    <div className="layout">
      <aside className="sidebar">
        <h3>{t('appName')}</h3>
        <nav>
          <NavLink to="/">{t('dashboard')}</NavLink>
          <NavLink to="/properties">{t('properties')}</NavLink>
          <NavLink to="/languages">{t('languages')}</NavLink>
          <NavLink to="/categories">{t('categories')}</NavLink>
          <NavLink to="/cities">{t('cities')}</NavLink>
        </nav>
        <div className="sidebar-actions">
          <select
            value={i18n.language}
            onChange={(e) => {
              void i18n.changeLanguage(e.target.value)
              localStorage.setItem('rentdirect-admin-locale', e.target.value)
            }}
          >
            <option value="en">English</option>
            <option value="hi">हिन्दी</option>
          </select>
          <small>{userName}</small>
          <button
            type="button"
            onClick={async () => {
              try {
                await api.post('/auth/logout', undefined, { headers: authHeaders(token) })
              } catch {
                // local logout still applies
              } finally {
                onLogout()
              }
            }}
          >
            {t('logout')}
          </button>
        </div>
      </aside>
      <main className="content">
        <Routes>
          <Route path="/" element={<DashboardPage token={token} />} />
          <Route path="/properties" element={<PropertiesPage token={token} />} />
          <Route path="/languages" element={<LanguagesPage token={token} />} />
          <Route path="/categories" element={<CategoriesPage token={token} />} />
          <Route path="/cities" element={<CitiesPage token={token} />} />
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </main>
    </div>
  )
}

function DashboardPage({ token }: { token: string }) {
  const health = useQuery({
    queryKey: ['health'],
    queryFn: async () => {
      const { data } = await api.get<{ status: string }>('/health')
      return data
    },
  })

  const me = useQuery({
    queryKey: ['me'],
    queryFn: async () => {
      const { data } = await api.get<{ user: User }>('/auth/me', { headers: authHeaders(token) })
      return data.user
    },
  })

  return (
    <section className="card">
      <h2>Dashboard</h2>
      <p>API health: {health.data?.status ?? '...'}</p>
      <p>Logged in as: {me.data?.name ?? '...'}</p>
      <p>Stack: Laravel API + React Admin + Flutter app integration ready.</p>
    </section>
  )
}

function PropertiesPage({ token }: { token: string }) {
  const query = useQuery({
    queryKey: ['properties'],
    queryFn: async () => {
      const { data } = await api.get<{ data: Property[] }>('/properties?status=active&per_page=20', {
        headers: authHeaders(token),
      })
      return data.data
    },
  })

  return (
    <section className="card">
      <h2>Properties</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Type</th>
            <th>Rent</th>
            <th>Status</th>
            <th>City</th>
          </tr>
        </thead>
        <tbody>
          {query.data?.map((property) => (
            <tr key={property.id}>
              <td>{property.id}</td>
              <td>{property.title}</td>
              <td>{property.property_type}</td>
              <td>{property.rent_amount}</td>
              <td>{property.status}</td>
              <td>{property.city ? `${property.city.name}, ${property.city.state_name}` : '-'}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </section>
  )
}

function LanguagesPage({ token }: { token: string }) {
  const queryClient = useQueryClient()
  const { t } = useTranslation()
  const form = useForm<LanguageFormInput>({
    resolver: zodResolver(languageSchema),
    defaultValues: {
      code: '',
      name: '',
      native_name: '',
      is_default: false,
      is_enabled: true,
    },
  })

  const query = useQuery({
    queryKey: ['languages'],
    queryFn: async () => {
      const { data } = await api.get<Language[]>('/admin/languages', { headers: authHeaders(token) })
      return data
    },
  })

  const mutation = useMutation({
    mutationFn: async (values: LanguageFormInput) => {
      await api.post('/admin/languages', languageSchema.parse(values), { headers: authHeaders(token) })
    },
    onSuccess: async () => {
      form.reset()
      await queryClient.invalidateQueries({ queryKey: ['languages'] })
    },
  })

  return (
    <section className="card">
      <h2>{t('languages')}</h2>
      <form
        className="inline-form"
        onSubmit={form.handleSubmit((values) => {
          mutation.mutate(values)
        })}
      >
        <input placeholder={t('code')} {...form.register('code')} />
        <input placeholder={t('name')} {...form.register('name')} />
        <input placeholder={t('nativeName')} {...form.register('native_name')} />
        <button type="submit">{t('create')}</button>
      </form>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>{t('code')}</th>
            <th>{t('name')}</th>
            <th>{t('nativeName')}</th>
          </tr>
        </thead>
        <tbody>
          {query.data?.map((language) => (
            <tr key={language.id}>
              <td>{language.id}</td>
              <td>{language.code}</td>
              <td>{language.name}</td>
              <td>{language.native_name}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </section>
  )
}

function CategoriesPage({ token }: { token: string }) {
  const queryClient = useQueryClient()
  const { t } = useTranslation()
  const form = useForm<CategoryFormInput>({
    resolver: zodResolver(categorySchema),
    defaultValues: {
      category_group: 'residential',
      slug: '',
      name: '',
      is_active: true,
      sort_order: 0,
    },
  })

  const query = useQuery({
    queryKey: ['categories'],
    queryFn: async () => {
      const { data } = await api.get<Category[]>('/admin/categories', { headers: authHeaders(token) })
      return data
    },
  })

  const mutation = useMutation({
    mutationFn: async (values: CategoryFormInput) => {
      await api.post('/admin/categories', categorySchema.parse(values), { headers: authHeaders(token) })
    },
    onSuccess: async () => {
      form.reset({ category_group: 'residential', slug: '', name: '', is_active: true, sort_order: 0 })
      await queryClient.invalidateQueries({ queryKey: ['categories'] })
    },
  })

  return (
    <section className="card">
      <h2>{t('categories')}</h2>
      <form
        className="inline-form"
        onSubmit={form.handleSubmit((values) => {
          mutation.mutate(values)
        })}
      >
        <select {...form.register('category_group')}>
          <option value="residential">residential</option>
          <option value="commercial">commercial</option>
        </select>
        <input placeholder={t('slug')} {...form.register('slug')} />
        <input placeholder={t('name')} {...form.register('name')} />
        <button type="submit">{t('create')}</button>
      </form>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>{t('categoryGroup')}</th>
            <th>{t('slug')}</th>
            <th>{t('name')}</th>
          </tr>
        </thead>
        <tbody>
          {query.data?.map((category) => (
            <tr key={category.id}>
              <td>{category.id}</td>
              <td>{category.category_group}</td>
              <td>{category.slug}</td>
              <td>{category.name}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </section>
  )
}

function CitiesPage({ token }: { token: string }) {
  const queryClient = useQueryClient()
  const { t } = useTranslation()
  const form = useForm<CityFormInput>({
    resolver: zodResolver(citySchema),
    defaultValues: {
      name: '',
      state_name: '',
      country_name: 'India',
      is_active: true,
    },
  })

  const query = useQuery({
    queryKey: ['cities'],
    queryFn: async () => {
      const { data } = await api.get<City[]>('/admin/cities', { headers: authHeaders(token) })
      return data
    },
  })

  const mutation = useMutation({
    mutationFn: async (values: CityFormInput) => {
      await api.post('/admin/cities', citySchema.parse(values), { headers: authHeaders(token) })
    },
    onSuccess: async () => {
      form.reset({ name: '', state_name: '', country_name: 'India', is_active: true })
      await queryClient.invalidateQueries({ queryKey: ['cities'] })
    },
  })

  return (
    <section className="card">
      <h2>{t('cities')}</h2>
      <form
        className="inline-form"
        onSubmit={form.handleSubmit((values) => {
          mutation.mutate(values)
        })}
      >
        <input placeholder={t('name')} {...form.register('name')} />
        <input placeholder={t('state')} {...form.register('state_name')} />
        <input placeholder={t('country')} {...form.register('country_name')} />
        <button type="submit">{t('create')}</button>
      </form>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>{t('name')}</th>
            <th>{t('state')}</th>
            <th>{t('country')}</th>
          </tr>
        </thead>
        <tbody>
          {query.data?.map((city) => (
            <tr key={city.id}>
              <td>{city.id}</td>
              <td>{city.name}</td>
              <td>{city.state_name}</td>
              <td>{city.country_name}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </section>
  )
}

export default function App() {
  const session = useSession()

  const app = useMemo(() => {
    if (!session.token || !session.user) {
      return <Routes><Route path="*" element={<LoginPage onLogin={session.login} />} /></Routes>
    }

    return <ProtectedLayout token={session.token} userName={session.user.name} onLogout={session.logout} />
  }, [session])

  useEffect(() => {
    if (session.user?.locale) {
      localStorage.setItem('rentdirect-admin-locale', session.user.locale)
    }
  }, [session.user?.locale])

  return app
}
