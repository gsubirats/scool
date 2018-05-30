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
                                    <v-flex md2>
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
                                        <v-select
                                                label="Tipus id"
                                                autocomplete
                                                required
                                                combobox
                                                clearable
                                                :error-messages="identifierTypeErrors"
                                                @input="$v.identifierType.$touch()"
                                                @blur="$v.identifierType.$touch()"
                                                :items="identifierTypes"
                                                v-model="identifierType"
                                        ></v-select>
                                    </v-flex>
                                    <v-flex md1>
                                        <v-text-field
                                                label="DNI/NIE/Passaport"
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
                                                label="Adreça"
                                                hint="P.ex. C/ Alcanyiz o Avg/ Generalitat"
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
                                                tabindex = "-1"
                                                item-text="name"
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
                                                tabindex = "-1"
                                                item-text="name"
                                                autocomplete
                                                :loading="loadingProvince"
                                                cache-items
                                                required
                                                combobox
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
                                    <v-flex md2>
                                        <v-text-field
                                                label="Correu electrònic"
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
                                    <v-flex md1>
                                        <v-text-field
                                                label="Mòbil"
                                                v-model="mobile"
                                                :error-messages="mobileErrors"
                                                @input="$v.mobile.$touch()"
                                                @blur="$v.mobile.$touch()"
                                                required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md2>
                                        <v-select
                                                v-model="other_mobiles"
                                                label="Altres Mòbils"
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
                                    <p>Cos i especialitat</p>
                                </div>
                            </h1>

                            <v-container grid-list-md text-xs-center>
                                <v-layout row wrap>
                                    <v-flex md3>
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
                                                item-text="name"
                                        >
                                        </v-select>

                                    </v-flex>
                                    <v-flex md5>
                                        <v-select
                                                label="Cos"
                                                autocomplete
                                                tabindex="-1"
                                                required
                                                clearable
                                                :error-messages="forceErrors"
                                                @input="$v.force.$touch()"
                                                @blur="$v.force.$touch()"
                                                :items="forces"
                                                v-model="force"
                                                item-text="name"
                                        >
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
                                                    hint="DD/MM/YYYY format"
                                                    persistent-hint
                                                    prepend-icon="event"
                                                    v-model="start_date"
                                                    :error-messages="startDateErrors"
                                                    @input="$v.start_date.$touch()"
                                                    @blur="$v.start_date.$touch()"
                                                    required
                                            ></v-text-field>
                                            <v-date-picker
                                                    locale="ca"
                                                    v-model="start_date"
                                            ></v-date-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex md3>
                                        <v-text-field
                                                label="Data superació oposicions"
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
                                                item-text="name"
                                        >
                                        </v-select>
                                    </v-flex>
                                    <v-flex md6>
                                        <v-text-field
                                                label="Lloc destinació definitiva (només comissió serveis)"
                                                v-model="destination_place"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex md6>
                                        <teacher-select
                                                label="Professor al que substitueix"
                                                :teachers="teachers"
                                                v-model="teacher"
                                        ></teacher-select>
                                    </v-flex>
                                    <!-- TODO: Terms of Service | Compliance with LOPD i GDPR -->
                                    <!--<v-flex md6>-->
                                        <!--<v-checkbox-->
                                                <!--label="Accepteu les condicions d'ús?"-->
                                                <!--v-model="checkbox"-->
                                                <!--:error-messages="checkboxErrors"-->
                                                <!--@change="$v.checkbox.$touch()"-->
                                                <!--@blur="$v.checkbox.$touch()"-->
                                                <!--required-->
                                        <!--&gt;</v-checkbox>-->
                                    <!--</v-flex>-->
                                </v-layout>
                            </v-container>

                            <h1 class="subheading primary--text">
                                <div>
                                    <p>Documentació</p>
                                </div>
                            </h1>

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
  import TeacherSelect from './TeacherSelectComponent.vue'

  export default {
    components: { TeacherSelect },
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
      },
      postal_code: function (newPostalCode) {
        this.setLocality(newPostalCode)
        this.setProvince(newPostalCode)
      },
      specialty: function (newSpecialty) {
        this.setForce(newSpecialty)
      }
    },
    methods: {
      setForce (specialty) {
        if (specialty) {
          let foundForce = this.forces.find(force => {
            return force.id === specialty.force_id
          })
          if (foundForce) this.force = foundForce
        }
      },
      setLocality (postalCode) {
        if (postalCode) {
          let foundLocality = this.allLocalities.find(locality => {
            return locality.postalcode === postalCode
          })
          if (foundLocality) {
            this.localities.push(foundLocality)
            this.locality = foundLocality
          }
        }
      },
      setProvince (postalCode) {
        if (postalCode) {
          let foundProvince = this.allProvinces.find(province => {
            return postalCode.startsWith(province.postal_code_prefix)
          })
          if (foundProvince) {
            this.provinces.push(foundProvince)
            this.province = foundProvince
          }
        }
      },
      queryPostalCodes (v) {
        this.postalCodes = this.allPostalCodes.filter(postalCode => {
          return (postalCode || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
        })
      },
      queryLocalities (v) {
        this.localities = this.allLocalities.filter(locality => {
          return (locality.name || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
        })
      },
      queryProvinces (v) {
        this.provinces = this.allProvinces.filter(province => {
          return (province.name || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
        })
      },
      validateDNI (dni) {
        let numero, lt, letra
        let regexpdni = /^[XYZ]?\d{5,8}[A-Z]$/

        dni = dni.toUpperCase()

        if (regexpdni.test(dni) === true) {
          numero = dni.substr(0, dni.length - 1)
          numero = numero.replace('X', 0)
          numero = numero.replace('Y', 1)
          numero = numero.replace('Z', 2)
          lt = dni.substr(dni.length - 1, 1)
          numero = numero % 23
          letra = 'TRWAGMYFPDXBNJZSQVHLCKET'
          letra = letra.substring(numero, numero+1)
          if (letra !== lt) return false
          else return true
        } else return false
      },
      submit () {
        if (!this.$v.$invalid) {
          if (this.identifierType === 'DNI/NIF' && !this.validateDNI(this.identifier)) {
            this.showError('El DNI no és vàlid')
            return
          }
          axios.post('api/v1/add_teacher', {
            name: this.name,
            sn1: this.sn1,
            sn2: this.sn2,
            identifier_type: this.identifierType,
            identifier: this.identifier,
            birthdate: this.birthdate,
            street: this.street,
            number: this.number,
            floor: this.floor,
            floor_number: this.floor_number,
            postal_code: this.postal_code,
            locality: this.locality,
            province: this.province.name,
            email: this.email,
            other_emails: this.other_emails.join(),
            mobile: this.mobile,
            other_mobiles: this.other_mobiles,
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
            teacher_id: this.teacher.id
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
        this.mobile = ''
        this.other_mobiles = []
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
        this.teacher = {}
        this.checkbox = false
      },
      saveBirthdate (date) {
        this.$refs.menu.save(date)
      },
      fetchAllProvinces () {
        this.loadingProvince = true
        axios.get('/api/v1/provinces').then(response => {
          this.allProvinces = response.data
          this.loadingProvince = false
        }).catch(error => {
          this.loadingProvince = false
          console.log(error)
          this.showError(error)
        })
      },
      fetchAllLocalities () {
        this.loadingLocality = true
        axios.get('/api/v1/localities').then(response => {
          this.allLocalities = response.data
          this.allPostalCodes = [...new Set(this.allLocalities.map(locality => locality['postalcode']))] //Remove duplicates
          this.loadingLocality = false
          this.fillTipicalLocaties()
        }).catch(error => {
          this.loadingLocality = false
          console.log(error)
          this.showError(error)
        })
      },
      fillTipicalLocaties () {
        this.localities = this.allLocalities.filter(locality => {
          return locality.postalcode.startsWith('43')
        })
      }
    },
    created () {
      this.fetchAllProvinces()
      this.fetchAllLocalities()
    }
  }
</script>
