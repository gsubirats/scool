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
                            >
                                <template slot="items" slot-scope="{ item: teacher }">
                                    <tr>
                                        <td class="text-xs-left">
                                            {{ teacher.id }}
                                        </td>
                                        <td class="text-xs-left">{{ teacher.code }}</td>
                                        <td class="text-xs">
                                            <v-avatar color="grey lighten-4" :size="40">
                                                <img :src="'/user/' + teacher.user.hashid + '/photo'" :alt="teacher.name">
                                            </v-avatar>
                                        </td>
                                        <td class="text-xs-left">
                                            TODO name
                                        </td>
                                        <td class="text-xs-left">
                                            {{ teacher.user.name }}
                                        </td>
                                        <td class="text-xs-left">{{ teacher.user.email }}</td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ showTeacherStaff(teacher) }}
                                        </td>
                                        <td class="text-xs-left">{{ teacher.created_at }}</td>
                                        <td class="text-xs-left">{{ teacher.updated_at }}</td>
                                        <td class="text-xs-left">

                                            <show-teacher-icon :teacher="teacher"></show-teacher-icon>

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
          {text: 'Codi', value: 'user.code'},
          {text: 'Photo', value: 'photo'},
          {text: 'Name', value: 'name'},
          {text: 'UserName', value: 'username'},
          {text: 'Email', value: 'email'},
          {text: 'Plaça', value: 'roles'},
          {text: 'Data creació', value: 'formatted_created_at'},
          {text: 'Data actualització', value: 'formatted_updated_at'},
          {text: 'Accions', sortable: false}
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
      showTeacherStaff (teacher) {
        return teacher.user.staffs[0]
      }
    },
    created () {
      this.$store.commit(mutations.SET_TEACHERS, this.teachers)
    }
  }
</script>
