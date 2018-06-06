<template>
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" >
        <v-btn slot="activator" icon class="mx-0">
            <v-icon color="primary">visibility</v-icon>
        </v-btn>
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click.native="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Fitxa del professor</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark flat @click.native="dialog = false">
                        <v-icon>add</v-icon> Crear nou professor
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text class="px-0 mb-2">
                <v-container fluid grid-list-md text-xs-center>
                    <v-layout row wrap>
                        <v-flex xs12>

                            <pending-teacher-form
                                    :specialties="specialties"
                                    :forces="forces"
                                    :administrative-statuses="administrativeStatuses"
                                    :teachers="teachers"
                                    :pendingTeacher="pendingTeacher"></pending-teacher-form>

                            <v-card>
                                <v-card-title class="blue darken-3 white--text">Plaça assignar i dades usuari</v-card-title>
                                <v-card-text class="px-0 mb-2">
                                    Substitut? condicions:
                                    - Situació administrativa és substitut
                                    - S'ha de marcar professor que substitueix
                                    Interí:
                                    - Situació administrativa és Interí
                                    Funcionari:
                                    -
                                    TODO: mostrar dades plaça assignar (segons professor substitueix, o segons si és nou professor interí )

                                    MOSTRAR TAMBÉ DADES USUARI -> Quin usuari es proposa segons el seu nom +@iesebre.com -> Possibilitat de canviar
                                    però sempre controlant que ningú ja tingui assignat
                                </v-card-text>
                            </v-card>


                            <v-btn @click.native="dialog = false">
                                <v-icon>close</v-icon> Sortir
                            </v-btn>
                            <v-btn black color="green" @click="createTeacher" class="white--text">
                                <v-icon>add</v-icon> Crear nou professor
                            </v-btn>

                        </v-flex>
                    </v-layout>
                </v-container>

            </v-card-text>
        </v-card>
    </v-dialog>

</template>

<style>

</style>

<script>
  import { validationMixin } from 'vuelidate'
  import PendingTeacher from './Mixins/PendingTeacher'
  import TeacherSelect from './TeacherSelectComponent.vue'

  export default {
    components: { TeacherSelect },
    mixins: [validationMixin, PendingTeacher],
    data () {
      return {
        dialog: false
      }
    },
    props: {
      pendingTeacher: {
        type: Object,
        required: true
      },
      teachers: {
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
      createTeacher (teacher) {
        console.log('TODO create teacher')
      }
    }
  }
</script>
