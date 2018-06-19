<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs10>
                <subjects-select :subjects="subjects" v-model="subject"></subjects-select>
            </v-flex>
            <v-flex xs2>
                <v-btn color="success" @click="calculate">Calcular</v-btn>
                <v-btn color="success">TODO</v-btn>
            </v-flex>
            <v-flex xs12>
                <subject-info :subject-id="subject" :subjects="subjects"></subject-info>
            </v-flex>
            <v-flex xs12>
                Distribució setmanal (TODO). Els horaris de GPUNTIS aportaran aquesta info
                lessons -> potencials durant tot l'any tantes com lliçons hi ha tot l'any (per exemple 99 hores de UF són 99 llicóns i no pas 3 per setmana (33 setmanes))
                weekly_lessons -> Similar però plantilla setmana (el que teniem a ebre-escool)
            </v-flex>
            <v-flex xs12>
                <subject-lessons :subject-id="subject" :lessons="lessons"></subject-lessons>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
  import SubjectsSelect from '../curriculum/subjects/SubjectsSelectComponent.vue'
  import SubjectInfo from '../curriculum/subjects/SubjectInfoComponent.vue'
  import SubjectLessons from '../curriculum/subjects/SubjectLessonsComponent.vue'

  export default {
    name: 'LessonsManagerComponent',
    components: {
      'subjects-select': SubjectsSelect,
      'subject-info': SubjectInfo,
      'subject-lessons': SubjectLessons
    },
    data () {
      return {
        subject: {}
      }
    },
    props: {
      subjects: {
        type: Array,
        required: true
      },
      lessons: {
        type: Array,
        required: true
      }
    },
    methods: {
      calculate () {
        console.log('CALCULATE TODO')
      }
    }
  }
</script>
