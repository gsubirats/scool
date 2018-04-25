<template>
    <button type="button" class="btn btn-block" :class="cssClass" @click="test"> {{ text }}</button>
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
      test () {
        axios.get('api/v1/tenant/' + this.tenant.id + '/test-user') // eslint-disable-line
          .then(response => {
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
