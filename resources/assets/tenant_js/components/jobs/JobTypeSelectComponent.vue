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
            @blur="blur"
            :error-messages="errorMessages"
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
        type: Array,
        required: false
      }
    },
    watch: {
      jobType (newJobType) {
        this.internalJobType = this.jobType
      }
    },
    methods: {
      input () {
        this.$emit('input', this.internalJobType)
      },
      blur () {
        this.$emit('input', this.internalJobType)
      }
    }
  }

</script>
