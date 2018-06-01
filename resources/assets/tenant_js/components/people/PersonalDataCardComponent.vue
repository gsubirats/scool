<template>
    <v-card>
        <v-container grid-list-xs>
            <v-layout row wrap>
                <v-flex xs2>
                    <v-container grid-list-xs text-xs-center>
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
                                <span class="title" v-html="internalUser.name"></span>
                                <span class="subtitle" v-html="internalUser.email"></span>
                            </v-flex>
                            <v-flex xs12>
                                <v-list two-line>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title v-html="internalUser.person && internalUser.person.givenName"></v-list-tile-title>
                                            <v-list-tile-sub-title>Nom</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title v-html="internalUser.person && internalUser.person.sn1"></v-list-tile-title>
                                            <v-list-tile-sub-title>1r Cognom</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title v-html="internalUser.person && internalUser.person.sn2"></v-list-tile-title>
                                            <v-list-tile-sub-title>2n Cognom</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-flex>
                <v-flex xs10>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-flex xs3 >
                                <h1 class="title primary--text">
                                    <div>
                                        <p>Dades personals</p>
                                    </div>

                                </h1>
                            </v-flex>
                            <v-flex xs4 >
                                <h1 class="title primary--text">
                                    <div>
                                        <p>Adreça</p>
                                    </div>
                                </h1>
                            </v-flex>
                            <v-flex xs5 >
                                <h1 class="title primary--text">
                                    <div>
                                        <p>Contacte</p>
                                    </div>
                                </h1>
                            </v-flex>
                            <v-flex xs12>
                                <v-container grid-list-xs>
                                    <v-layout row wrap>
                                        <v-flex xs3>
                                            <v-list two-line>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="identifier()"></v-list-tile-title>
                                                        <v-list-tile-sub-title v-html="identifierType()"></v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile v-if="hasTIS()">
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="TIS()"></v-list-tile-title>
                                                        <v-list-tile-sub-title>TIS</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="birthdate()"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Data de naixement</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.person && internalUser.person.gender"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Sexe</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile v-if="hasBirthplace()">
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="birthplace()"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Lloc de naixement</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile v-if="hasCivilStatus()">
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="civil_status()"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Estat civil</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                            </v-list>
                                        </v-flex>
                                        <v-flex xs4>
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
                                        <v-flex xs5>
                                            <v-list two-line>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.person && internalUser.person.email"></v-list-tile-title>
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
                                                        <v-list-tile-title v-html="internalUser.person && internalUser.person.mobile"></v-list-tile-title>
                                                        <v-list-tile-sub-title>Mòbil</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="internalUser.person && internalUser.person.phone"></v-list-tile-title>
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
                                    </v-layout>
                                </v-container>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-flex>
            </v-layout>
        </v-container>

    </v-card>

</template>

<script>
  export default {
    name: 'PersonalDataCardComponent',
    data () {
      return {
        internalUser: this.user
      }
    },
    props: {
      user: {
        type: Object,
        required: true
      }
    },
    watch: {
      user: function (newUser) {
        console.log('User changed to ' + newUser.id)
        this.internalUser = this.user
      }
    },
    methods: {
      formatDate (date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${day}/${month}/${year}`
      },
      birthdate () {
        if (this.internalUser.person && this.internalUser.person.birthdate) {
          return this.formatDate(this.internalUser.person.birthdate)
        }
        return ''
      },
      photoSrc () {
        if (this.internalUser && this.internalUser.hashid) {
          return '/user/' + this.internalUser.hashid + '/photo'
        }
        return '/img/GravatarPlaceholder.svg'
      },
      identifierType () {
        if (this.internalUser && this.internalUser.person && this.internalUser.person.identifier && this.internalUser.person.identifier.type) {
          return this.internalUser.person.identifier.type.name
        }
        return 'NIF'
      },
      identifier () {
        if (this.internalUser && this.internalUser.person && this.internalUser.person.identifier) {
          return this.internalUser.person.identifier.value
        }
      },
      address () {
        if (this.internalUser && this.internalUser && this.internalUser.person && this.internalUser.person.address) {
          const address = this.internalUser.person.address
          return address.name + ' ' + address.number + ' ' + address.floor + ' ' + address.floor_number
        }
      },
      postalCode () {
        if (this.internalUser && this.internalUser.person && this.internalUser.person.address && this.internalUser.person.address.location) {
          return this.internalUser.person.address.location.postalcode
        }
      },
      location () {
        if (this.internalUser && this.internalUser.person && this.internalUser.person.address && this.internalUser.person.address.location) {
          return this.internalUser.person.address.location.name
        }
      },
      province () {
        if (this.internalUser && this.internalUser.person && this.internalUser.person.address && this.internalUser.person.address.province) {
          return this.internalUser.person.address.province.name
        }
      },
      other_emails () {
        if (this.internalUser && this.internalUser.person && this.internalUser.person.other_emails) {
          return JSON.parse(this.internalUser.person.other_emails).join()
        }
      },
      other_phones () {
        let result = ''
        if (this.internalUser && this.internalUser.person && this.internalUser.person.other_phones) {
          result = JSON.parse(this.internalUser.person.other_phones).join()
        }
        if (this.internalUser && this.internalUser.person && this.internalUser.person.other_mobiles) {
          result = result + ' ' + JSON.parse(this.internalUser.person.other_mobiles).join()
        }
        return result
      },
      hasTIS () {
        return this.internalUser && this.internalUser.person && this.internalUser.person.tis
      },
      TIS () {
        if (this.hasTIS()) {
          return this.internalUser.person.tis
        }
      },
      hasBirthplace () {
        return this.internalUser.person && this.internalUser.person.birthplace
      },
      birthplace () {
        if (this.hasBirthplace()) return this.internalUser.person && this.internalUser.person.birthplace && this.internalUser.person.birthplace.name
      },
      hasCivilStatus () {
        return this.internalUser.person && this.internalUser.person.civil_status
      },
      civil_status () {
        if (this.hasCivilStatus()) {
          return this.internalUser.person && this.internalUser.person.civil_status
        }
      }
    }
  }
</script>

<style scoped>

</style>
