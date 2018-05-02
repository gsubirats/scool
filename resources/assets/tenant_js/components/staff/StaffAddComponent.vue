<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="blue darken-3 white--text"><h2>Afegeix una nova plaça</h2></v-card-title>
                    <v-card-text class="px-0 mb-2">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <form>

                                        <v-select
                                            :items="staffTypes"
                                            v-model="staffType"
                                            item-text="name"
                                            label="Tipus"
                                            :clearable="true"
                                            single-line
                                        ></v-select>

                                        <v-select
                                                :items="specialties"
                                                v-model="specialty"
                                                item-text="code"
                                                label="Especialitat"
                                                :clearable="true"
                                                single-line
                                        ></v-select>

                                        <v-select
                                                :items="families"
                                                v-model="family"
                                                item-text="name"
                                                label="Família"
                                                :clearable="true"
                                                single-line
                                        ></v-select>

                                        <!--<v-text-field-->
                                                <!--label="Correu electrònic"-->
                                                <!--v-model="email"-->
                                                <!--:error-messages="emailErrors"-->
                                                <!--@input="$v.email.$touch()"-->
                                                <!--@blur="$v.email.$touch()"-->
                                                <!--required-->
                                        <!--&gt;</v-text-field>-->
                                        <!--<v-text-field-->
                                                <!--label="Paraula de pas (no és obligatoria)"-->
                                                <!--v-model="password"-->
                                                <!--type="password"-->
                                        <!--&gt;</v-text-field>-->
                                        <!--<v-select-->
                                                <!--@change="selectedUserType"-->
                                                <!--:items="userTypes"-->
                                                <!--v-model="userType"-->
                                                <!--item-text="name"-->
                                                <!--label="Tipus usuari"-->
                                                <!--:clearable="true"-->
                                                <!--single-line-->
                                        <!--&gt;</v-select>-->

                                        <!--<v-select-->
                                                <!--:items="roles"-->
                                                <!--v-model="role"-->
                                                <!--item-text="name"-->
                                                <!--label="Rol"-->
                                                <!--:clearable="true"-->
                                                <!--single-line-->
                                                <!--multiple-->
                                        <!--&gt;</v-select>-->

                                        <v-btn @click="create"
                                               :loading="creating">Crear</v-btn>
                                        <v-btn @click="clear">Netejar</v-btn>
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
  export default {
    data () {
      return {
        creating: false,
        staffType: null,
        specialty: null,
        family: null
      }
    },
    props: {
      staffTypes: {
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
      }
    },
    methods: {
      create () {
        // TODO
      },
      clear () {
        this.staffType = null
        this.specialty = null
        this.family = null
      }
    }
  }
</script>
