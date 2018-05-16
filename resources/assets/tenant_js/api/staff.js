import axios from 'axios'

export default {
  fetch () {
    return axios.get('/api/v1/staff')
  },
  store (staff) {
    return axios.post('/api/v1/staff', {
      'type': staff.type,
      'code': staff.code,
      'family': staff.family,
      'specialty': staff.specialty,
      'holder': staff.holder,
      'notes': staff.notes
    })
  },
  delete (staff) {
    return axios.delete('/api/v1/staff/' + staff.id)
  }
}
