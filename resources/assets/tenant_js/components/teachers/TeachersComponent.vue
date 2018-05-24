<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="blue darken-3 white--text"><h2>Professors</h2></v-card-title>
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
                                    :items="internalTeachers"
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
                                            <span :title="teacher.user.email">{{ name(teacher) }}</span>
                                        </td>
                                        <td class="text-xs-left">
                                            <span :title="specialtyName(teacher)">{{specialtyCode(teacher)}}</span>
                                        </td>
                                        <td class="text-xs-left">
                                            <span :title="familyName(teacher)">{{familyCode(teacher)}}</span>
                                        </td>
                                        <td class="text-xs-left">
                                            <span :title="administrativeStatusName(teacher)">{{ administrativeStatusCode(teacher) }}</span>
                                        </td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <span :title="jobDescription(teacher)">{{ job(teacher) }}</span>
                                        </td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <v-tooltip bottom>
                                                <span slot="activator">TODO2</span>
                                                <span>TODO1</span>
                                            </v-tooltip>
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

                        <v-data-iterator
                                class="hidden-md-and-up"
                                content-tag="v-layout"
                                row
                                wrap
                                :items="internalTeachers"
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

<style>

</style>

<script>
  import { mapGetters } from 'vuex'
  import * as mutations from '../../store/mutation-types'
  import ShowTeacherIcon from './ShowTeacherIconComponent.vue'

  export default {
    components: {
      ShowTeacherIcon
    },
    data () {
      return {
        showDeletePendingTeacherDialog: false,
        search: '',
        deleting: false,
        headers: [
          {text: 'Id', align: 'left', value: 'id'},
          {text: 'Codi', value: 'code'},
          {text: 'Foto', value: 'photo', sortable: false},
          {text: 'Nom', value: 'user.person.s1'},
          {text: 'Especialitat', value: 'username'},
          {text: 'Familia', value: 'email'},
          {text: 'Estatus', value: 'todo'},
          {text: 'Plaça', value: 'roles'},
          {text: 'Data finalització', value: 'todo'},
          {text: 'Data creació', value: 'formatted_created_at'},
          {text: 'Data actualització', value: 'formatted_updated_at'},
          {text: 'Accions', value: 'full_search', sortable: false}
        ]
      }
    },
    computed: {
      ...mapGetters({
        internalTeachers: 'teachers'
      })
    },
    props: {
      teachers: {
        type: Array,
        required: true
      }
    },
    methods: {
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
      name (teacher) {
        return teacher.user.person.sn1 + ' ' + teacher.user.person.sn2 + ', ' + teacher.user.person.givenName
      },
      job (teacher) {
        return teacher.user.jobs[0].family.code + '_' + teacher.user.jobs[0].specialty.code + '_' + teacher.user.jobs[0].order + '_' + teacher.user.jobs[0].code
      },
      jobDescription (teacher) {
        return 'Plaça num ' + teacher.user.jobs[0].order + ' de la família ' + teacher.user.jobs[0].family.name + ', especialitat ' + teacher.user.jobs[0].specialty.name + ', assignada al professor ' + this.teacherDescription(teacher.user.jobs[0].code)
      },
      teacherDescription (teacherCode) {
        let teacher = this.teachers.find(teacher => { return teacher.code === teacherCode })
        return teacher.user.name + ' (' + teacher.code + ')'
      }
    },
    created () {
      this.$store.commit(mutations.SET_TEACHERS, this.teachers)
    }
  }
</script>
