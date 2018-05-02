<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="blue darken-3 white--text"><h2>Afegeix un usuari</h2></v-card-title>
                    <v-card-text class="px-0 mb-2">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <form>
                                        <v-text-field
                                                label="Nom"
                                                v-model="name"
                                                :error-messages="nameErrors"
                                                :counter="255"
                                                @input="$v.name.$touch()"
                                                @blur="$v.name.$touch()"
                                                required
                                        ></v-text-field>
                                        <v-text-field
                                                label="Correu electrònic"
                                                v-model="email"
                                                :error-messages="emailErrors"
                                                @input="$v.email.$touch()"
                                                @blur="$v.email.$touch()"
                                                required
                                        ></v-text-field>
                                        <v-text-field
                                                label="Paraula de pas (no és obligatoria)"
                                                v-model="password"
                                                type="password"
                                        ></v-text-field>
                                        <v-select
                                                @change="selectedUserType"
                                                :items="userTypes"
                                                v-model="userType"
                                                item-text="name"
                                                label="Tipus usuari"
                                                :clearable="true"
                                                single-line
                                        ></v-select>

                                        <v-select
                                                :items="roles"
                                                v-model="role"
                                                item-text="name"
                                                label="Rol"
                                                :clearable="true"
                                                single-line
                                                multiple
                                        ></v-select>

                                        <v-btn
                                                @click="createAndInvite"
                                                :disabled="$v.$invalid"
                                                :loading="creatingAndInviting">Crear i invitar</v-btn>
                                        <v-btn @click="create"
                                               :disabled="$v.$invalid"
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
        data: 'example'
      }
    },
    mounted () {
      console.log('Mounted ok')
    }
  }
</script>
