<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>

                <v-card>
                    <v-card-title class="blue darken-3 white--text">Fitxa del professorat</v-card-title>
                    <v-card-text class="px-0 mb-2">
                        <form>
                            <h1 class="subheading primary--text">
                                <div>
                                    <p>Dades personals</p>
                                </div>
                            </h1>
                            <v-container grid-list-md text-xs-center>
                                <v-layout row wrap>
                                    <v-flex md3>
                                        <v-text-field
                                                label="Nom"
                                                v-model="name"
                                                :error-messages="nameErrors"
                                                :counter="255"
                                                @input="$v.name.$touch()"
                                                @blur="$v.name.$touch()"
                                                required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md3>
                                        <v-text-field
                                                label="1r Cognom"
                                                v-model="sn1"
                                                :error-messages="sn1Errors"
                                                :counter="255"
                                                @input="$v.sn1.$touch()"
                                                @blur="$v.sn1.$touch()"
                                                required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md3>
                                        <v-text-field
                                                label="2n Cognom"
                                                v-model="sn2"
                                                :error-messages="sn2Errors"
                                                :counter="255"
                                                @input="$v.sn2.$touch()"
                                                @blur="$v.sn2.$touch()"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md1>
                                        <v-text-field
                                                label="DNI"
                                                v-model="identifier"
                                                :error-messages="identifierErrors"
                                                @input="$v.identifier.$touch()"
                                                @blur="$v.identifier.$touch()"
                                                required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md2>
                                        <v-menu
                                                ref="menu"
                                                lazy
                                                :close-on-content-click="false"
                                                v-model="birthdateMenu"
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                :nudge-right="40"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    label="Data de naixement"
                                                    v-model="birthdate"
                                                    :error-messages="birthdateErrors"
                                                    @input="$v.birthdate.$touch()"
                                                    @blur="$v.birthdate.$touch()"
                                                    prepend-icon="event"
                                                    readonly
                                            ></v-text-field>
                                            <v-date-picker
                                                    ref="picker"
                                                    locale="ca"
                                                    v-model="birthdate"
                                                    @change="saveBirthdate"
                                                    min="1900-01-01"
                                                    :max="new Date().toISOString().substr(0, 10)"
                                            ></v-date-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                            </v-container>

                            <h1 class="subheading primary--text">
                                <div>
                                    <p>Adreça</p>
                                </div>
                            </h1>

                            <v-container grid-list-md text-xs-center>
                                <v-layout row wrap>
                                    <v-flex md3>
                                        <v-text-field
                                                label="Carrer"
                                                v-model="street"
                                                :error-messages="streetErrors"
                                                :counter="255"
                                                @input="$v.street.$touch()"
                                                @blur="$v.street.$touch()"
                                                required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md1>
                                        <v-text-field
                                                label="Número"
                                                v-model="number"
                                                :error-messages="numberErrors"
                                                @input="$v.number.$touch()"
                                                @blur="$v.number.$touch()"
                                                required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md1>
                                        <v-text-field
                                                label="Pis"
                                                v-model="floor"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md1>
                                        <v-text-field
                                                label="# pis"
                                                v-model="floor_number"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md1>
                                        <v-select
                                                label="Codi postal"
                                                autocomplete
                                                :loading="loadingPostalCode"
                                                cache-items
                                                required
                                                combobox
                                                clearable
                                                :error-messages="postalCodeErrors"
                                                @input="$v.postal_code.$touch()"
                                                @blur="$v.postal_code.$touch()"
                                                :items="postalCodes"
                                                :search-input.sync="searchPostalCodes"
                                                v-model="postal_code"
                                        ></v-select>
                                    </v-flex>
                                    <v-flex md3>
                                        <v-select
                                                label="Localitat"
                                                autocomplete
                                                :loading="loadingLocality"
                                                cache-items
                                                required
                                                combobox
                                                clearable
                                                :items="localities"
                                                :search-input.sync="searchLocalities"
                                                :error-messages="localityErrors"
                                                v-model="locality"
                                                @input="$v.locality.$touch()"
                                                @blur="$v.locality.$touch()"
                                        ></v-select>
                                    </v-flex>
                                    <v-flex md2>
                                        <v-select
                                                label="Província"
                                                autocomplete
                                                :loading="loadingProvince"
                                                cache-items
                                                required
                                                clearable
                                                :items="provinces"
                                                :search-input.sync="searchProvinces"
                                                v-model="province"
                                                :error-messages="provinceErrors"
                                                @input="$v.province.$touch()"
                                                @blur="$v.province.$touch()"
                                        ></v-select>
                                    </v-flex>
                                </v-layout>
                            </v-container>


                            <h1 class="subheading primary--text">
                                <div>
                                    <p>Contacte</p>
                                </div>
                            </h1>

                            <v-container grid-list-md text-xs-center>
                                <v-layout row wrap>
                                    <v-flex md5>
                                        <v-text-field
                                                label="Correus electrònic"
                                                v-model="email"
                                                :error-messages="emailErrors"
                                                @input="$v.email.$touch()"
                                                @blur="$v.email.$touch()"
                                                required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md3>
                                        <v-select
                                                v-model="other_emails"
                                                label="Altres correus"
                                                multiple
                                                chips
                                                tags
                                                clearable
                                                :items="[]"
                                        ></v-select>
                                    </v-flex>
                                    <v-flex md2>
                                        <v-text-field
                                                label="Telèfon"
                                                v-model="telephone"
                                                :error-messages="telephoneErrors"
                                                @input="$v.telephone.$touch()"
                                                @blur="$v.telephone.$touch()"
                                                required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md2>
                                        <v-select
                                                v-model="other_telephones"
                                                label="Altres Telèfons"
                                                multiple
                                                chips
                                                tags
                                                clearable
                                                :items="[]"
                                        ></v-select>
                                    </v-flex>
                                </v-layout>
                            </v-container>

                            <h1 class="subheading primary--text">
                                <div>
                                    <p>Formació</p>
                                </div>
                            </h1>

                            <v-container grid-list-md text-xs-center>
                                <v-layout row wrap>
                                    <v-flex md6>
                                        <v-text-field
                                                label="Titulació d'accés"
                                                v-model="degree"
                                                :error-messages="degreeErrors"
                                                :counter="255"
                                                @input="$v.degree.$touch()"
                                                @blur="$v.degree.$touch()"
                                                required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md6>
                                        <v-text-field
                                                label="Altres titulacions"
                                                v-model="other_degrees"
                                                :counter="255"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md6>
                                        <v-text-field
                                                label="Idiomes segons marc Europeu"
                                                v-model="languages"
                                                :counter="255"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md6>
                                        <v-text-field
                                                label="Perfils professionals"
                                                v-model="profiles"
                                                :counter="255"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md12>
                                        <v-text-field
                                                label="Altres formacions"
                                                v-model="other_training"
                                                :counter="255"
                                        ></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>

                            <h1 class="subheading primary--text">
                                <div>
                                    <p>Cos, especialitat</p>
                                </div>
                            </h1>

                            <v-container grid-list-md text-xs-center>
                                <v-layout row wrap>
                                    <v-flex md3>
                                        <v-select
                                                label="Cos"
                                                autocomplete
                                                required
                                                clearable
                                                :error-messages="forceErrors"
                                                @input="$v.force.$touch()"
                                                @blur="$v.force.$touch()"
                                                :items="forces"
                                                v-model="force"
                                        >
                                            <template slot="item" slot-scope="data">
                                                {{data.item.code}} - {{data.item.name}}
                                            </template>
                                            <template slot="selection" slot-scope="data">
                                                {{data.item.code}}
                                            </template>
                                        </v-select>
                                    </v-flex>
                                    <v-flex md5>
                                        <v-select
                                                label="Especialitat"
                                                autocomplete
                                                required
                                                clearable
                                                :error-messages="specialtyErrors"
                                                @input="$v.specialty.$touch()"
                                                @blur="$v.specialty.$touch()"
                                                :items="specialties"
                                                v-model="specialty"
                                        >
                                            <template slot="item" slot-scope="data">
                                                {{data.item.code}} - {{data.item.name}}
                                            </template>
                                            <template slot="selection" slot-scope="data">
                                                {{data.item.code}} - {{data.item.name}}
                                            </template>
                                        </v-select>
                                    </v-flex>
                                    <v-flex md4>
                                        <v-text-field
                                                label="Any inici serveis ensenyament"
                                                v-model="teacher_start_date"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md4>
                                        <v-menu
                                                lazy
                                                v-model="startDateMenu"
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                :nudge-right="40"
                                                min-width="290px"
                                        >
                                            <v-text-field
                                                    slot="activator"
                                                    label="Data incorporació centre"
                                                    v-model="start_date"
                                                    :error-messages="startDateErrors"
                                                    @input="$v.start_date.$touch()"
                                                    @blur="$v.start_date.$touch()"
                                                    required
                                            ></v-text-field>
                                            <v-date-picker
                                                    locale="ca"
                                                    v-model="start_date"
                                                    min="1900-01-01"
                                                    :max="new Date().toISOString().substr(0, 10)"
                                            ></v-date-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex md3>
                                        <v-text-field
                                                label="Data aprovació oposicions"
                                                v-model="opositions_date"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md5>
                                        <v-select
                                                label="Situació administrativa"
                                                autocomplete
                                                required
                                                clearable
                                                :items="administrativeStatuses"
                                                v-model="administrative_status"
                                                :error-messages="administrativeStatusErrors"
                                                @input="$v.administrative_status.$touch()"
                                                @blur="$v.administrative_status.$touch()"
                                        >
                                            <template slot="item" slot-scope="data">
                                                {{data.item.name}}
                                            </template>
                                            <template slot="selection" slot-scope="data">
                                                {{data.item.name}}
                                            </template>
                                        </v-select>
                                    </v-flex>
                                    <v-flex md6>
                                        <v-text-field
                                                label="Lloc destinació definitiva (només comissió serveis)"
                                                v-model="destination_place"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md6>
                                        <v-text-field
                                                label="Professor al que substitueix"
                                                v-model="teacher_id"
                                                :error-messages="teacherErrors"
                                                @input="$v.teacher_id.$touch()"
                                                @blur="$v.teacher_id.$touch()"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md6>
                                        <v-checkbox
                                                label="Accepteu les condicions d'ús?"
                                                v-model="checkbox"
                                                :error-messages="checkboxErrors"
                                                @change="$v.checkbox.$touch()"
                                                @blur="$v.checkbox.$touch()"
                                                required
                                        ></v-checkbox>
                                    </v-flex>
                                </v-layout>
                            </v-container>

                            <v-btn @click="submit">Enviar</v-btn>
                        </form>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<style>

</style>

<script>
  import { validationMixin } from 'vuelidate'
  import withSnackbar from '../mixins/withSnackbar'
  import axios from 'axios'
  import PendingTeacher from './Mixins/PendingTeacher'

  export default {
    mixins: [validationMixin, withSnackbar, PendingTeacher],
    watch: {
      birthdateMenu (val) {
        val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
      },
      searchPostalCodes (val) {
        val && (val.length > 1) && this.queryPostalCodes(val)
      },
      searchLocalities (val) {
        val && (val.length > 1) && this.queryLocalities(val)
      },
      searchProvinces (val) {
        val && (val.length > 1) && this.queryProvinces(val)
      }
    },
    methods: {
      queryPostalCodes (v) {
        this.loadingPostalCode = true
        // Simulated ajax query
        setTimeout(() => {
          this.postalCodes = this.fakepostalCodes.filter(e => {
            return (e || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
          })
          this.loadingPostalCode = false
        }, 500)
      },
      queryLocalities (v) {
        this.loadingLocality = true
        // Simulated ajax query
        setTimeout(() => {
          this.localities = this.fakeLocalities.filter(e => {
            return (e || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
          })
          this.loadingLocality = false
        }, 500)
      },
      queryProvinces (v) {
        this.loadingProvince = true
        // Simulated ajax query
        setTimeout(() => {
          this.provinces = this.fakeProvinces.filter(e => {
            return (e || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
          })
          this.loadingProvince = false
        }, 500)
      },
      submit () {
        if (!this.$v.$invalid) {
          axios.post('api/v1/add_teacher', {
            name: this.name,
            sn1: this.sn1,
            sn2: this.sn2,
            identifier: this.identifier,
            birthdate: this.birthdate,
            street: this.street,
            number: this.number,
            floor: this.floor,
            floor_number: this.floor_number,
            postal_code: this.postal_code,
            locality: this.locality,
            province: this.province,
            email: this.email,
            other_emails: this.other_emails.join(),
            telephone: this.telephone,
            other_telephones: this.other_telephones.join(),
            degree: this.degree,
            other_degrees: this.other_degrees,
            languages: this.languages,
            profiles: this.profiles,
            other_training: this.other_training,
            force_id: this.force.id,
            specialty_id: this.specialty.id,
            teacher_start_date: this.teacher_start_date,
            start_date: this.start_date,
            opositions_date: this.opositions_date,
            administrative_status_id: this.administrative_status.id,
            destination_place: this.destination_place,
            teacher_id: this.teacher_id
          }).then(response => {
            console.log(response)
            this.showMessage('Dades enviades correctament')
            this.clear()
            this.$v.$reset()
          }).catch(error => {
            console.log(error)
            this.showError(error)
          })
        } else {
          this.$v.$touch()
        }
      },
      clear () {
        this.name = ''
        this.sn1 = ''
        this.sn2 = ''
        this.identifier = ''
        this.birthdate = ''
        this.street = ''
        this.number = ''
        this.floor = ''
        this.floor_number = ''
        this.postal_code = ''
        this.locality = ''
        this.province = ''
        this.email = ''
        this.other_emails = []
        this.telephone = ''
        this.other_telephones = []
        this.degree = ''
        this.other_degrees = ''
        this.languages = ''
        this.profiles = ''
        this.other_training = ''
        this.force = ''
        this.specialty = ''
        this.teacher_start_date = ''
        this.start_date = ''
        this.opositions_date = ''
        this.administrative_status = ''
        this.destination_place = ''
        this.teacher_id = ''
        this.checkbox = false
      },
      saveBirthdate (date) {
        this.$refs.menu.save(date)
      }
    }
  }
</script>
