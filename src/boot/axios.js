import { boot } from 'quasar/wrappers'
import axios from 'axios'

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)
// const api = axios.create({ baseURL: 'https://api.example.com' })

switch (window.location.hostname) {
  case 'localhost':
  case '127.0.0.1':
    var baseURL = 'http://localhost:8084/v1'
    break
  case 'findatranslationcmsdev.allgraduates.com.au':
    var baseURL = 'api/public/v1'
    break
  case 'fatcms.allgraduates.com.au':
    var baseURL = 'api/public/v1'
    break
  default:
    throw ('Unknown environment: ' + window.location.hostname)
}

const api = axios.create({
  baseURL: baseURL,
  withCredentials: false, // This is the default
  crossDomain: true,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
    // Authorization: localStorage.getItem('user')
  },
  timeout: 10000
})

// Add a response interceptor
api.interceptors.response.use(function (response) {
  return response
}, function (error) {
  console.log(error.response.status)

  return Promise.reject(error)
})

export default boot(({ app }) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api

  app.config.globalProperties.$axios = axios
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
})

export { axios, api }
