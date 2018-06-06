<template>
    <v-text-field prepend-icon="person"
                  v-model="username"
                  solo
                  label="Nom usuari"
                  :loading="loading"
    ></v-text-field>
</template>

<script>
  import axios from 'axios'
  import withSnackbar from '../mixins/withSnackbar'

  export default {
    name: 'ProposedUserComponent',
    mixins: [withSnackbar],
    data () {
      return {
        loading: true,
        username: "Calculant el nom d'usuari"
      }
    },
    props: {
      name: {
        type: String,
        required: true
      },
      sn1: {
        type: String,
        required: true
      }
    },
    watch: {
      name (value) {
        this.proposeFreeUserName(value, this.sn1)
      },
      sn1 (value) {
        this.proposeFreeUserName(this.name, value)
      }
    },
    methods: {
      proposeFreeUserName (name, sn1) {
        if (name && sn1) {
          axios.get('/api/v1/proposeFreeUserName/' + name + '/' + sn1).then(response => {
            this.loading = false
            this.username = response.data
          }).catch(error => {
            this.loading = false
            console.log(error)
            this.showError(error)
          })
        }
      }
    },
    created () {
      this.proposeFreeUserName(this.name, this.sn1)
    }
  }
</script>
