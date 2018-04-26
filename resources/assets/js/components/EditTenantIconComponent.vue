<template>
    <i class="fa fa-fw fa-edit"></i>
</template>

<script>
  import swal from 'sweetalert'

  export default {
    data () {
      return {
        cssClass: 'btn-danger',
        text: 'Eliminar'
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
            if (response.data.connection === 'ok') {
              this.cssClass = 'btn-success'
              this.text = 'Ok'
            } else {
              this.cssClass = 'btn-danger'
              this.text = 'Error'
              swal('Error', response.data.exception, "error")
            }
          }).catch(error => {
            console.log(error)
          })
      }
    }
  }
</script>
