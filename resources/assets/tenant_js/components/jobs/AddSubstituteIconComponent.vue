<template>
    <v-dialog
            v-model="dialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
            @keydown.esc="dialog = false"
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
                    <v-btn dark flat @click.native="addSubstitute()">Afegir</v-btn>
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
                <v-list three-line subheader>
                    <v-subheader>Substitut</v-subheader>
                    <v-list-tile avatar>
                        <v-list-tile-content>
                            <available-users v-if="dialog" :job-type="job.type_id"></available-users>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-list-tile avatar>
                        <v-list-tile-content>
                            <v-menu
                                    ref="menu"
                                    :close-on-content-click="false"
                                    :value="true"
                                    :nudge-right="40"
                                    :return-value.sync="date"
                                    lazy
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    min-width="290px"
                            >
                                <v-text-field
                                        slot="activator"
                                        v-model="start_date"
                                        label="Data d'inici"
                                        prepend-icon="event"
                                        readonly
                                ></v-text-field>
                                <v-date-picker v-model="start_date" no-title scrollable>
                                    <v-spacer></v-spacer>
                                    <v-btn flat color="primary" @click="menu = false">Cancel</v-btn>
                                    <v-btn flat color="primary" @click="$refs.menu.save(date)">OK</v-btn>
                                </v-date-picker>
                            </v-menu>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-divider></v-divider>
            </v-card-text>
            <v-card-actions>
                <v-btn color="success" @click="addSubstitute()">Afegir</v-btn>
                <v-btn flat @click="dialog=false">Sortir</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import AvailableUsers from './AvailableUsersComponent'
  import withSnackbar from '../mixins/withSnackbar'
  import axios from 'axios'
  import moment from 'moment'

  export default {
    name: 'AddSubstituteIconComponent',
    mixins: [withSnackbar],
    components: {
      'available-users': AvailableUsers
    },
    data () {
      return {
        dialog: false,
        start_date: moment(new Date()).format('YYYY-MM-DD')
      }
    },
    props: {
      job: {
        type: Object,
        required: true
      }
    },
    methods: {
      addSubstitute () {
        console.log('TODO add substitute')
        axios.post('/api/v1/substitute', {
          job: this.job.id,
          user: this.user.id,
          start_at: this.start_date
        }).then(response => {
          console.log(response)
          this.dialog = false
        }).catch(error => {
          console.log(error)
          this.showError(error)
        })
      }
    }
  }
</script>
