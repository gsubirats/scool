<template>
    <v-card>
        <v-toolbar dark color="primary">
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
                                                    <img :src="'/user/' + teacher.hashid + '/photo'" alt="avatar">
                                                </v-avatar>
                                            </v-flex>
                                            <v-flex xs12>
                                                <span class="title" v-html="teacher.name"></span>
                                                <span class="subtitle" v-html="teacher.email"></span>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-list two-line>
                                                    <v-list-tile>
                                                        <v-list-tile-content>
                                                            <v-list-tile-title v-html="teacher.person && teacher.person.givenName"></v-list-tile-title>
                                                            <v-list-tile-sub-title>Nom</v-list-tile-sub-title>
                                                        </v-list-tile-content>
                                                    </v-list-tile>
                                                    <v-divider></v-divider>
                                                    <v-list-tile>
                                                        <v-list-tile-content>
                                                            <v-list-tile-title v-html="teacher.person && teacher.person.sn1"></v-list-tile-title>
                                                            <v-list-tile-sub-title>1r Cognom</v-list-tile-sub-title>
                                                        </v-list-tile-content>
                                                    </v-list-tile>
                                                    <v-divider></v-divider>
                                                    <v-list-tile>
                                                        <v-list-tile-content>
                                                            <v-list-tile-title v-html="teacher.person && teacher.person.sn2"></v-list-tile-title>
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
                                                                        <v-list-tile-title v-html="teacher.person && teacher.person.birthdate"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Data de naixement</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.person && teacher.person.birthplace && teacher.person.birthplace.name"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Lloc de naixement</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.person && teacher.person.gender"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Sexe</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.person && teacher.person.civil_status"></v-list-tile-title>
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
                                                                        <v-list-tile-title v-html="teacher.person && teacher.person.email"></v-list-tile-title>
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
                                                                        <v-list-tile-title v-html="teacher.person && teacher.person.mobile"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Mòbil</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.person && teacher.person.phone"></v-list-tile-title>
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
                                                                        <v-list-tile-title v-html="teacher.teacher.code"></v-list-tile-title>
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
                                                <v-container grid-list-xs>
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
                                                                        <v-list-tile-title v-html="teacher.teacher.titulacio_acces"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Titulació d'accés</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.altres_titulacions"></v-list-tile-title>
                                                                        <v-list-tile-sub-title >Altres titulacions</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.idiomes"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Idiomes</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.perfil_professional"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Pèrfils professionals</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.altres_formacions"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Altres formacions</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                            </v-list>
                                                        </v-flex>
                                                        <v-flex xs4>
                                                            <v-list two-line>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.data_superacio_oposicions"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Data Superació oposicions</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.lloc_destinacio_definitiva"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Lloc destinació definitiva</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.data_inici_treball"></v-list-tile-title>
                                                                        <v-list-tile-sub-title>Any inici serveis ensenyament</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.person && teacher.person.notes">---</v-list-tile-title>
                                                                        <v-list-tile-sub-title>Notes</v-list-tile-sub-title>
                                                                    </v-list-tile-content>
                                                                </v-list-tile>
                                                                <v-divider></v-divider>
                                                                <v-list-tile>
                                                                    <v-list-tile-content>
                                                                        <v-list-tile-title v-html="teacher.teacher.data_incorporacio_centre"></v-list-tile-title>
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
</template>

<style>

</style>

<script>
  export default {
    data () {
      return {
        data: 'example'
      }
    },
    props: {
      teacher: {
        type: Object,
        required: true
      },
      teachers: {
        type: Array,
        required: true
      }
    },
    methods: {
      identifierType () {
        if (this.teacher && this.teacher && this.teacher.person && this.teacher.person.identifier && this.teacher.person.identifier.type) {
          return this.teacher.person.identifier.type.name
        }
        return 'NIF'
      },
      identifier () {
        if (this.teacher && this.teacher && this.teacher.person && this.teacher.person.identifier) {
          return this.teacher.person.identifier.value
        }
      },
      address () {
        if (this.teacher && this.teacher && this.teacher.person && this.teacher.person.address) {
          const address = this.teacher.person.address
          return address.name + ' ' + address.number + ' ' + address.floor + ' ' + address.floor_number
        }
      },
      postalCode () {
        if (this.teacher && this.teacher && this.teacher.person && this.teacher.person.address && this.teacher.person.address.location) {
          return this.teacher.person.address.location.postalcode
        }
      },
      location () {
        if (this.teacher && this.teacher && this.teacher.person && this.teacher.person.address && this.teacher.person.address.location) {
          return this.teacher.person.address.location.name
        }
      },
      specialty () {
        if (this.teacher && this.teacher.teacher && this.teacher.teacher.specialty) {
          return this.teacher.teacher.specialty.code + ' ' + this.teacher.teacher.specialty.name
        }
      },
      family () {
        if (this.teacher && this.teacher.teacher && this.teacher.teacher.specialty && this.teacher.teacher.specialty.family) {
          return this.teacher.teacher.specialty.family.code + ' ' + this.teacher.teacher.specialty.family.name
        }
      },
      force () {
        if (this.teacher && this.teacher.teacher && this.teacher.teacher.specialty && this.teacher.teacher.specialty.force) {
          return this.teacher.teacher.specialty.force.name
        }
      },
      status () {
        if (this.teacher && this.teacher.teacher && this.teacher.teacher.administrative_status) {
          return this.teacher.teacher.administrative_status.name
        }
      },
      province () {
        if (this.teacher && this.teacher && this.teacher.person && this.teacher.person.address && this.teacher.person.address.province) {
          return this.teacher.person.address.province.name
        }
      },
      other_emails () {
        if (this.teacher && this.teacher && this.teacher.person && this.teacher.person.other_emails) {
          return JSON.parse(this.teacher.person.other_emails).join()
        }
      },
      other_phones () {
        let result = ''
        if (this.teacher && this.teacher && this.teacher.person && this.teacher.person.other_phones) {
          result = JSON.parse(this.teacher.person.other_phones).join()
        }
        if (this.teacher && this.teacher && this.teacher.person && this.teacher.person.other_mobiles) {
          result = result + ' ' + JSON.parse(this.teacher.person.other_mobiles).join()
        }
        return result
      },
      job () {
        if (this.teacher.jobs[0]) {
          return this.teacher.jobs[0].family.code + '_' + this.teacher.jobs[0].specialty.code + '_' + this.teacher.jobs[0].order + '_' + this.teacher.jobs[0].code
        }
      },
      jobDescription () {
        if (this.teacher.jobs[0]) {
          return 'Plaça num ' + this.teacher.jobs[0].order + ' de la família ' + this.teacher.jobs[0].family.name + ', especialitat ' + this.teacher.jobs[0].specialty.name + ', assignada al professor ' + this.teacherDescription(this.teacher.jobs[0].code)
        }
        return ''
      },
      jobFamily () {
        if (this.teacher.jobs[0]) {
          return this.teacher.jobs[0].family.name
        }
        return ''
      },
      jobSpecialty () {
        if (this.teacher.jobs[0]) {
          return this.teacher.jobs[0].specialty.name
        }
        return ''
      },
      jobOrder () {
        if (this.teacher.jobs[0]) {
          return this.teacher.jobs[0].order
        }
        return ''
      },
      teacherDescription (teacherCode) {
        let teacher = this.teachers.find(teacher => { return teacher.code === teacherCode })
        return teacher.name + ' (' + teacher.code + ')'
      }
    }
  }
</script>
