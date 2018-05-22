import * as types from '../../mutation-types'

export default {
  [ types.SET_STAFF ] (state, staff) {
    state.staff = staff
  },
  [ types.ADD_STAFF ] (state, staff) {
    state.staff.push(staff)
  },
  [ types.DELETE_STAFF ] (state, staff) {
    state.staff.splice(state.staff.indexOf(staff), 1)
  }
}
