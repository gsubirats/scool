<template>
    <button type="button" class="btn btn-block" :class="cssClass" @click="askPassword"> {{ text }}</button>
</template>

<script>
  export default {
    data () {
      return {
        cssClass: 'btn-default',
        text: 'Test user'
      }
    },
    props: {
      tenant: {
        type: Object,
        required: true
      }
    },
    methods: {
      askPassword () {
        this.test('secret')
      },
      test (password) {
        axios.post('api/v1/tenant/' + this.tenant.id + '/test-user',{'password': password}) // eslint-disable-line
          .then(response => {
            //TODO show alert with exceptions messages
            console.log(response.data.exception)
            if (response.data.connection === 'ok') {
              this.cssClass = 'btn-success'
              this.text = 'Ok'
            } else {
              this.cssClass = 'btn-danger'
              this.text = 'Error'
            }
          }).catch(error => {
            console.log(error)
          })
      }
    }
  }
</script>
