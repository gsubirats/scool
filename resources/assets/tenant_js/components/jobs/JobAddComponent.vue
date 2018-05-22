<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-alert v-model="error" type="error" dismissible>
                    <template v-for="error in errors">{{ error[0] }}</template>
                </v-alert>
                <v-card>
                    <v-card-title class="blue darken-3 white--text"><h2>Afegeix una nova plaça</h2></v-card-title>
                    <v-card-text class="px-0 mb-2">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <form>
                                        <v-container grid-list-md text-xs-center>
                                            <v-layout row wrap>
                                                <v-flex md2>
                                                    <v-text-field
                                                            v-model="code"
                                                            name="code"
                                                            label="Codi"
                                                            :counter="4"
                                                            :error-messages="codeErrors"
                                                            @input="$v.code.$touch()"
                                                            @blur="$v.code.$touch()"
                                                            autofocus
                                                    ></v-text-field>
                                                </v-flex>
                                                <v-flex md2>
                                                    <v-select
                                                            :items="jobTypes"
                                                            v-model="jobType"
                                                            item-text="name"
                                                            label="Tipus"
                                                            :clearable="true"
                                                            single-line
                                                            :error-messages="jobTypeErrors"
                                                            @input="$v.jobType.$touch()"
                                                            @blur="$v.jobType.$touch()"
                                                            autocomplete
                                                    ></v-select>
                                                </v-flex>
                                                <v-flex md3>
                                                    <v-select
                                                            v-if="isTeacher"
                                                            :items="families"
                                                            v-model="family"
                                                            item-text="name"
                                                            label="Família"
                                                            :clearable="true"
                                                            single-line
                                                            :error-messages="familyErrors"
                                                            @input="$v.family.$touch()"
                                                            @blur="$v.family.$touch()"
                                                            autocomplete
                                                    ></v-select>
                                                </v-flex>
                                                <v-flex md5>
                                                    <v-select
                                                            v-if="isTeacher"
                                                            :items="specialties"
                                                            v-model="specialty"
                                                            item-text="code"
                                                            label="Especialitat"
                                                            :clearable="true"
                                                            single-line
                                                            :error-messages="specialtyErrors"
                                                            @input="$v.specialty.$touch()"
                                                            @blur="$v.specialty.$touch()"
                                                            autocomplete
                                                    ></v-select>
                                                </v-flex>
                                                <v-flex md6>
                                                    <v-select
                                                            label="Escolliu un titular"
                                                            :items="users"
                                                            v-model="holder"
                                                            item-text="name"
                                                            chips
                                                            max-height="auto"
                                                            autocomplete
                                                    >
                                                        <template slot="selection" slot-scope="data">
                                                            <v-chip
                                                                    close
                                                                    @input="data.parent.selectItem(data.item)"
                                                                    :selected="data.selected"
                                                                    class="chip--select-multi"
                                                                    :key="JSON.stringify(data.item)"
                                                            >
                                                                <v-avatar>
                                                                    <img :src="data.item.avatar">
                                                                </v-avatar>
                                                                {{ data.item.name }}
                                                            </v-chip>
                                                        </template>
                                                        <template slot="item" slot-scope="data">
                                                            <template v-if="typeof data.item !== 'object'">
                                                                <v-list-tile-content v-text="data.item"></v-list-tile-content>
                                                            </template>
                                                            <template v-else>
                                                                <v-list-tile-avatar>
                                                                    <img :src="data.item.avatar">
                                                                </v-list-tile-avatar>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title v-html="data.item.name"></v-list-tile-title>
                                                                    <v-list-tile-sub-title v-html="data.item.group"></v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                            </template>
                                                        </template>
                                                    </v-select>
                                                </v-flex>
                                                <v-flex md6>
                                                    <v-text-field
                                                            v-model="notes"
                                                            name="notes"
                                                            label="Observacions"
                                                    ></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                        <v-btn flat color="red" @click="clear">Netejar</v-btn>
                                        <v-btn @click="add"
                                               :loading="adding"
                                               :disabled="adding"
                                        >Afegir</v-btn>
                                    </form>
                                </v-flex>
                            </v-layout>
                        </v-container>
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
  import { required, maxLength, requiredIf } from 'vuelidate/lib/validators'
  import * as actions from '../../store/action-types'

  export default {
    mixins: [validationMixin, withSnackbar],
    validations: {
      code: {required, maxLength: maxLength(4)},
      jobType: {required},
      family: {required: requiredIf((component) => {
        return component.jobType.name === 'Professor/a'
      })},
      specialty: {required: requiredIf((component) => {
        return component.jobType.name === 'Professor/a'
      })}
    },
    data () {
      return {
        error: false,
        errors: [],
        adding: false,
        jobType: null,
        specialty: null,
        code: '',
        family: null,
        holder: null,
        notes: ''
      }
    },
    props: {
      teacherType: {
        type: String,
        required: true
      },
      jobTypes: {
        type: Array,
        required: true
      },
      specialties: {
        type: Array,
        required: true
      },
      families: {
        type: Array,
        required: true
      },
      users: {
        type: Array,
        required: true
      }
    },
    computed: {
      isTeacher () {
        return this.jobType && this.jobType.name === this.teacherType
      },
      codeErrors () {
        const errors = []
        if (!this.$v.code.$dirty) return errors
        !this.$v.code.maxLength && errors.push('El codi ha de tenir com a màxim 4 caràcters.')
        !this.$v.code.required && errors.push('El codi és obligatori.')
        return errors
      },
      jobTypeErrors () {
        const errors = []
        if (!this.$v.jobType.$dirty) return errors
        !this.$v.jobType.required && errors.push('El tipus és obligatori.')
        return errors
      },
      familyErrors () {
        const errors = []
        if (!this.$v.family.$dirty) return errors
        !this.$v.family.required && errors.push('La família és obligatoria si el tipus és professor/a.')
        return errors
      },
      specialtyErrors () {
        const errors = []
        if (!this.$v.specialty.$dirty) return errors
        !this.$v.specialty.required && errors.push('La especialitat és obligatoria si el tipus és professor/a.')
        return errors
      }
    },
    methods: {
      add () {
        if (!this.$v.$invalid) {
          this.adding = true
          this.$store.dispatch(actions.STORE_JOB, {
            type: this.jobType.name,
            code: this.code,
            family: this.family && this.family.id,
            specialty: this.specialty && this.specialty.id,
            holder: this.holder && this.holder.id,
            notes: this.notes
          }).then(response => {
            this.adding = false
            this.showMessage('Plaça afegida correctament')
          }).catch(error => {
            this.adding = false
            console.log(error)
            if (error.status === 422) this.mapErrors(error.data.errors)
            this.showError(error)
          })
        } else {
          this.$v.$touch()
        }
      },
      mapErrors (errors) {
        this.error = true
        Object.values(errors).forEach(error => {
          this.errors.push(error)
        })
      },
      clear () {
        this.code = null
        this.jobType = null
        this.specialty = null
        this.family = null
        this.holder = null
      }
    }
  }
</script>
