<template>
    <v-select
            :label="label"
            :items="jobTypes"
            v-model="internalJobType"
            item-text="name"
            item-value="id"
            autocomplete
            clearable
            @input="input"
    >
    </v-select>
</template>

<script>
  export default {
    name: 'JobTypeSelectComponent',
    data () {
      return {
        internalJobType: this.jobType
      }
    },
    model: {
      prop: 'jobType',
      event: 'input'
    },
    props: {
      jobType: {
        type: Number
      },
      label: {
        type: String,
        default: 'Escolliu el tipus de plaça'
      },
      jobTypes: {
        type: Array,
        required: true
      },
      errorMessages: {
        type: Function,
        required: false
      }
    },
    watch: {
      jobType (newJobType) {
        this.internalJobType = this.jobType
      }
    },
    methods: {
      input (value) {
        this.$emit('input', value)
      }
    }
  }

</script>
