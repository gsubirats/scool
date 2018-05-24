<template>
    <v-select
            :label="label"
            :items="administrativeStatuses"
            v-model="internalAdministrativeStatus"
            item-text="name"
            autocomplete
            clearable
            @input="input"
    >
    </v-select>
</template>

<style>

</style>

<script>
  export default {
    data () {
      return {
        internalAdministrativeStatus: this.administrativeStatus
      }
    },
    model: {
      prop: 'administrativeStatus',
      event: 'input'
    },
    props: {
      administrativeStatus: {
        type: Object
      },
      label: {
        type: String,
        default: 'Escolliu el tipus de professor'
      },
      administrativeStatuses: {
        type: Array,
        required: true
      },
      errorMessages: {
        type: Function,
        required: false
      }
    },
    methods: {
      input (value) {
        this.$emit('input', value)
      }
    }
  }
</script>
