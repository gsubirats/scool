<template>
    <v-dialog
            v-model="dialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
    >
        <v-btn icon slot="activator" title="Afegir substitut">
            <v-icon color="teal">add</v-icon>
        </v-btn>
        <v-card tile>
            <v-toolbar card dark color="primary">
                <v-btn icon dark @click.native="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Afegir substitut</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark flat @click.native="dialog = false">Afegir</v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text>

                <v-list three-line subheader>
                    <v-subheader>Plaça</v-subheader>
                    <v-list-tile>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ job.type }}</v-list-tile-title>
                            <v-list-tile-sub-title>Tipus plaça</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile avatar>
                        <v-list-tile-content>
                            <v-avatar color="grey lighten-4" :size="40">
                                <img :src="'/user/' + job.holder_hashid + '/photo'"
                                     :alt="job.holder_description"
                                     :title="job.holder_description">
                            </v-avatar>
                            <v-list-tile-title>{{ job.holder_name }}</v-list-tile-title>
                            <v-list-tile-sub-title>Titular</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-content>
                            <v-list-tile-title>Especialitat, família, codi i notes</v-list-tile-title>
                            <v-list-tile-sub-title> {{ job.specialty_description }} | {{ job.family_description }} | {{ job.fullcode }} | {{ job.notes }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-divider></v-divider>
                <v-list subheader>
                    <v-subheader>Substitut</v-subheader>
                    <v-list-tile avatar>
                        <v-list-tile-action>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <available-users v-if="dialog" :job-type="job.type_id"></available-users>
                        </v-list-tile-content>
                    </v-list-tile>

                </v-list>
            </v-card-text>

            <div style="flex: 1 1 auto;"></div>
        </v-card>
    </v-dialog>
</template>

<script>
  import AvailableUsers from './AvailableUsersComponent'

  export default {
    name: 'AddSubstituteIconComponent',
    components: {
      'available-users': AvailableUsers
    },
    data () {
      return {
        dialog: false
      }
    },
    props: {
      job: {
        type: Object,
        required: true
      }
    }
  }
</script>
