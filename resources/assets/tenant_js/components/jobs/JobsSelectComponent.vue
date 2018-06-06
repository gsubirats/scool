<template>
    <v-select
            :label="label"
            :items="jobs"
            v-model="internalJob"
            item-text="name"
            autocomplete
            clearable
            @input="input"
    >
    </v-select>
</template>

<script>
  export default {
    name: 'JobsSelectComponent',
    data () {
      return {
        internalJob: this.job
      }
    },
    props: {
      job: {
        type: Object
      },
      label: {
        type: String,
        default: 'Escolliu la plaça'
      },
      jobs: {
        type: Array,
        required: true
      }
    },
    methods: {
      input (value) {
        this.$emit('input', value)
      }
    }
  }
</script>
