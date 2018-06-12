<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-toolbar color="blue darken-3">
                    <v-toolbar-side-icon class="white--text"></v-toolbar-side-icon>
                    <v-toolbar-title class="white--text title">Places</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon class="white--text" @click="settings">
                        <v-icon>settings</v-icon>
                    </v-btn>
                    <v-btn icon class="white--text" @click="refresh">
                        <v-icon>refresh</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card>
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
                                    no-results-text="No s'ha trobat cap registre coincident"
                                    no-data-text="No hi han dades disponibles"
                                    rows-per-page-text="Places per pàgina"
                            >
                                <template slot="items" slot-scope="{item: job}">
                                    <tr>
                                        <td class="text-xs-left" v-html="job.id"></td>
                                        <td class="text-xs-left" v-if="showJobTypeHeader" v-html="job.type"></td>
                                        <td class="text-xs-left" v-html="job.code"></td>
                                        <td class="text-xs-left">
                                            <v-avatar color="grey lighten-4" :size="40">
                                                <img :src="'/user/' + job.holder_hashid + '/photo'"
                                                     :alt="job.holder_description"
                                                     :title="job.holder_description">
                                            </v-avatar>
                                        </td>
                                        <td class="text-xs-left">
                                            <template v-if="job.active_user_hash_id">
                                                <v-avatar color="grey lighten-4" :size="40">
                                                    <img :src="'/user/' + job.active_user_hash_id + '/photo'"
                                                         :alt="job.active_user_description"
                                                         :title="job.active_user_description">
                                                </v-avatar>
                                            </template>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-avatar color="grey lighten-4" :size="40" v-for="substitute in job.substitutes" :key="substitute.id">
                                                <img :src="'/user/' + substitute.hash_id + '/photo'"
                                                     :alt="substitute.description"
                                                     :title="substitute.description">
                                            </v-avatar>
                                            <remove-substitutes-icon v-if="job.substitutes.length > 0" :job="job"></remove-substitutes-icon>
                                        </td>
                                        <td class="text-xs-left" v-html="job.fullcode"></td>
                                        <td class="text-xs-left" v-html="job.order">{{ job.order }}</td>
                                        <td class="text-xs-left" :title="job.family_description" v-html="job.family_code"></td>
                                        <td class="text-xs-left" :title="job.specialty_description" v-html="job.specialty_code"></td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" v-html="job.notes"></td>
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
                                            <!--TODO-->
                                            <add-substitute-icon></add-substitute-icon>
                                            <stop-substitution-icon :job="job"></stop-substitution-icon>
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
  import AddSubstituteIcon from './AddSubstituteIconComponent'
  import StopSubstitutionIcon from './StopSubstitutionIconComponent'
  import RemoveSubstitutesIcon from './RemoveSubstitutesIconComponent'

  export default {
    components: {
      'confirm-icon': ConfirmIcon,
      'job-type-select': JobTypeSelect,
      'add-substitute-icon': AddSubstituteIcon,
      'stop-substitution-icon': StopSubstitutionIcon,
      'remove-substitutes-icon': RemoveSubstitutesIcon
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
        return this.internaljobs.filter(job => job.type_id === this.jobType.id)
      },
      headers () {
        let headers = []
        headers.push({text: 'Id', align: 'left', value: 'id'})
        if (this.showJobTypeHeader) {
          headers.push({text: 'Tipus', value: 'type'})
        }
        headers.push({text: 'Code', value: 'code'})
        headers.push({text: 'Titular', value: 'holder_description', sortable: false})
        headers.push({text: 'Professor actual', value: 'active_user_description', sortable: false})
        headers.push({text: 'Substituts', sortable: false})
        headers.push({text: 'Codi complet', value: 'fullcode'})
        headers.push({text: 'Order', value: 'order'})
        headers.push({text: 'Família', value: 'family'})
        headers.push({text: 'Especialitat', value: 'specialty'})
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
      refresh () {
        this.$store.dispatch(actions.GET_JOBS).catch(error => {
          this.showError(error)
        })
      },
      settings () {
        console.log('settings TODO')
      },
      addSubstitute () {

      },
      edit () {
        console.log('TODO EDIT')
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
