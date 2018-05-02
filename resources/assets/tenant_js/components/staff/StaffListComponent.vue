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
                                    :items="internalUsers"
                                    :search="search"
                                    item-key="id"
                                    expand
                            >
                                <template slot="items" slot-scope="props">
                                    <tr @click="expand($event, props)">
                                        <td class="text-xs-left">
                                            {{ props.item.id }}
                                        </td>
                                        <td class="text-xs-left">
                                            {{ props.item.name }}
                                        </td>
                                        <td class="text-xs-left">{{ props.item.email }}</td>
                                        <td class="text-xs-left">{{ props.item.type && props.item.type.name }}</td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ formatRoles(props.item) }}</span>
                                                <span>{{ formatRoles(props.item) }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">{{ props.item.formatted_created_at }}</td>
                                        <td class="text-xs-left">{{ props.item.formatted_updated_at }}</td>
                                        <td class="text-xs-left">
                                            <v-btn icon class="mx-0" @click="editItem(props.item)">
                                                <v-icon color="teal">edit</v-icon>
                                            </v-btn>
                                            <v-btn icon class="mx-0" @click="showConfirmationDialog(props.item)">
                                                <v-icon color="pink">delete</v-icon>
                                                <v-dialog v-model="showDeleteUserDialog" max-width="500px">
                                                    <v-card>
                                                        <v-card-text>
                                                            Esteu segurs que voleu eliminar aquest usuari?
                                                        </v-card-text>
                                                        <v-card-actions>
                                                            <v-btn flat @click.stop="showDeleteUserDialog=false">Cancel·lar</v-btn>
                                                            <v-btn color="primary" @click.stop="deleteUser" :loading="deleting">Esborrar</v-btn>
                                                        </v-card-actions>
                                                    </v-card>
                                                </v-dialog>
                                            </v-btn>
                                        </td>
                                    </tr>
                                </template>
                                <template slot="expand" slot-scope="props">
                                    <v-card>
                                        <v-card-text>
                                            <v-list two-line v-if="props.item.inscription_type_id == 1">
                                                <template v-if="props.item.groups && props.item.groups.length">
                                                    <v-list-group
                                                            v-for="(group, index) in props.item.groups"
                                                            :key="group.id"
                                                            no-action
                                                    >
                                                        <v-list-tile slot="activator">
                                                            <v-list-tile-avatar>
                                                                <img :src="'/group/' + group.id + '/avatar'">
                                                            </v-list-tile-avatar>
                                                            <v-list-tile-content>
                                                                <v-list-tile-title>
                                                                    <b>{{ group.name }}</b> |
                                                                    Líder:
                                                                    <template v-if="group.leader">
                                                                        {{this.user.id}} {{group.leader.sn1}} {{group.leader.sn2}}, {{group.leader.givenName}} ({{group.leader.name}})
                                                                    </template>
                                                                    <template v-else>Sense lider assignat</template>
                                                                </v-list-tile-title>
                                                            </v-list-tile-content>
                                                            <v-list-tile-action v-if="canEditGroup(group)">

                                                                <v-btn icon ripple @click.stop="editGroup(group)">
                                                                    <v-icon color="green darken-1">mode_edit</v-icon>
                                                                </v-btn>

                                                            </v-list-tile-action>
                                                            <v-list-tile-action v-if="canEditGroup(group)">
                                                                <v-btn icon ripple @click.stop="unsubscribeGroup(props.item,group)">
                                                                    <v-icon color="red darken-1">delete</v-icon>
                                                                </v-btn>
                                                            </v-list-tile-action>
                                                            <v-list-tile-action v-if="memberOf(group,this.user)">
                                                                <v-btn icon ripple @click.stop="unregisterToEvent(props.item)">
                                                                    <v-icon color="red darken-1">exit_to_app</v-icon>
                                                                </v-btn>
                                                            </v-list-tile-action>
                                                        </v-list-tile>

                                                        <template v-if="group.members &&  group.members.length">
                                                            <v-list-tile v-for="(member, index) in group.members" :key="member.id">
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title>
                                                                        {{index +1}}) {{member.sn1}} {{member.sn2}}, {{member.givenName}} ({{member.name}})
                                                                    </v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                        </template>
                                                        <v-list-tile v-else>
                                                            <v-list-tile-content>
                                                                <v-list-tile-title>
                                                                    Sense membres assignats al grup
                                                                </v-list-tile-title>
                                                            </v-list-tile-content>
                                                        </v-list-tile>

                                                    </v-list-group>
                                                </template>
                                                <template v-else>
                                                    Cap grup inscrit a l'esdeveniment
                                                </template>
                                            </v-list>

                                            <v-list two-line v-else>
                                                <template v-if="props.item.users && props.item.users.length">
                                                    <template v-for="(user, index) in props.item.users">
                                                        <v-list-tile avatar :key="user.title" @click="">
                                                            <v-list-tile-avatar>
                                                                <img :src="gravatarURL(user.email)">
                                                            </v-list-tile-avatar>
                                                            <v-list-tile-content>
                                                                <v-list-tile-title>{{user.sn1}} {{user.sn2}} , {{user.givenName}} ({{user.name}})</v-list-tile-title>
                                                                <v-list-tile-sub-title v-html="user.email"></v-list-tile-sub-title>
                                                            </v-list-tile-content>
                                                        </v-list-tile>
                                                    </template>
                                                </template>
                                                <template v-else>
                                                    Cap usuari inscrit a l'esdeveniment
                                                </template>
                                            </v-list>
                                        </v-card-text>
                                    </v-card>
                                </template>
                            </v-data-table>
                        </v-card>

                        <v-data-iterator
                                class="hidden-md-and-up"
                                content-tag="v-layout"
                                row
                                wrap
                                :items="internalUsers"
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
  export default {
    data () {
      return {
        search: '',
        deleting: false,
        headers: [
          {text: 'Id', align: 'left', value: 'id'},
          {text: 'Tipus', align: 'left', value: 'type'},
          {text: 'Família', value: 'department'},
          {text: 'Observacions', value: 'notes'},
          {text: 'Accions', sortable: false}
        ]
      }
    },
    props: {
      staff: {
        type: Array,
        required: true
      }
    }
  }
</script>
