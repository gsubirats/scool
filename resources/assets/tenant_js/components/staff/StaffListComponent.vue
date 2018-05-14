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
                                    :items="internalStaff"
                                    :search="search"
                                    item-key="id"
                            >
                                <template slot="items" slot-scope="props">
                                    <tr>
                                        <td class="text-xs-left">
                                            {{ props.item.id }}
                                        </td>
                                        <td class="text-xs-left">
                                            {{ type(props.item) }}
                                        </td>
                                        <td class="text-xs-left">{{ props.item.code }}</td>
                                        <td class="text-xs-left">{{ props.item.family && props.item.family.name}}</td>
                                        <td class="text-xs-left">{{ props.item.specialty && props.item.specialty.code }}</td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ props.item.user && props.item.user.name }}
                                        </td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ props.item.notes }}</span>
                                                <span>{{ props.item.notes }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ props.item.formatted_created_at_diff }}</span>
                                                <span>{{ props.item.formatted_created_at }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ props.item.formatted_updated_at_diff }}</span>
                                                <span>{{ props.item.formatted_updated_at }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-btn icon class="mx-0" @click="editItem(props.item)">
                                                <v-icon color="teal">edit</v-icon>
                                            </v-btn>
                                            <v-btn icon class="mx-0" @click="showConfirmationDialog(props.item)">
                                                <v-icon color="pink">delete</v-icon>
                                                <v-dialog v-model="showDeleteStaffDialog" max-width="500px">
                                                    <v-card>
                                                        <v-card-text>
                                                            Esteu segurs que voleu eliminar aquesta plaça?
                                                        </v-card-text>
                                                        <v-card-actions>
                                                            <v-btn flat @click.stop="showDeleteStaffDialog=false">Cancel·lar</v-btn>
                                                            <v-btn color="primary">Esborrar</v-btn>
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
                                :items="internalStaff"
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

  export default {
    data () {
      return {
        search: '',
        deleting: false,
        showDeleteStaffDialog: false,
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
        internalStaff: 'staff'
      })
    },
    props: {
      staff: {
        type: Array,
        required: true
      }
    },
    methods: {
      type (staff) {
        return staff.type && staff.type.name
      }
    },
    created () {
      this.$store.commit(mutations.SET_STAFF, this.staff)
    }
  }
</script>
