<template>
    <span>
        <v-dialog
                v-model="dialog"
                fullscreen
                hide-overlay
                transition="dialog-bottom-transition"
                scrollable
                @keydown.esc="dialog=false"
        >
        <v-card tile>
          <v-toolbar card dark color="primary">
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title>Dades substitució</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-btn dark flat @click.native="finishSubstitution()">Finalitzar Subtitució</v-btn>
              <v-btn dark flat @click.native="modify()">Modificar</v-btn>
            </v-toolbar-items>
          </v-toolbar>
          <v-card-text>
            <v-list three-line subheader>
              <v-subheader>Susbtitut</v-subheader>
              <v-list-tile avatar>
                <v-list-tile-content style="display:inline;" v-if="currentSubstitute">
                    <v-avatar color="grey lighten-4" :size="40">
                        <img :src="'/user/' + currentSubstitute.hash_id + '/photo'"
                             :alt="currentSubstitute.description + ' | Inici:' + currentSubstitute.start_at + ' - Fí:' +  currentSubstitute.end_at"
                             :title="currentSubstitute.description + ' | Inici:' + currentSubstitute.start_at + ' - Fí:' + currentSubstitute.end_at ">
                    </v-avatar>
                    <v-list-tile-title>{{currentSubstitute.description}}</v-list-tile-title>
                    <v-list-tile-sub-title>Substitut</v-list-tile-sub-title>
                </v-list-tile-content>
            </v-list-tile>
              <v-list-tile>
                    <v-list-tile-content>
                        <date-picker v-model="start_date" label="Data d'inici" name="start_date"></date-picker>
                    </v-list-tile-content>
              </v-list-tile>
                <v-list-tile>
                    <v-list-tile-content>
                        <date-picker v-model="end_date" label="Data fí" name="end_date"></date-picker>
                    </v-list-tile-content>
              </v-list-tile>
            </v-list>
            <v-divider></v-divider>
            <v-list three-line subheader>
              <v-subheader>Plaça que ocupa</v-subheader>
              <v-list-tile>
                        <v-list-tile-content>
                            <v-list-tile-title>Especialitat, família, codi i notes</v-list-tile-title>
                            <v-list-tile-sub-title> {{ job.specialty_description }} | {{ job.family_description }} | {{ job.fullcode }} | {{ job.notes }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
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
              <v-list-tile avatar>
                    <v-list-tile-content>
                        <template v-if="job.active_user_hash_id">
                            <v-avatar color="grey lighten-4" :size="40">
                                <img :src="'/user/' + job.active_user_hash_id + '/photo'"
                                     :alt="job.active_user_description"
                                     :title="job.active_user_description">
                            </v-avatar>
                        </template>
                        <v-list-tile-title>{{ job.active_user_description }}</v-list-tile-title>
                        <v-list-tile-sub-title>Usuari actiu</v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile avatar>
                        <v-list-tile-content style="display:inline;">
                            <v-avatar color="grey lighten-4" :size="40" v-for="substitute in job.substitutes" :key="substitute.id" @dblclick="showSubstituteDialog(substitute)">
                                <img :src="'/user/' + substitute.hash_id + '/photo'"
                                     :alt="substitute.description + ' | Inici:' + substitute.start_at + ' - Fí:' +  substitute.end_at"
                                     :title="substitute.description + ' | Inici:' + substitute.start_at + ' - Fí:' + substitute.end_at ">
                            </v-avatar>
                            <v-list-tile-title>{{ substitutesNames() }}</v-list-tile-title>
                            <v-list-tile-sub-title>Substituts</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
            </v-list>
          </v-card-text>
          <div style="flex: 1 1 auto;"></div>
            <v-card-actions>
                <v-btn color="success" @click.native="finishSubstitution()" :disabled="disableFinishSubstitutionButton()">Finalitzar Subtitució</v-btn>
                <v-btn color="primary" @click.native="modify()">Modificar</v-btn>
                <v-btn flat @click="dialog=false">Sortir</v-btn>
            </v-card-actions>
        </v-card>
      </v-dialog>
        <v-avatar color="grey lighten-4" :size="40" v-for="substitute in job.substitutes" :key="substitute.id" @dblclick="showSubstituteDialog(substitute)">
            <img :src="'/user/' + substitute.hash_id + '/photo'"
                 :alt="substitute.description + ' | Inici:' + substitute.start_at + ' - Fí:' +  substitute.end_at"
                 :title="substitute.description + ' | Inici:' + substitute.start_at + ' - Fí:' + substitute.end_at ">
        </v-avatar>
    </span>
</template>

<script>
  import DatePicker from '../ui/DatePicker'
  import moment from 'moment'

  export default {
    name: 'SubstituteAvatarsComponent',
    components: {
      'date-picker': DatePicker
    },
    data () {
      return {
        dialog: false,
        currentSubstitute: null,
        start_date: null,
        end_date: null
      }
    },
    props: {
      job: {
        required: true
      }
    },
    watch: {
      currentSubstitute (newValue) {
        if (newValue) {
          this.start_date = moment(newValue.start_at).format('YYYY-MM-DD')
          this.end_date = newValue.end_at
        }
      }
    },
    methods: {
      disableFinishSubstitutionButton () {
        if (this.currentSubstitute && this.currentSubstitute.end_at !== null) return true
        return false
      },
      finishSubstitution () {
        console.log('TODO finishSubstitution')
      },
      modify () {
        console.log('TODO modify')
      },
      showSubstituteDialog (substitute) {
        this.currentSubstitute = substitute
        this.dialog = true
      },
      substitutesNames () {
        return this.job.substitutes.map(substitute => substitute.name).join(', ')
      }
    }
  }
</script>
