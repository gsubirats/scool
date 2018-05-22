<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="blue darken-3 white--text"><h2>Professors pendents</h2></v-card-title>
                    <v-card-text class="px-0 mb-2">
                        <v-card>
                            <v-card-title>
                                <v-spacer></v-spacer>
                                <v-text-field
                                        append-icon="search"
                                        label="Buscar"
                                        single-line
                                        hide-details
                                        v-model="search"
                                ></v-text-field>
                            </v-card-title>
                            <v-data-table
                                    class="px-0 mb-2 hidden-sm-and-down"
                                    :headers="headers"
                                    :items="internalTeachers"
                                    :search="search"
                                    item-key="id"
                                    no-results-text="No s'ha trobat cap registre coincident"
                                    no-data-text="No hi ha cap professor pendent de confirmar"
                                    rows-per-page-text="Professors per pàgina"
                            >
                                <template slot="items" slot-scope="props">
                                    <tr>
                                        <td class="text-xs-left">
                                            {{ props.item.id }}
                                        </td>
                                        <td class="text-xs-left">
                                            {{ props.item.specialty && props.item.specialty.code }}
                                        </td>
                                        <td class="text-xs-left">
                                            {{ props.item.sn1 }} {{ props.item.sn2 }}, {{ props.item.name }}
                                        </td>
                                        <td class="text-xs-left">{{ props.item.email }}</td>
                                        <td class="text-xs-left">{{ props.item.telephone }}</td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ props.item.teacher_id }}
                                        </td>
                                        <td class="text-xs-left">{{ props.item.start_date }}</td>
                                        <td class="text-xs-left">{{ props.item.created_at }}</td>
                                        <td class="text-xs-left">{{ props.item.updated_at }}</td>
                                        <td class="text-xs-left">

                                            <v-dialog v-model="seeDialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                                                <v-btn slot="activator" icon class="mx-0" @click="see(props.item)">
                                                    <v-icon>visibility</v-icon>
                                                </v-btn>
                                                    <v-card>
                                                        <v-toolbar dark color="primary">
                                                            <v-btn icon dark @click.native="seeDialog = false">
                                                                <v-icon>close</v-icon>
                                                            </v-btn>
                                                            <v-toolbar-title>Fitxa del professor</v-toolbar-title>
                                                            <v-spacer></v-spacer>
                                                            <v-toolbar-items>
                                                                <v-btn dark flat @click.native="seeDialog = false">
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
                                                                                                value="PROVA"
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
                                                                                </v-layout>
                                                                            </v-container>

                                                                            <v-btn @click.native="seeDialog = false">
                                                                                <v-icon>close</v-icon> Sortir
                                                                            </v-btn>
                                                                            <v-btn black color="green" @click="submit" class="white--text">
                                                                                <v-icon>add</v-icon> Crear nou professor
                                                                            </v-btn>
                                                                        </form>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>

                                                        </v-card-text>
                                                    </v-card>
                                            </v-dialog>

                                            <v-btn icon class="mx-0" @click="editItem(props.item)">
                                                <v-icon color="teal">edit</v-icon>
                                            </v-btn>

                                            <v-dialog v-model="deleteConfirmationDialog" persistent max-width="290">
                                                <v-btn icon slot="activator">
                                                    <v-icon color="pink">delete</v-icon>
                                                </v-btn>
                                                <v-card>
                                                    <v-card-title class="headline">Si us plau confirmeu</v-card-title>
                                                    <v-card-text>Esteu segurs que voleu eliminar aquesta proposta de nou professor?</v-card-text>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn color="green darken-1" flat @click.native="deleteConfirmationDialog = false">Cancel·lar</v-btn>
                                                        <v-btn color="red darken-1" flat
                                                               :disabled="removing"
                                                               :loading="removing"
                                                               @click.native="remove(props.item)">Eliminar</v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                        </td>
                                    </tr>
                                </template>
                            </v-data-table>
                        </v-card>

                        <v-data-iterator
                                class="hidden-md-and-up"
                                content-tag="v-layout"
                                row
                                wrap
                                :items="internalTeachers"
                        >
                            <v-flex
                                    slot="item"
                                    slot-scope="props"
                                    xs12
                                    sm6
                                    md4
                                    lg3
                            >
                                <v-card>
                                    <v-card-title><h4>{{ props.item.name }}</h4></v-card-title>
                                    <v-divider></v-divider>
                                    <v-list dense>
                                        <v-list-tile>
                                            <v-list-tile-content>Email:</v-list-tile-content>
                                            <v-list-tile-content class="align-end">{{ props.item.email }}</v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-content>Created at:</v-list-tile-content>
                                            <v-list-tile-content class="align-end">{{ props.item.created_at }}</v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-content>Updated at:</v-list-tile-content>
                                            <v-list-tile-content class="align-end">{{ props.item.updated_at }}</v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                </v-card>
                            </v-flex>
                        </v-data-iterator>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>

</template>

<style>

</style>

<script>
  import { mapGetters } from 'vuex'
  import * as mutations from '../../store/mutation-types'
  import PendingTeacher from './Mixins/PendingTeacher'
  import { validationMixin } from 'vuelidate'
  import { required, maxLength, email } from 'vuelidate/lib/validators'
  import axios from 'axios'

  export default {
    mixins: [validationMixin, PendingTeacher],
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
      teacher_id: { required },
      checkbox: { required }
    },
    data () {
      return {
        removing: false,
        deleteConfirmationDialog: false,
        search: '',
        seeDialog: false,
        showDeletePendingTeacherDialog: false,
        deleting: false,
        headers: [
          {text: 'Id', align: 'left', value: 'id'},
          {text: 'Especialitat', value: 'specialty.code'},
          {text: 'Name', value: 'name'},
          {text: 'Email', value: 'email'},
          {text: 'Telèfon', value: 'telephone'},
          {text: 'Substitueix', value: 'teacher_id'},
          {text: 'Data incorporació', value: 'start_date'},
          {text: 'Data creació', value: 'formatted_created_at'},
          {text: 'Data actualització', value: 'formatted_updated_at'},
          {text: 'Accions', sortable: false}
        ]
      }
    },
    computed: {
      ...mapGetters({
        internalTeachers: 'pendingTeachers'
      })
    },
    props: {
      teachers: {
        type: Array,
        required: true
      }
    },
    methods: {
      submit () {
        console.log('TODO SUBMIT')
      },
      see (teacher) {
        this.name = teacher.name
        this.sn1 = teacher.sn1
        this.sn2 = teacher.sn2
        this.identifier = teacher.identifier
        this.birthdate = teacher.birthdate
        this.street = teacher.street
        this.number = teacher.number
        this.floor = teacher.floor
        this.floor_number = teacher.floor_number
        this.postal_code = teacher.postal_code
        this.locality = teacher.locality
        this.province = teacher.province
        this.email = teacher.email
        this.other_emails = teacher.other_emails.split(',')
        this.telephone = teacher.telephone
        this.other_telephones = teacher.other_telephones.split(',')
        this.degree = teacher.degree
        this.other_degrees = teacher.other_degrees
        this.languages = teacher.languages
        this.profiles = teacher.profiles
        this.other_training = teacher.other_training
        this.force = teacher.force
        this.specialty = teacher.specialty
        this.teacher_start_date = teacher.teacher_start_date
        this.start_date = teacher.start_date
        this.opositions_date = teacher.opositions_date
        this.administrative_status = teacher.administrative_status
        this.destination_place = teacher.destination_place
        this.teacher_id = teacher.teacher_id
      },
      remove (teacher) {
        this.removing = true
        console.log('DELETING TEACHER:...')
        console.log(teacher)
        axios.delete('/api/v1/pending_teacher').then(response => {
          this.deleteConfirmationDialog = false
          this.removing = true
        }).then(error => {
          console.log(error)
          this.removing = true
          this.showError(error)
        })
      }
    },
    created () {
      this.$store.commit(mutations.SET_PENDING_TEACHERS, this.teachers)
    }
  }
</script>
