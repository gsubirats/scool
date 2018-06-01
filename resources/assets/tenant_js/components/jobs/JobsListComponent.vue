<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="blue darken-3 white--text"><h2>Places</h2></v-card-title>
                    <v-card-text class="px-0 mb-2">
                        <v-card>
                            <v-card-title>
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
                                    :items="internaljobs"
                                    :search="search"
                                    item-key="id"
                            >
                                <template slot="items" slot-scope="{item: job}">
                                    <tr>
                                        <td class="text-xs-left">
                                            {{ job.id }}
                                        </td>
                                        <td class="text-xs-left">
                                            {{ type(job) }}
                                        </td>
                                        <td class="text-xs-left">{{ job.code }}</td>
                                        <td class="text-xs-left">{{ job.family && job.family.name}}</td>
                                        <td class="text-xs-left">{{ job.specialty && job.specialty.code }}</td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ job.user && job.user.name }}
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

  export default {
    components: { ConfirmIcon },
    data () {
      return {
        search: '',
        deleting: false,
        headers: [
          {text: 'Id', align: 'left', value: 'id'},
          {text: 'Tipus', align: 'left', value: 'type.name'},
          {text: 'Code', align: 'left', value: 'code'},
          {text: 'Família', value: 'family.name'},
          {text: 'Especialitat', value: 'specialty.code'},
          {text: 'Titular', value: 'user.name'},
          {text: 'Notes', value: 'notes'},
          {text: 'Data creació', value: 'formatted_created_at_diff'},
          {text: 'Data actualització', value: 'formatted_updated_at_diff'},
          {text: 'Accions', sortable: false}
        ]
      }
    },
    computed: {
      ...mapGetters({
        internaljobs: 'jobs'
      })
    },
    props: {
      jobs: {
        type: Array,
        required: true
      }
    },
    methods: {
      edit () {
        console.log('TODO EDIT')
      },
      type (job) {
        return job.type && job.type.name
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
