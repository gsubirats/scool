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
                                        <!--<v-text-field-->
                                        <!--label="Correus electrònics"-->
                                        <!--v-model="email"-->
                                        <!--:error-messages="emailErrors"-->
                                        <!--@input="$v.email.$touch()"-->
                                        <!--@blur="$v.email.$touch()"-->
                                        <!--required-->
                                        <!--&gt;</v-text-field>-->
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
                                        <v-text-field
                                                label="Altres Telèfons"
                                                v-model="other_telephones"
                                        ></v-text-field>
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
                                                :close-on-content-click="false"
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
                                                @input="$v.teacher.$touch()"
                                                @blur="$v.teacher.$touch()"
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
  import { required, maxLength, email } from 'vuelidate/lib/validators'

  export default {
    mixins: [validationMixin],
    validations: {
      name: { required, maxLength: maxLength(255) },
      sn1: { required, maxLength: maxLength(255) },
      sn2: { maxLength: maxLength(255) },
      identifier: { required, maxLength: maxLength(12) },
      birthdate: { required },
      street: { required },
      number: { required },
      postal_code: { required },
      locality: { required },
      province: { required },
      email: { required, email },
      telephone: { required },
      degree: { required },
      specialty: { required },
      force: { required },
      start_date: { required },
      administrative_status: { required },
      teacher: { required },
      checkbox: { required }
    },
    data () {
      return {
        loadingPostalCode: false,
        loadingLocality: false,
        loadingProvince: false,
        postalCodes: [],
        localities: [],
        provinces: [],
        searchPostalCodes: null,
        searchLocalities: null,
        searchProvinces: null,
        fakepostalCodes: [
          '43500', '43501', '43502', '43800', '43560', '43523', '43521'
        ],
        fakeLocalities: [
          'TORTOSA', 'VINALLOP', 'ROQUETES', 'BíTEM', 'SANT CARLES DE LA RÀPITA', 'DELTEBRE', 'El PERELLO'
        ],
        fakeProvinces: [
          'TARRAGONA', 'CASTELLO', 'BARCELONA', 'LLEIDA', 'BARCELONA', 'VALÈNCIA', 'TERUEL', 'ALACANT'
        ],
        name: '',
        sn1: '',
        sn2: '',
        identifier: '',
        birthdate: '',
        street: '',
        number: '',
        floor: '',
        floor_number: '',
        postal_code: '',
        locality: '',
        province: '',
        email: '',
        other_emails: '',
        telephone: '',
        other_telephones: '',
        degree: '',
        other_degrees: '',
        languages: '',
        profiles: '',
        other_training: '',
        force: '',
        specialty: '',
        teacher_start_date: '',
        start_date: '',
        opositions_date: '',
        administrative_status: '',
        destination_place: '',
        teacher_id: '',
        checkbox: false,
        birthdateMenu: false,
        startDateMenu: false
      }
    },
    props: {
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
    computed: {
      checkboxErrors () {
        const errors = []
        if (!this.$v.checkbox.$dirty) return errors
        !this.$v.checkbox.required && errors.push("Heu d'acceptar les condicions per poder continuar!")
        return errors
      },
      selectErrors () {
        const errors = []
        if (!this.$v.select.$dirty) return errors
        !this.$v.select.required && errors.push('Item is required')
        return errors
      },
      nameErrors () {
        const errors = []
        if (!this.$v.name.$dirty) return errors
        !this.$v.name.maxLength && errors.push('El nom ha de tenir com a màxim 255 caràcters.')
        !this.$v.name.required && errors.push('El nom és obligatori.')
        return errors
      },
      sn1Errors () {
        const errors = []
        if (!this.$v.sn1.$dirty) return errors
        !this.$v.sn1.maxLength && errors.push('El primer cognom ha de tenir com a màxim 255 caràcters.')
        !this.$v.sn1.required && errors.push('El cognom és obligatori.')
        return errors
      },
      sn2Errors () {
        const errors = []
        if (!this.$v.sn2.$dirty) return errors
        !this.$v.sn2.maxLength && errors.push('El segon cognom ha de tenir com a màxim 255 caràcters.')
        return errors
      },
      identifierErrors () {
        const errors = []
        if (!this.$v.identifier.$dirty) return errors
        !this.$v.identifier.maxLength && errors.push('El identificador ha de tenir com a màxim 12 caràcters.')
        !this.$v.identifier.required && errors.push('El identificador és obligatori.')
        return errors
      },
      birthdateErrors () {
        const errors = []
        if (!this.$v.birthdate.$dirty) return errors
        !this.$v.birthdate.required && errors.push('La data de naixement és obligatòria.')
        return errors
      },
      streetErrors () {
        const errors = []
        if (!this.$v.street.$dirty) return errors
        !this.$v.street.required && errors.push('El carrer és obligatòri.')
        return errors
      },
      numberErrors () {
        const errors = []
        if (!this.$v.number.$dirty) return errors
        !this.$v.number.required && errors.push('El número de carrer és obligatòri.')
        return errors
      },
      postalCodeErrors () {
        const errors = []
        if (!this.$v.postal_code.$dirty) return errors
        !this.$v.postal_code.required && errors.push('El codi postal és obligatòri.')
        return errors
      },
      localityErrors () {
        const errors = []
        if (!this.$v.locality.$dirty) return errors
        !this.$v.locality.required && errors.push('El poble/ciutat és obligatòri.')
        return errors
      },
      provinceErrors () {
        const errors = []
        if (!this.$v.province.$dirty) return errors
        !this.$v.province.required && errors.push('La província és obligatòria.')
        return errors
      },
      emailErrors () {
        const errors = []
        if (!this.$v.email.$dirty) return errors
        !this.$v.email.email && errors.push('Ha de ser un correu electrònic vàlid')
        !this.$v.email.required && errors.push('El correu electrònic és obligatori')
        return errors
      },
      telephoneErrors () {
        const errors = []
        if (!this.$v.telephone.$dirty) return errors
        !this.$v.telephone.required && errors.push('El telèfon és obligatori')
        return errors
      },
      degreeErrors () {
        const errors = []
        if (!this.$v.degree.$dirty) return errors
        !this.$v.degree.required && errors.push("La titulació d'accés és obligatoria")
        return errors
      },
      forceErrors () {
        const errors = []
        if (!this.$v.force.$dirty) return errors
        !this.$v.force.required && errors.push('El cos és obligatori')
        return errors
      },
      specialtyErrors () {
        const errors = []
        if (!this.$v.specialty.$dirty) return errors
        !this.$v.specialty.required && errors.push('La especialitat és obligatòria')
        return errors
      },
      startDateErrors () {
        const errors = []
        if (!this.$v.start_date.$dirty) return errors
        !this.$v.start_date.required && errors.push('La data és obligatòria')
        return errors
      },
      administrativeStatusErrors () {
        const errors = []
        if (!this.$v.administrative_status.$dirty) return errors
        !this.$v.administrative_status.required && errors.push('La situació administrativa és obligatòria')
        return errors
      },
      teacherErrors () {
        const errors = []
        if (!this.$v.teacher.$dirty) return errors
        !this.$v.teacher.required && errors.push('Camp obligatori pels substituts')
        return errors
      }
    },
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
        // TODO
        console.log('Submitting!')
      },
      saveBirthdate (date) {
        this.$refs.menu.save(date)
      }
    }
  }
</script>
