<template>
    <jobs-select :job="job()" :jobs="jobs" label="Escolliu la plaça a assignar"></jobs-select>
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
      },
      teacher: {
        type: Object,
        required: true
      }
    },
    methods: {
      job () {
        let foundJob = this.jobs.find((job) => {
          return job.active_user_code === this.teacher.code
        })
        if (foundJob) return foundJob
        return undefined
      }
    }
  }
</script>
