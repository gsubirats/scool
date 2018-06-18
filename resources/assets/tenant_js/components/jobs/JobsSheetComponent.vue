<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <img src="/img/logo_iesebre.jpg" alt="Logo Institut de l'Ebre">
                <span class="display-1">Places i professorat que ocupa la plaça. {{ academicPeriod()}}</span>
            </v-flex>
        </v-layout>
        <v-layout row wrap>
            <v-flex v-for="job in jobs" :key="job.id" xl1 lg2 md3 sm6 xs12 style="border: 1px solid red;">
                <div style="display: flex;justify-content: space-between;">
                    <span style="display: flex;flex-direction: column;justify-content: space-between;border: 1px solid red;max-width: 75px;overflow: hidden;text-overflow: ellipsis;">
                        <div class="red--text title">40</div>
                        <div class="text-xs-left">{{job.active_user_name}}</div>
                    </span>
                    <user-avatar :hash-id="job.active_user_hash_id"
                                 :alt="job.active_user_description"
                                 size="100"
                                 :tile="true"
                    ></user-avatar>
                </div>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
  import moment from 'moment'
  import UserAvatar from '../ui/UserAvatarComponent'

  export default {
    name: 'JobsSheetComponent',
    components: {
      'user-avatar': UserAvatar
    },
    props: {
      jobs: {
        type: Array,
        required: true
      }
    },
    methods: {
      academicPeriod () {
        // 1 setembre a 31 Agost
        const month = moment().format('M')
        const year = moment().format('YYYY')
        if (month >= 9) {
          const nextYear = moment().add(1, 'years').format('YYYY')
          return year + '-' + nextYear
        } else {
          const previousYear = moment().subtract(1, 'years').format('YYYY')
          return previousYear + '-' + year
        }
      }
    }
  }
</script>

<style scoped>

</style>