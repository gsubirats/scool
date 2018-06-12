<template>
    <v-btn title="Finalitzar substitució actual" icon class="mx-0" @click="stop()">
        <v-icon>stop</v-icon>
    </v-btn>
</template>

<script>
  export default {
    name: 'StopSubstitutionIconComponent',
    methods: {
      stop () {
        console.log(this.job)
        console.log('TODO stop substitution')
      }
    },
    props: {
      job: {
        type: Object,
        required: true
      }
    }
  }
</script>
