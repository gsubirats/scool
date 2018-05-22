import axios from 'axios'

export default {
  fetch () {
    return axios.get('/api/v1/jobs')
  },
  store (job) {
    return axios.post('/api/v1/jobs', {
      'type': job.type,
      'code': job.code,
      'family': job.family,
      'specialty': job.specialty,
      'holder': job.holder,
      'notes': job.notes
    })
  },
  delete (job) {
    return axios.delete('/api/v1/jobs/' + job.id)
  }
}
