import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import api from '../../../api/jobs'

export default {
  [ actions.STORE_JOB ] (context, job) {
    return new Promise((resolve, reject) => {
      api.store(job).then(response => {
        console.log('STHJI')
        console.log(response.data)
        context.commit(mutations.ADD_JOB, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.DELETE_JOB ] (context, job) {
    return new Promise((resolve, reject) => {
      api.delete(job).then(response => {
        context.commit(mutations.DELETE_JOB, job)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
