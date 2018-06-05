<template>
    <modal-dialog v-model="dialog">
        <v-card>
            <v-card-title>
                Dialog 2
            </v-card-title>
            <v-card-text>
                TODO
            </v-card-text>
        </v-card>
    </modal-dialog>
</template>

<script>
  import DialogComponent from '../ui/DialogComponent'

  export default {
    name: 'AddSubstituteIconComponent',
    components: {
      'modal-dialog': DialogComponent
    },
    data () {
      return {
        dialog: false
      }
    }
  }
</script>
