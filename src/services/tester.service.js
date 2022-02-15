
// import api from '../api/axiosSettings'
import { api } from 'boot/axios'
export const testerService = {
  hello,
  getPagesById
}



function hello () {
  const requestOptions = {
    method: 'GET'
  }
  return api('/test/', requestOptions)
}

function getPagesById (id) {
  const requestOptions = {
    method: 'GET'
  }
  return api('/test/page/' + id, requestOptions)
}

