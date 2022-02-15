import { testerService } from '../../services'

export function helloWorld (context) {
    return new Promise((resolve, reject) => {
        testerService.hello()
        .then(response => {
            context.commit('LOAD_TESTER', response.data)
            resolve(response.data)
        })
        .catch(e => {
            reject(e.response)
        })
    })

}

export function loadPage (context, id) {
    return new Promise((resolve, reject) => {
        testerService.getPagesById(id)
      .then(response => {
        context.commit('LOAD_TESTER', response.data)
        resolve(response.data)
      })
      .catch(e => {
        reject(e.response)
      })
    })
  }