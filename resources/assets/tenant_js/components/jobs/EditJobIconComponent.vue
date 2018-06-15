<template>
    <v-dialog v-model="dialog" max-width="700px" @keydown.esc="dialog = false">
        <v-btn slot="activator" icon class="mx-0" >
            <v-icon color="teal">edit</v-icon>
        </v-btn>
        <v-card>
            <v-card-title>
                <span class="headline">Plaça ({{job.fullcode}})</span>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex xs12 sm6 md4>
                            <v-text-field v-model="internalJob.code" label="Codi"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <job-type-select
                                    :job-types="jobTypes"
                                    v-model="jobType"
                                    label="Típus"
                            ></job-type-select>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-text-field v-model="internalJob.order" label="Order"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-text-field v-model="internalJob.family" label="Família"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-text-field v-model="internalJob.specialty" label="Especialitat"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-text-field v-model="internalJob.notes" label="Notes"></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click.native="dialog = false">Cancel·lar</v-btn>
                <v-btn color="blue darken-1" flat @click.native="save">Modificar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import JobTypeSelect from './JobTypeSelectComponent.vue'

  export default {
    name: 'EditJobIconComponent',
    components: {
      'job-type-select': JobTypeSelect
    },
    data () {
      return {
        editedItem: {},
        dialog: false,
        internalJob: this.job,
      }
    },
    props: {
      job: {
        required: true
      },
      jobTypes: {
        type: Array,
        required: true
      }
    },
    watch: {
      job: function (newJob) {
        this.internalJob = this.job
      }
    },
    computed: {
      jobType () {
        return this.jobTypes.find(jobType => String(jobType.name) === String(this.internalJob.type)).id
      }
    },
    methods: {
      save () {
        console.log('Save')
      }
    }
  }
</script>
