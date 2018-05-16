import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import api from '../../../api/staff'

export default {
  [ actions.STORE_STAFF ] (context, staff) {
    return new Promise((resolve, reject) => {
      api.store(staff).then(response => {
        console.log('STHJI')
        console.log(response.data)
        context.commit(mutations.ADD_STAFF, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.DELETE_STAFF ] (context, staff) {
    return new Promise((resolve, reject) => {
      api.delete(staff).then(response => {
        context.commit(mutations.DELETE_STAFF, staff)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
