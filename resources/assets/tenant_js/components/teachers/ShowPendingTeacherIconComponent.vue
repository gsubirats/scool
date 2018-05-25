<template>
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" >
        <v-btn slot="activator" icon class="mx-0" @click="mapTeacher(pendingTeacher)">
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
                                                    item-text="name"
                                            >
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
                                    </v-layout>
                                </v-container>

                                <v-btn @click.native="dialog = false">
                                    <v-icon>close</v-icon> Sortir
                                </v-btn>
                                <v-btn black color="green" @click="createTeacher" class="white--text">
                                    <v-icon>add</v-icon> Crear nou professor
                                </v-btn>
                            </form>
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
      },
      getSpecialty (specialtyId) {
        return this.specialties.find(specialty => specialty.id === specialtyId)
      },
      getForce (forceId) {
        return this.forces.find(force => force.id === forceId)
      },
      getAdministrativestatus (statusId) {
        return this.administrativeStatuses.find(status => status.id === statusId)
      },
      getTeacher (teacherId) {
        return this.teachers.find(teacher => teacher.id === teacherId)
      },
      mapTeacher (pendingTeacher) {
        this.name = pendingTeacher.name
        this.sn1 = pendingTeacher.sn1
        this.sn2 = pendingTeacher.sn2
        this.identifier = pendingTeacher.identifier
        this.birthdate = pendingTeacher.birthdate
        this.street = pendingTeacher.street
        this.number = pendingTeacher.number
        this.floor = pendingTeacher.floor
        this.floor_number = pendingTeacher.floor_number
        this.postal_code = pendingTeacher.postal_code
        this.locality = pendingTeacher.locality
        this.province = pendingTeacher.province
        this.email = pendingTeacher.email
        this.other_emails = pendingTeacher.other_emails && pendingTeacher.other_emails.split(',')
        this.telephone = pendingTeacher.telephone
        this.other_telephones = pendingTeacher.other_telephones && pendingTeacher.other_telephones.split(',')
        this.degree = pendingTeacher.degree
        this.other_degrees = pendingTeacher.other_degrees
        this.languages = pendingTeacher.languages
        this.profiles = pendingTeacher.profiles
        this.other_training = pendingTeacher.other_training
        this.force = this.getForce(pendingTeacher.force_id)
        this.specialty = this.getSpecialty(pendingTeacher.specialty_id)
        this.teacher_start_date = pendingTeacher.pendingTeacher_start_date
        this.start_date = pendingTeacher.start_date
        this.opositions_date = pendingTeacher.opositions_date
        this.administrative_status = this.getAdministrativestatus(pendingTeacher.administrative_status_id)
        this.destination_place = pendingTeacher.destination_place
        console.log(this.teacher)
        this.teacher = this.getTeacher(pendingTeacher.teacher_id)
        console.log(this.teacher)
      }
    },
    created () {
      this.mapTeacher(this.pendingTeacher)
    }
  }
</script>
