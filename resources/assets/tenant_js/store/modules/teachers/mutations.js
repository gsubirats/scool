import * as types from '../../mutation-types'

export default {
  [ types.SET_PENDING_TEACHERS ] (state, pendingTeachers) {
    state.pendingTeachers = pendingTeachers
  },
  [ types.SET_TEACHERS ] (state, teachers) {
    state.teachers = teachers
  }
}
