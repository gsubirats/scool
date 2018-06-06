<template>
    <jobs-select :jobs="jobs" label="Escolliu la plaça a assignar"></jobs-select>
</template>

<script>
  import JobsSelect from './JobsSelectComponent.vue'

  export default {
    name: 'JobsSelectForPendingTeacher',
    components: {
      'jobs-select': JobsSelect
    },
    props: {
      jobs: {
        type: Array,
        required: true
      }
    }
  }
</script>
