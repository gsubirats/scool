<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="blue darken-3 white--text"><h2>Professors</h2></v-card-title>
                    <v-card-text class="px-0 mb-2">
                        <v-card>
                            <v-card-title>
                                <administrative-status-select
                                        :administrative-statuses="administrativeStatuses"
                                        v-model="administrativeStatus"
                                ></administrative-status-select>
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
                                    :items="filteredTeachers"
                                    :search="search"
                                    item-key="id"
                                    disable-initial-sort
                                    no-results-text="No s'ha trobat cap registre coincident"
                                    no-data-text="No hi han dades disponibles"
                                    rows-per-page-text="Professors per pàgina"
                            >
                                <template slot="items" slot-scope="{ item: teacher }">
                                    <tr>
                                        <td class="text-xs-left">
                                            {{ teacher.id }}
                                        </td>
                                        <td class="text-xs-left">{{ teacher.code }}</td>
                                        <td class="text-xs">
                                            <v-avatar color="grey lighten-4" :size="40">
                                                <img :src="'/user/' + teacher.user.hashid + '/photo'" :alt="teacher.user.name" :title="teacher.user.name">
                                            </v-avatar>
                                        </td>
                                        <td class="text-xs-left">
                                            <span :title="teacher.user.email">{{ teacher.user.person.fullname }}</span>
                                        </td>
                                        <td class="text-xs-left">
                                            <span :title="departmentDescription(teacher)">{{department(teacher)}}</span>
                                        </td>
                                        <td class="text-xs-left">
                                            <span :title="specialtyName(teacher)">{{specialtyCode(teacher)}}</span>
                                        </td>
                                        <td class="text-xs-left">
                                            <span :title="familyName(teacher)">{{familyCode(teacher)}}</span>
                                        </td>
                                        <td class="text-xs-left" v-if="showStatusHeader">
                                            <span :title="administrativeStatusName(teacher)">{{ administrativeStatusCode(teacher) }}</span>
                                        </td>

                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <span :title="jobDescription(teacher)">{{ job(teacher) }}</span>
                                        </td>

                                        <td class="text-xs-left" v-if="showSubstituteHeaders">
                                            {{ jobStartAt(teacher) }}
                                        </td>
                                        <td class="text-xs-left" v-if="showSubstituteHeaders">
                                            {{ jobEndAt(teacher) }}
                                        </td>
                                        <td class="text-xs-left">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ teacher.formatted_created_at_diff }}</span>
                                                <span>{{ teacher.formatted_created_at }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ teacher.formatted_updated_at_diff }}</span>
                                                <span>{{ teacher.formatted_updated_at }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <show-teacher-icon :teacher="teacher" :teachers="teachers"></show-teacher-icon>

                                            <v-btn icon class="mx-0" @click="editItem(teacher)">
                                                <v-icon color="teal">edit</v-icon>
                                            </v-btn>
                                            <v-btn icon class="mx-0" @click="showConfirmationDialog(teacher)">
                                                <v-icon color="pink">delete</v-icon>
                                                <v-dialog v-model="showDeletePendingTeacherDialog" max-width="500px">
                                                    <v-card>
                                                        <v-card-text>
                                                            Esteu segurs que voleu eliminar aquest usuari?
                                                        </v-card-text>
                                                        <v-card-actions>
                                                            <v-btn flat @click.stop="showDeletePendingTeacherDialog=false">Cancel·lar</v-btn>
                                                            <v-btn color="primary" @click.stop="deleteUser" :loading="deleting">Esborrar</v-btn>
                                                        </v-card-actions>
                                                    </v-card>
                                                </v-dialog>
                                            </v-btn>
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
  import ShowTeacherIcon from './ShowTeacherIconComponent.vue'
  import AdministrativeStatusSelect from './AdministrativeStatusSelectComponent.vue'

  export default {
    components: {
      ShowTeacherIcon, // show-teacher-icon
      AdministrativeStatusSelect // administrative-status-select
    },
    data () {
      return {
        administrativeStatus: {},
        showDeletePendingTeacherDialog: false,
        search: '',
        deleting: false
      }
    },
    computed: {
      ...mapGetters({
        internalTeachers: 'teachers'
      }),
      filteredTeachers: function () {
        if (this.showStatusHeader) return this.internalTeachers
        return this.internalTeachers.filter(teacher => teacher.administrative_status_id === this.administrativeStatus.id)
      },
      headers () {
        let headers = []
        headers.push({text: 'Id', align: 'left', value: 'id'})
        headers.push({text: 'Codi', value: 'code'})
        headers.push({text: 'Foto', value: 'full_search', sortable: false})
        headers.push({text: 'Nom', value: 'user.person.fullname' })
        headers.push({text: 'Departament', value: 'department.code' })
        headers.push({text: 'Especialitat', value: 'specialty.code'})
        headers.push({text: 'Familia', value: 'specialty.family.code'})
        if (this.showStatusHeader) headers.push({text: 'Estatus', value: 'administrative_status.code'})
        headers.push({text: 'Plaça', value: 'user.jobs[0].fullcode'})
        if (this.showSubstituteHeaders) {
          headers.push({text: 'Data inici', value: 'todo'})
          headers.push({text: 'Data fí', value: 'todo'})
        }
        headers.push({text: 'Data creació', value: 'formatted_created_at'})
        headers.push({text: 'Data actualització', value: 'formatted_updated_at'})
        headers.push({text: 'Accions', sortable: false})
        return headers
      },
      showStatusHeader () {
        if (this.administrativeStatus != null && Object.keys(this.administrativeStatus).length !== 0) return false
        return true
      },
      showSubstituteHeaders () {
        if (this.administrativeStatus != null && Object.keys(this.administrativeStatus).length !== 0 && this.administrativeStatus.code === 'SUBSTITUT') return true
        return false
      }
    },
    props: {
      teachers: {
        type: Array,
        required: true
      },
      administrativeStatuses: {
        type: Array,
        required: true
      }
    },
    methods: {
      department (teacher) {
        if (teacher.department) {
          return teacher.department.code
        }
      },
      departmentDescription (teacher) {
        if (teacher.department) {
          return teacher.department.name
        }
      },
      specialtyCode (teacher) {
        if (teacher.specialty) {
          return teacher.specialty.code
        }
      },
      specialtyName (teacher) {
        if (teacher.specialty) {
          return teacher.specialty.name
        }
      },
      familyCode (teacher) {
        if (teacher.specialty && teacher.specialty.family) {
          return teacher.specialty.family.code
        }
      },
      familyName (teacher) {
        if (teacher.specialty && teacher.specialty.family) {
          return teacher.specialty.family.name
        }
      },
      administrativeStatusName (teacher) {
        if (teacher.administrative_status) {
          return teacher.administrative_status.name
        }
      },
      administrativeStatusCode (teacher) {
        if (teacher.administrative_status) {
          return teacher.administrative_status.code
        }
      },
      job (teacher) {
        const job = teacher.user.jobs[0]
        if (teacher.user && teacher.user.jobs && job) {
          return job.fullcode
        } else {
          return ''
        }
      },
      jobStartAt (teacher) {
        const job = teacher.user.jobs[0]
        if (teacher.user && teacher.user.jobs && job) {
          return teacher.user.jobs[0].employee.start_at
        } else {
          return ''
        }
      },
      jobEndAt (teacher) {
        const job = teacher.user.jobs[0]
        if (teacher.user && teacher.user.jobs && job) {
          return teacher.user.jobs[0].employee.end_at
        } else {
          return ''
        }
      },
      jobDescription (teacher) {
        const job = teacher.user.jobs[0]
        if (teacher.user && teacher.user.jobs && job) {
          return job.description + ', assignada al professor ' + this.teacherDescription(job.code)
        } else {
          return ''
        }
      },
      teacherDescription (teacherCode) {
        let teacher = this.teachers.find(teacher => { return teacher.code === teacherCode })
        if (teacher) return teacher.user.name + ' (' + teacher.code + ')'
        return ''
      }
    },
    created () {
      this.$store.commit(mutations.SET_TEACHERS, this.teachers)
    }
  }
</script>
