<template>
    <span style="display:inline-block">
        <v-btn v-if="icon" icon class="mx-0" title="Vegeu la fitxa del professor" @click.native.stop="prepareDialog">
            <v-icon color="primary">visibility</v-icon>
        </v-btn>
        <v-btn v-else class="mx-0" title="Vegeu la fitxa del professor" @click.native.stop="prepareDialog">
            <v-icon color="primary">visibility</v-icon> Fitxa del professor
        </v-btn>
        <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" @keydown.esc="dialog = false">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click.native="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Fitxa del professor</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click.native="dialog = false">Imprimir</v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-container grid-list-lg fluid>
                    <v-layout row wrap>
                        <v-flex xs6>
                            <v-card>
                                <v-container grid-list-xs fluid>
                                    <v-layout row wrap>
                                        <v-flex xs4>
                                            <v-container grid-list-xs text-xs-center fluid>
                                                <v-layout row wrap>
                                                    <v-flex xs12>
                                                        <v-avatar
                                                                size="120"
                                                                color="grey lighten-4"
                                                        >
                                                            <img :src="photoSrc()" alt="avatar">
                                                        </v-avatar>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <span class="title" v-html="internalTeacher && internalTeacher.user && internalTeacher.user.name"></span>
                                                        <span class="subtitle" v-html="internalTeacher && internalTeacher.user && internalTeacher.user.email"></span>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-list two-line>
                                                            <v-list-tile>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title v-html="internalTeacher && internalTeacher.user && internalTeacher.user.person && internalTeacher.user.person.givenName"></v-list-tile-title>
                                                                    <v-list-tile-sub-title>Nom</v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                            <v-divider></v-divider>
                                                            <v-list-tile>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title v-html="internalTeacher && internalTeacher.user && internalTeacher.user.person && internalTeacher.user.person.sn1"></v-list-tile-title>
                                                                    <v-list-tile-sub-title>1r Cognom</v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                            <v-divider></v-divider>
                                                            <v-list-tile>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title v-html="internalTeacher && internalTeacher.user && internalTeacher.user.person && internalTeacher.user.person.sn2"></v-list-tile-title>
                                                                    <v-list-tile-sub-title>2n Cognom</v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                        </v-list>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-flex>
                                        <v-flex xs8>
                                            <v-container grid-list-xs fluid>
                                                <v-layout row wrap>
                                                    <v-flex xs6 >
                                                        <h1 class="title primary--text">
                                                            <div>
                                                                <p>Dades personals</p>
                                                            </div>

                                                        </h1>
                                                    </v-flex>
                                                    <v-flex xs6 >
                                                        <h1 class="title primary--text">
                                                            <div>
                                                                <p>Adreça</p>
                                                            </div>
                                                        </h1>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-container grid-list-xs fluid>
                                                            <v-layout row wrap>
                                                                <v-flex xs6>
                                                                    <v-list two-line>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="identifier()"></v-list-tile-title>
                                                                                <v-list-tile-sub-title v-html="identifierType()"></v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher && internalTeacher.user && internalTeacher.user.person && internalTeacher.user.person.birthdate"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Data de naixement</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher && internalTeacher.user && internalTeacher.user.person && internalTeacher.user.person.birthplace && internalTeacher.user.person.birthplace.name"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Lloc de naixement</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher && internalTeacher.user && internalTeacher.user.person && internalTeacher.user.person.gender"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Sexe</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher && internalTeacher.user && internalTeacher.user.person && internalTeacher.user.person.civil_status"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Estat civil</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                    </v-list>
                                                                </v-flex>
                                                                <v-flex xs6>
                                                                    <v-list two-line>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="address()"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Adreça</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="postalCode()"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Codi Postal</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="location()"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Població</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="province()"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Província</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <!--TODO-->
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title>Catalunya</v-list-tile-title>
                                                                                <v-list-tile-sub-title>País</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                    </v-list>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-container>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-flex>
                                    </v-layout>
                                </v-container>

                            </v-card>
                        </v-flex>
                        <v-flex xs6>
                            <v-card>
                                <v-container grid-list-xs fluid>
                                    <v-layout row wrap>
                                        <v-flex xs12>
                                            <v-container grid-list-xs fluid>
                                                <v-layout row wrap>
                                                    <v-flex xs16 >
                                                        <h1 class="title primary--text">
                                                            <div>
                                                                <p>Contacte</p>
                                                            </div>

                                                        </h1>
                                                    </v-flex>
                                                    <v-flex xs16 >
                                                        <h1 class="title primary--text">
                                                            <div>
                                                                <p>Dades professionals</p>
                                                            </div>

                                                        </h1>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-container grid-list-xs fluid>
                                                            <v-layout row wrap>
                                                                <v-flex xs6>
                                                                    <v-list two-line>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher && internalTeacher.user && internalTeacher.user.person && internalTeacher.user.person.email"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Email personal</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="other_emails()"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Altres emails</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher && internalTeacher.user && internalTeacher.user.person && internalTeacher.user.person.mobile"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Mòbil</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher && internalTeacher.user && internalTeacher.user.person && internalTeacher.user.person.phone"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Telèfon fix</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="other_phones()"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Altres telèfons</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                    </v-list>
                                                                </v-flex>
                                                                <v-flex xs6>
                                                                    <v-list two-line>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.code"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Codi</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="specialty()"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Especialitat</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="family()"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Familia</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="force()"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Cos</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="status()"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Estat administratiu</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>

                                                                    </v-list>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-container>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-flex>
                                    </v-layout>
                                </v-container>

                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-container grid-list-lg fluid>
                    <v-layout row wrap >
                        <v-flex xs12>
                            <v-card>
                                <v-container grid-list-xs fluid>
                                    <v-layout row wrap>
                                        <v-flex xs12>
                                            <v-container grid-list-xs fluid>
                                                <v-layout row wrap>
                                                    <v-flex xs4 >
                                                        <h1 class="title primary--text">
                                                            <div>
                                                                <p>Plaça i càrrecs</p>
                                                            </div>
                                                        </h1>
                                                    </v-flex>
                                                    <v-flex xs4 >
                                                        <h1 class="title primary--text">
                                                            <div>
                                                                <p>Formació</p>
                                                            </div>
                                                        </h1>
                                                    </v-flex>
                                                    <v-flex xs4 >
                                                        <h1 class="title primary--text">
                                                            <div>
                                                                <p>Altres dades</p>
                                                            </div>
                                                        </h1>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-container grid-list-xs fluid>
                                                            <v-layout row wrap>
                                                                <v-flex xs4>
                                                                    <v-list two-line>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title>
                                                                                    <span :title="jobDescription()">{{ job() }}</span>
                                                                                </v-list-tile-title>
                                                                                <v-list-tile-sub-title>Codi plaça</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="jobFamily()"></v-list-tile-title>
                                                                                <v-list-tile-sub-title >Família</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="jobSpecialty()"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Especialitat</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="jobOrder()"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Ordre</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <!--TODO-->
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title>TODO</v-list-tile-title>
                                                                                <v-list-tile-sub-title>Càrrecs</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                    </v-list>
                                                                </v-flex>
                                                                <v-flex xs4>
                                                                    <v-list two-line>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.titulacio_acces"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Titulació d'accés</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.altres_titulacions"></v-list-tile-title>
                                                                                <v-list-tile-sub-title >Altres titulacions</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.idiomes"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Idiomes</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.perfil_professional"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Pèrfils professionals</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.altres_formacions"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Altres formacions</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                    </v-list>
                                                                </v-flex>
                                                                <v-flex xs4>
                                                                    <v-list two-line>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.data_superacio_oposicions"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Data Superació oposicions</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.lloc_destinacio_definitiva"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Lloc destinació definitiva</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.data_inici_treball"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Any inici serveis ensenyament</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher && internalTeacher.user && internalTeacher.user.person && internalTeacher.user.person.notes"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Notes</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                        <v-divider></v-divider>
                                                                        <v-list-tile>
                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title v-html="internalTeacher.data_incorporacio_centre"></v-list-tile-title>
                                                                                <v-list-tile-sub-title>Data incorporació centre</v-list-tile-sub-title>
                                                                            </v-list-tile-content>
                                                                        </v-list-tile>
                                                                    </v-list>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-container>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-flex>
                                    </v-layout>
                                </v-container>

                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card>
        </v-dialog>
    </span>
</template>

<style>

</style>

<script>
  import axios from 'axios'

  export default {
    data () {
      return {
        dialog: false,
        internalTeacher: this.teacher
      }
    },
    props: {
      teacher: {
        type: Object,
        default: () => { return {} }
      },
      teachers: {
        type: Array,
        default: () => []
      },
      icon: {
        type: Boolean,
        default: true
      }
    },
    methods: {
      photoSrc () {
        if (this.internalTeacher && this.internalTeacher.user) {
          return '/user/' + this.internalTeacher.user.hashid + '/photo'
        }
        return '/img/GravatarPlaceholder.svg'
      },
      prepareDialog () {
        if (! _.isEmpty(this.internalTeacher) && this.teachers.length > 0) {
          this.dialog = true
          return
        }
        axios.get('/api/v1/teacher').then(response => {
          this.internalTeacher = response.data
          this.dialog = true
        }).catch(error => {
          console.log(error)
        })
      },
      identifierType () {
        if (this.internalTeacher && this.internalTeacher.user && this.internalTeacher.user.person && this.internalTeacher.user.person.identifier && this.internalTeacher.user.person.identifier.type) {
          return this.internalTeacher.user.person.identifier.type.name
        }
        return 'NIF'
      },
      identifier () {
        if (this.internalTeacher && this.internalTeacher.user && this.internalTeacher.user.person && this.internalTeacher.user.person.identifier) {
          return this.internalTeacher.user.person.identifier.value
        }
      },
      address () {
        if (this.internalTeacher && this.internalTeacher.user && this.internalTeacher.user.person && this.internalTeacher.user.person.address) {
          const address = this.internalTeacher.user.person.address
          return address.name + ' ' + address.number + ' ' + address.floor + ' ' + address.floor_number
        }
      },
      postalCode () {
        if (this.internalTeacher && this.internalTeacher.user && this.internalTeacher.user.person && this.internalTeacher.user.person.address && this.internalTeacher.user.person.address.location) {
          return this.internalTeacher.user.person.address.location.postalcode
        }
      },
      location () {
        if (this.internalTeacher && this.internalTeacher.user && this.internalTeacher.user.person && this.internalTeacher.user.person.address && this.internalTeacher.user.person.address.location) {
          return this.internalTeacher.user.person.address.location.name
        }
      },
      specialty () {
        if (this.internalTeacher && this.internalTeacher.specialty) {
          return this.internalTeacher.specialty.code + ' ' + this.internalTeacher.specialty.name
        }
      },
      family () {
        if (this.internalTeacher && this.internalTeacher.specialty && this.internalTeacher.specialty.family) {
          return this.internalTeacher.specialty.family.code + ' ' + this.internalTeacher.specialty.family.name
        }
      },
      force () {
        if (this.internalTeacher && this.internalTeacher.specialty && this.internalTeacher.specialty.force) {
          return this.internalTeacher.specialty.force.name
        }
      },
      status () {
        if (this.internalTeacher && this.internalTeacher.administrative_status) {
          return this.internalTeacher.administrative_status.name
        }
      },
      province () {
        if (this.internalTeacher && this.internalTeacher.user && this.internalTeacher.user.person && this.internalTeacher.user.person.address && this.internalTeacher.user.person.address.province) {
          return this.internalTeacher.user.person.address.province.name
        }
      },
      other_emails () {
        if (this.internalTeacher && this.internalTeacher.user && this.internalTeacher.user.person && this.internalTeacher.user.person.other_emails) {
          return JSON.parse(this.internalTeacher.user.person.other_emails).join()
        }
      },
      other_phones () {
        let result = ''
        if (this.internalTeacher && this.internalTeacher.user && this.internalTeacher.user.person && this.internalTeacher.user.person.other_phones) {
          result = JSON.parse(this.internalTeacher.user.person.other_phones).join()
        }
        if (this.internalTeacher && this.internalTeacher.user && this.internalTeacher.user.person && this.internalTeacher.user.person.other_mobiles) {
          result = result + ' ' + JSON.parse(this.internalTeacher.user.person.other_mobiles).join()
        }
        return result
      },
      job () {
        if (this.internalTeacher && this.internalTeacher.user) {
          return this.internalTeacher.user.jobs[0].family.code + '_' + this.internalTeacher.user.jobs[0].specialty.code + '_' + this.internalTeacher.user.jobs[0].order + '_' + this.internalTeacher.user.jobs[0].code
        }
      },
      jobDescription () {
        if (this.internalTeacher && this.internalTeacher.user) {
          return 'Plaça num ' + this.internalTeacher.user.jobs[0].order + ' de la família ' + this.internalTeacher.user.jobs[0].family.name + ', especialitat ' + this.internalTeacher.user.jobs[0].specialty.name + ', assignada al professor ' + this.teacherDescription(this.internalTeacher.user.jobs[0].code)
        }
      },
      jobFamily () {
        if (this.internalTeacher && this.internalTeacher.user) {
          return this.internalTeacher.user.jobs[0].family.name
        }
      },
      jobSpecialty () {
        if (this.internalTeacher && this.internalTeacher.user) {
          return this.internalTeacher.user.jobs[0].specialty.name
        }
      },
      jobOrder () {
        if (this.internalTeacher && this.internalTeacher.user) {
          return this.internalTeacher.user.jobs[0].order
        }
      },
      teacherDescription (teacherCode) {
        if (this.internalTeacher && this.internalTeacher.user) {
          let teacher = this.teachers.find(teacher => { return teacher.code === teacherCode })
          return teacher && teacher.user.name + ' (' + teacher.code + ')'
        }
      }
    }
  }
</script>
