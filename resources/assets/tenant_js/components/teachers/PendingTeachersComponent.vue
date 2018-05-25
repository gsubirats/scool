<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="blue darken-3 white--text"><h2>Professors pendents</h2></v-card-title>
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
                                    :items="internalPendingTeachers"
                                    :search="search"
                                    item-key="id"
                                    no-results-text="No s'ha trobat cap registre coincident"
                                    no-data-text="No hi ha cap professor pendent de confirmar"
                                    rows-per-page-text="Professors per pàgina"
                            >
                                <template slot="items" slot-scope="{ item: teacher }">
                                    <tr>
                                        <td class="text-xs-left">
                                            {{ teacher.id }}
                                        </td>
                                        <td class="text-xs-left">
                                            {{ teacher.specialty && teacher.specialty.code }}
                                        </td>
                                        <td class="text-xs-left">
                                            {{ teacher.sn1 }} {{ teacher.sn2 }}, {{ teacher.name }}
                                        </td>
                                        <td class="text-xs-left">{{ teacher.email }}</td>
                                        <td class="text-xs-left">{{ teacher.telephone }}</td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ teacher.teacher_id }}
                                        </td>
                                        <td class="text-xs-left">{{ teacher.start_date }}</td>
                                        <td class="text-xs-left">{{ teacher.created_at }}</td>
                                        <td class="text-xs-left">{{ teacher.updated_at }}</td>
                                        <td class="text-xs-left">

                                            <show-pending-teacher-icon
                                                    :pending-teacher="teacher"
                                                    :teachers="teachers"
                                                    :specialties="specialties"
                                                    :forces="forces"
                                                    :administrative-statuses="administrativeStatuses">
                                            ></show-pending-teacher-icon>

                                            <v-btn icon class="mx-0" @click="editItem(teacher)">
                                                <v-icon color="teal">edit</v-icon>
                                            </v-btn>

                                            <v-dialog v-model="deleteConfirmationDialog" persistent max-width="290">
                                                <v-btn icon slot="activator">
                                                    <v-icon color="pink">delete</v-icon>
                                                </v-btn>
                                                <v-card>
                                                    <v-card-title class="headline">Si us plau confirmeu</v-card-title>
                                                    <v-card-text>Esteu segurs que voleu eliminar aquesta proposta de nou professor?</v-card-text>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn color="green darken-1" flat @click.native="deleteConfirmationDialog = false">Cancel·lar</v-btn>
                                                        <v-btn color="red darken-1" flat
                                                               :disabled="removing"
                                                               :loading="removing"
                                                               @click.native="remove(teacher)">Eliminar</v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                        </td>
                                    </tr>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>

</template>

<style>

</style>

<script>
  import { mapGetters } from 'vuex'
  import * as mutations from '../../store/mutation-types'
  import ShowPendingTeacherIcon from './ShowPendingTeacherIconComponent.vue'
  import axios from 'axios'

  export default {
    components: {
      ShowPendingTeacherIcon // show-pending-teacher-icon
    },
    data () {
      return {
        removing: false,
        deleteConfirmationDialog: false,
        search: '',
        deleting: false,
        headers: [
          {text: 'Id', align: 'left', value: 'id'},
          {text: 'Especialitat', value: 'specialty.code'},
          {text: 'Name', value: 'name'},
          {text: 'Email', value: 'email'},
          {text: 'Telèfon', value: 'telephone'},
          {text: 'Substitueix', value: 'teacher_id'},
          {text: 'Data incorporació', value: 'start_date'},
          {text: 'Data creació', value: 'formatted_created_at'},
          {text: 'Data actualització', value: 'formatted_updated_at'},
          {text: 'Accions', sortable: false}
        ]
      }
    },
    computed: {
      ...mapGetters({
        internalPendingTeachers: 'pendingTeachers'
      })
    },
    props: {
      teachers: {
        type: Array,
        required: true
      },
      pendingTeachers: {
        type: Array,
        required: true
      },
      specialties: {
        type: Array,
        required: true
      },
      forces: {
        type: Array,
        required: true
      },
      administrativeStatuses: {
        type: Array,
        required: true
      }
    },
    methods: {
      remove (teacher) {
        this.removing = true
        axios.delete('/api/v1/pending_teacher').then(response => {
          this.deleteConfirmationDialog = false
          this.removing = true
        }).then(error => {
          console.log(error)
          this.removing = true
          this.showError(error)
        })
      }
    },
    created () {
      this.$store.commit(mutations.SET_PENDING_TEACHERS, this.pendingTeachers)
    }
  }
</script>
