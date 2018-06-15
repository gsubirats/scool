<template>
    <v-dialog
            v-model="dialog"
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
            @keydown.esc="dialog = false"
    >
        <v-btn icon slot="activator" title="Afegir titular" style="margin: 0px">
            <v-icon color="teal">add</v-icon>
        </v-btn>
        <v-card tile>
            <v-toolbar card dark color="primary">
                <v-btn icon dark @click.native="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Afegir titular</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark flat @click.native="addHolder()" :disabled="adding" :loading="adding">Afegir</v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text>
                <v-list three-line subheader>
                    <v-subheader>Plaça</v-subheader>
                    <v-list-tile>
                        <v-list-tile-content>
                            <v-list-tile-title>Especialitat, família, codi i notes</v-list-tile-title>
                            <v-list-tile-sub-title> {{ job.specialty_description }} | {{ job.family_description }} | {{ job.fullcode }} | {{ job.notes }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ job.type }}</v-list-tile-title>
                            <v-list-tile-sub-title>Tipus plaça</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-subheader>Titular</v-subheader>

                    <v-list-tile avatar>
                        <v-list-tile-content>
                            TODO desplegable afegir titular
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-divider></v-divider>
            </v-card-text>
            <v-card-actions>
                <v-btn color="success" @click="addHolder()" :disabled="adding" :loading="adding">Afegir</v-btn>
                <v-btn flat @click="dialog=false">Sortir</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

</template>

<script>
  export default {
    name: 'AddHolderToJobIconComponent',
    data () {
      return {
        dialog: false,
        adding: false
      }
    },
    props: {
      job: {
        type: Object,
        required: true
      }
    },
    methods: {
      addHolder () {
        console.log('TODO ADD HOLDER')
      }
    }
  }
</script>
