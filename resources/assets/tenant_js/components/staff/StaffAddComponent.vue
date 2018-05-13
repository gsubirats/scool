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
                                                v-if="isTeacher"
                                                :items="specialties"
                                                v-model="specialty"
                                                item-text="code"
                                                label="Especialitat"
                                                :clearable="true"
                                                single-line
                                        ></v-select>

                                        <v-select
                                                v-if="isTeacher"
                                                :items="families"
                                                v-model="family"
                                                item-text="name"
                                                label="Família"
                                                :clearable="true"
                                                single-line
                                        ></v-select>

                                        <v-select
                                                label="Escolliu un titular"
                                                :items="users"
                                                v-model="holder"
                                                item-text="name"
                                                item-value="name"
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
        family: null,
        holder: null
      }
    },
    props: {
      teacherType: {
        type: String,
        required: true
      },
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
      },
      users: {
        type: Array,
        required: true
      }
    },
    computed: {
      isTeacher () {
        return this.staffType && this.staffType.name === this.teacherType
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
        this.holder = null
      }
    }
  }
</script>
