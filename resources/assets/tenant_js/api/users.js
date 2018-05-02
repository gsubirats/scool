import axios from 'axios'

export default {
  fetch () {
    return axios.get('/api/v1/users')
  },
  update (user) {
    return axios.put('/api/v1/user', {
      'name': user.name,
      'email': user.email
    })
  },
  store (user) {
    return axios.post('/api/v1/users', {
      'name': user.name,
      'email': user.email,
      'password': user.password,
      'roles': user.roles,
      'type': user.type
    })
  },
  delete (user) {
    return axios.delete('/api/v1/users/' + user.id)
  }
}
