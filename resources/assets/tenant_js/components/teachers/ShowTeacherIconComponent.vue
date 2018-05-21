<template>
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-btn slot="activator" icon class="mx-0" title="Vegeu la fitxa del professor">
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
                    <v-btn dark flat @click.native="dialog = false">Imprimir</v-btn>
                </v-toolbar-items>
            </v-toolbar>

            <v-container grid-list-lg fluid>
                <v-layout row wrap>
                    <v-flex xs6>
                        <v-card>
                            <v-container grid-list-xs>
                                <v-layout row wrap>
                                    <v-flex xs4>
                                        <v-container grid-list-xs text-xs-center>
                                            <v-layout row wrap>
                                                <v-flex xs12>
                                                    <v-avatar
                                                            size="120"
                                                            color="grey lighten-4"
                                                    >
                                                        <img :src="'/user/' + teacher.user.hashid + '/photo'" alt="avatar">
                                                    </v-avatar>
                                                </v-flex>
                                                <v-flex xs12>
                                                    <span class="title" v-html="teacher.user.name"></span>
                                                    <span class="subtitle" v-html="teacher.user.email"></span>
                                                </v-flex>
                                                <v-flex xs12>
                                                    <v-list two-line>
                                                        <v-list-tile>
                                                            <v-list-tile-content>
                                                                <v-list-tile-title v-html="teacher.user.person && teacher.user.person.givenName"></v-list-tile-title>
                                                                <v-list-tile-sub-title>Nom</v-list-tile-sub-title>
                                                            </v-list-tile-content>
                                                        </v-list-tile>
                                                        <v-divider></v-divider>
                                                        <v-list-tile>
                                                            <v-list-tile-content>
                                                                <v-list-tile-title v-html="teacher.user.person && teacher.user.person.sn1"></v-list-tile-title>
                                                                <v-list-tile-sub-title>1r Cognom</v-list-tile-sub-title>
                                                            </v-list-tile-content>
                                                        </v-list-tile>
                                                        <v-divider></v-divider>
                                                        <v-list-tile>
                                                            <v-list-tile-content>
                                                                <v-list-tile-title v-html="teacher.user.person && teacher.user.person.sn2"></v-list-tile-title>
                                                                <v-list-tile-sub-title>2n Cognom</v-list-tile-sub-title>
                                                            </v-list-tile-content>
                                                        </v-list-tile>
                                                    </v-list>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-flex>
                                    <v-flex xs8>
                                        <v-container grid-list-xs>
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
                                                    <v-container grid-list-xs>
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
                                                                            <v-list-tile-title v-html="teacher.user.person && teacher.user.person.birthdate"></v-list-tile-title>
                                                                            <v-list-tile-sub-title>Data de naixement</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.user.person && teacher.user.person.birthplace && teacher.user.person.birthplace.name"></v-list-tile-title>
                                                                            <v-list-tile-sub-title>Lloc de naixement</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.user.person && teacher.user.person.gender"></v-list-tile-title>
                                                                            <v-list-tile-sub-title>Sexe</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.user.person && teacher.user.person.civil_status"></v-list-tile-title>
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
                            <v-container grid-list-xs>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-container grid-list-xs>
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
                                                    <v-container grid-list-xs>
                                                        <v-layout row wrap>
                                                            <v-flex xs6>
                                                                <v-list two-line>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.user.person && teacher.user.person.email"></v-list-tile-title>
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
                                                                            <v-list-tile-title v-html="teacher.user.person && teacher.user.person.mobile"></v-list-tile-title>
                                                                            <v-list-tile-sub-title>Mòbil</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.user.person && teacher.user.person.phone"></v-list-tile-title>
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
                                                                            <v-list-tile-title v-html="teacher.code"></v-list-tile-title>
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
                            <v-container grid-list-xs>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-container grid-list-xs>
                                            <v-layout row wrap>
                                                <v-flex xs6 >
                                                    <h1 class="title primary--text">
                                                        <div>
                                                            <p>Formació</p>
                                                        </div>
                                                    </h1>
                                                </v-flex>
                                                <v-flex xs6 >
                                                    <h1 class="title primary--text">
                                                        <div>
                                                            <p>Altres dades</p>
                                                        </div>
                                                    </h1>
                                                </v-flex>
                                                <v-flex xs12>
                                                    <v-container grid-list-xs>
                                                        <v-layout row wrap>
                                                            <v-flex xs6>
                                                                <v-list two-line>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.titulacio_acces"></v-list-tile-title>
                                                                            <v-list-tile-sub-title>Titulació d'accés</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.altres_titulacions"></v-list-tile-title>
                                                                            <v-list-tile-sub-title >Altres titulacions</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.idiomes"></v-list-tile-title>
                                                                            <v-list-tile-sub-title>Idiomes</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.perfils"></v-list-tile-title>
                                                                            <v-list-tile-sub-title>Pèrfils professionals</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.altres_formacions"></v-list-tile-title>
                                                                            <v-list-tile-sub-title>Altres formacions</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                </v-list>
                                                            </v-flex>
                                                            <v-flex xs6>
                                                                <v-list two-line>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.data_superacio_oposicions"></v-list-tile-title>
                                                                            <v-list-tile-sub-title>Data Superació oposicions</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.lloc_destinacio_definitiva"></v-list-tile-title>
                                                                            <v-list-tile-sub-title>Lloc destinació definitiva</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.data_inici_treball"></v-list-tile-title>
                                                                            <v-list-tile-sub-title>Any inici serveis ensenyament</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.user.person && teacher.user.person.notes">---</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Notes</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="teacher.data_incorporacio_centre"></v-list-tile-title>
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
</template>

<style>

</style>

<script>
  export default {
    data () {
      return {
        dialog: false
      }
    },
    props: {
      teacher: {
        type: Object,
        required: true
      }
    },
    methods: {
      identifierType () {
        if (this.teacher && this.teacher.user && this.teacher.user.person && this.teacher.user.person.identifier && this.teacher.user.person.identifier.type) {
          return this.teacher.user.person.identifier.type.name
        }
        return 'NIF'
      },
      identifier () {
        if (this.teacher && this.teacher.user && this.teacher.user.person && this.teacher.user.person.identifier) {
          return this.teacher.user.person.identifier.value
        }
      },
      address () {
        if (this.teacher && this.teacher.user && this.teacher.user.person && this.teacher.user.person.address) {
          const address = this.teacher.user.person.address
          console.log(address)
          return address.name + ' ' + address.number + ' ' + address.floor + ' ' + address.floor_number
        }
      },
      postalCode () {
        if (this.teacher && this.teacher.user && this.teacher.user.person && this.teacher.user.person.address && this.teacher.user.person.address.location) {
          return this.teacher.user.person.address.location.postalcode
        }
      },
      location () {
        if (this.teacher && this.teacher.user && this.teacher.user.person && this.teacher.user.person.address && this.teacher.user.person.address.location) {
          return this.teacher.user.person.address.location.name
        }
      },
      specialty () {
        if (this.teacher && this.teacher.specialty) {
          return this.teacher.specialty.code + ' ' + this.teacher.specialty.name
        }
      },
      family () {
        if (this.teacher && this.teacher.specialty && this.teacher.specialty.family) {
          return this.teacher.specialty.family.code + ' ' + this.teacher.specialty.family.name
        }
      },
      force () {
        if (this.teacher && this.teacher.specialty && this.teacher.specialty.force) {
          return this.teacher.specialty.force.name
        }
      },
      status () {
        if (this.teacher && this.teacher.administrative_status) {
          return this.teacher.administrative_status.name
        }
      },
      province () {
        if (this.teacher && this.teacher.user && this.teacher.user.person && this.teacher.user.person.address && this.teacher.user.person.address.province) {
          return this.teacher.user.person.address.province.name
        }
      },
      other_emails () {
        if (this.teacher && this.teacher.user && this.teacher.user.person && this.teacher.user.person.other_emails) {
          return JSON.parse(this.teacher.user.person.other_emails).join()
        }
      },
      other_phones () {
        let result = ''
        if (this.teacher && this.teacher.user && this.teacher.user.person && this.teacher.user.person.other_phones) {
          result = JSON.parse(this.teacher.user.person.other_phones).join()
        }
        if (this.teacher && this.teacher.user && this.teacher.user.person && this.teacher.user.person.other_mobiles) {
          result = result + ' ' + JSON.parse(this.teacher.user.person.other_mobiles).join()
        }
        return result
      }
    }
  }
</script>
