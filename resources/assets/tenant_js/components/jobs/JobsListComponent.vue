<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="blue darken-3 white--text"><h2>Places</h2></v-card-title>
                    <v-card-text class="px-0 mb-2">
                        <v-card>
                            <v-card-title>
                                <job-type-select
                                        :job-types="jobTypes"
                                        v-model="jobType"
                                ></job-type-select>
                                <v-spacer></v-spacer>
                                <v-text-field
                                        append-icon="search"
                                        label="Buscar"
                                        single-line
                                        hide-details
                                        v-model="search"
                                ></v-text-field>
                            </v-card-title>
                            <v-data-table
                                    class="px-0 mb-2 hidden-sm-and-down"
                                    :headers="headers"
                                    :items="filteredJobs"
                                    :search="search"
                                    item-key="id"
                            >
                                <template slot="items" slot-scope="{item: job}">
                                    <tr>
                                        <td class="text-xs-left">
                                            {{ job.id }}
                                        </td>
                                        <td class="text-xs-left" v-if="showJobTypeHeader">
                                            {{ type(job) }}
                                        </td>
                                        <td class="text-xs-left">{{ job.code }}</td>
                                        <td class="text-xs-left">
                                            <v-avatar color="grey lighten-4" :size="40">
                                                <img :src="'/user/' + job.holders[0].hashid + '/photo'"
                                                     :alt="teacherDescription(job.holders[0])"
                                                     :title="teacherDescription(job.holders[0])">
                                            </v-avatar>
                                        </td>
                                        <td class="text-xs-left">
                                            <template v-if="job.activeUser">
                                                <v-avatar color="grey lighten-4" :size="40">
                                                    <img :src="'/user/' + job.activeUser.hashid + '/photo'"
                                                         :alt="teacherDescription(job.activeUser)"
                                                         :title="teacherDescription(job.activeUser)">
                                                </v-avatar>
                                            </template>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-avatar color="grey lighten-4" :size="40" v-for="substitute in job.substitutes" :key="substitute.id">
                                                <img :src="'/user/' + substitute.hashid + '/photo'"
                                                     :alt="substituteDescription(substitute)"
                                                     :title="substituteDescription(substitute)">
                                            </v-avatar>
                                        </td>
                                        <td class="text-xs-left">{{ job.fullcode }}</td>
                                        <td class="text-xs-left">{{ job.order }}</td>
                                        <td class="text-xs-left">{{ job.family && job.family.name}}</td>
                                        <td class="text-xs-left">
                                            <span :title="specialtyDescription(job)"> {{ specialty(job) }} </span>
                                        </td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ job.notes }}</span>
                                                <span>{{ job.notes }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ job.formatted_created_at_diff }}</span>
                                                <span>{{ job.formatted_created_at }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ job.formatted_updated_at_diff }}</span>
                                                <span>{{ job.formatted_updated_at }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-btn icon class="mx-0" @click="edit(job)">
                                                <v-icon color="teal">edit</v-icon>
                                            </v-btn>
                                            <confirm-icon icon="delete"
                                                          color="pink"
                                                          :working="deleting"
                                                          @confirmed="remove(job)"
                                                          tooltip="Eliminar"
                                            ></confirm-icon>
                                        </td>
                                    </tr>
                                </template>
                            </v-data-table>
                        </v-card>

                        <v-data-iterator
                                class="hidden-md-and-up"
                                content-tag="v-layout"
                                row
                                wrap
                                :items="internaljobs"
                        >
                            <v-flex
                                    slot="item"
                                    slot-scope="props"
                                    xs12
                                    sm6
                                    md4
                                    lg3
                            >
                                <v-card>
                                    <v-card-title><h4>{{ props.item.name }}</h4></v-card-title>
                                    <v-divider></v-divider>
                                    <v-list dense>
                                        <v-list-tile>
                                            <v-list-tile-content>Email:</v-list-tile-content>
                                            <v-list-tile-content class="align-end">{{ props.item.email }}</v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-content>Created at:</v-list-tile-content>
                                            <v-list-tile-content class="align-end">{{ props.item.created_at }}</v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-content>Updated at:</v-list-tile-content>
                                            <v-list-tile-content class="align-end">{{ props.item.updated_at }}</v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                </v-card>
                            </v-flex>
                        </v-data-iterator>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
  import { mapGetters } from 'vuex'
  import * as mutations from '../../store/mutation-types'
  import * as actions from '../../store/action-types'
  import ConfirmIcon from '../ui/ConfirmIconComponent.vue'
  import JobTypeSelect from './JobTypeSelectComponent.vue'

  export default {
    components: {
      ConfirmIcon,
      'job-type-select': JobTypeSelect
    },
    data () {
      return {
        search: '',
        deleting: false,
        jobType: {}
      }
    },
    computed: {
      ...mapGetters({
        internaljobs: 'jobs'
      }),
      filteredJobs: function () {
        if (this.showJobTypeHeader) return this.internaljobs
        return this.internaljobs.filter(job => job.type.id === this.jobType.id)
      },
      headers () {
        let headers = []
        headers.push({text: 'Id', align: 'left', value: 'id'})
        if (this.showJobTypeHeader) {
          headers.push({text: 'Tipus', value: 'type.name'})
        }
        headers.push({text: 'Code', value: 'code'})
        headers.push({text: 'Titular', sortable: false})
        headers.push({text: 'Professor actual', sortable: false})
        headers.push({text: 'Substituts', sortable: false})
        headers.push({text: 'Codi complet', value: 'fullcode'})
        headers.push({text: 'Order', value: 'order'})
        headers.push({text: 'Família', value: 'family.name'})
        headers.push({text: 'Especialitat', value: 'specialty.code'})
        headers.push({text: 'Notes', value: 'notes'})
        if (this.showSubstituteHeaders) {
          headers.push({text: 'Data inici', value: 'todo'})
          headers.push({text: 'Data fí', value: 'todo'})
        }
        headers.push({text: 'Data creació', value: 'formatted_created_at_diff'})
        headers.push({text: 'Data actualització', value: 'formatted_updated_at_diff'})
        headers.push({text: 'Accions', sortable: false})
        return headers
      },
      showJobTypeHeader () {
        if (this.jobType != null && Object.keys(this.jobType).length !== 0) return false
        return true
      }
    },
    props: {
      jobs: {
        type: Array,
        required: true
      },
      jobTypes: {
        type: Array,
        required: true
      }
    },
    methods: {
      teacherDescription (teacher) {
        if (teacher.teacher) {
          return teacher.teacher.code + ' ' + teacher.name
        } else {
          return teacher.name
        }
      },
      substituteDescription (substitute) {
        if (substitute.teacher) {
          return substitute.teacher.code + ' ' + substitute.name + ' ' + substitute.pivot.start_at + '-' + substitute.pivot.end_at
        } else {
          return substitute.name
        }
      },
      edit () {
        console.log('TODO EDIT')
      },
      type (job) {
        return job.type && job.type.name
      },
      specialty (job) {
        if (job.specialty) {
          return job.specialty.code
        }
      },
      specialtyDescription (job) {
        if (job.specialty) {
          return job.specialty.name
        }
      },
      remove (job) {
        this.deleting = true
        this.$store.dispatch(actions.DELETE_JOB, job).then(response => {
          this.deleting = false
        }).catch(error => {
          this.deleting = false
          console.log(error)
          this.showError(error)
        })
      }
    },
    created () {
      this.$store.commit(mutations.SET_JOBS, this.jobs)
    }
  }
</script>
