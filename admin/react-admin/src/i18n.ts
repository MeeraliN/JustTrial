import i18n from 'i18next'
import { initReactI18next } from 'react-i18next'

const resources = {
  en: {
    translation: {
      appName: 'RentDirect Admin',
      login: 'Login',
      email: 'Email',
      password: 'Password',
      dashboard: 'Dashboard',
      properties: 'Properties',
      languages: 'Languages',
      categories: 'Categories',
      cities: 'Cities',
      logout: 'Logout',
      create: 'Create',
      name: 'Name',
      code: 'Code',
      nativeName: 'Native Name',
      state: 'State',
      country: 'Country',
      categoryGroup: 'Category Group',
      slug: 'Slug',
    },
  },
  hi: {
    translation: {
      appName: 'रेन्टडायरेक्ट एडमिन',
      login: 'लॉगिन',
      email: 'ईमेल',
      password: 'पासवर्ड',
      dashboard: 'डैशबोर्ड',
      properties: 'प्रॉपर्टीज',
      languages: 'भाषाएँ',
      categories: 'श्रेणियाँ',
      cities: 'शहर',
      logout: 'लॉगआउट',
      create: 'बनाएं',
      name: 'नाम',
      code: 'कोड',
      nativeName: 'मूल नाम',
      state: 'राज्य',
      country: 'देश',
      categoryGroup: 'श्रेणी समूह',
      slug: 'स्लग',
    },
  },
}

void i18n.use(initReactI18next).init({
  resources,
  lng: localStorage.getItem('rentdirect-admin-locale') || 'en',
  fallbackLng: 'en',
  interpolation: {
    escapeValue: false,
  },
})

export default i18n
