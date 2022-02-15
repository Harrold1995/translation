
import { fatService } from '../../services'

export function fatAction (context, payload) {
  return new Promise((resolve, reject) => {
    fatService.fatApi(payload)
      .then(response => {
        context.commit('LOAD_DOCUMENTS', response.data.message)
        resolve(response.data.message)
      })
      .catch(e => {
        reject(e)
      })
  })
}

export function fatDetailAction (context, payload) {
  return new Promise((resolve, reject) => {
    fatService.fatDocumentDetails(payload)
      .then(response => {
        context.commit('LOAD_DOCUMENT', response.data)
        resolve(response.data)
      })
      .catch(e => {
        reject(e)
      })
  })
}