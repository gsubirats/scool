<template>
    <confirm-icon
            :id="'substitutions_remove_' + job.code"
            icon="delete"
            color="pink"
            :working="removing"
            @confirmed="removeSubstitutes()"
            tooltip="Eliminar tots els substituts"
            message="Esteu segurs que voleu eliminar totes les substitucions associades a aquesta plaça?"
    ></confirm-icon>
</template>

<script>
  import ConfirmIcon from '../ui/ConfirmIconComponent'
  import axios from 'axios'
  import withSnackbar from '../mixins/withSnackbar'

  export default {
    mixins: [withSnackbar],
    name: 'RemoveSustitutesIconComponent',
    components: {
      'confirm-icon': ConfirmIcon
    },
    data () {
      return {
        removing: false
      }
    },
    props: {
      job: {
        type: Object,
        required: true
      }
    },
    methods: {
      removeSubstitutes () {
        this.removing = true
        axios.delete('api/v1/job/substitutes').then(response => {
          console.log(response)
          this.removing = false
        }).catch(error => {
          console.log(error)
          this.showError(error)
          this.removing = false
        })
      }
    }
  }
</script>
