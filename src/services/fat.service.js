
import { api } from 'boot/axios'

export const fatService = {
  fatApi,
  fatViewApi,
  fatDocumentDetails,
  fatProcessLinkApi,
  fatGetSearchCriteriaApi
}

function fatApi (payload) {
  const requestOptions = {
    method: 'POST',
    data: payload
  }
  return api('/plugin/fat', requestOptions)
}

function fatViewApi (payload) {
  const requestOptions = {
    method: 'POST',
    data: payload
  }
  return api('/plugin/fat/view', requestOptions)
}

function fatDocumentDetails (payload) {
  const requestOptions = {
    method: 'POST',
    data: payload
  }
  return api('/plugin/fat/details', requestOptions)
}

function fatProcessLinkApi (payload) {
  const requestOptions = {
    method: 'POST',
    data: payload
  }
  return api('/plugin/fat/process/link', requestOptions)
}

function fatGetSearchCriteriaApi (payload) {
  const requestOptions = {
    method: 'POST',
    data: payload
  }
  return api('/plugin/fat/search/criteria', requestOptions)
}

