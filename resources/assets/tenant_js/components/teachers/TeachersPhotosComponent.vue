<template>
    <v-container grid-list-md text-xs-center>
        Imatges disponibles:
        <v-layout row wrap>
            <v-flex v-for="i in 6" :key="`2${i}`" xs2>
                <v-card dark color="primary">
                    <v-card-text class="px-0">2</v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
        <v-btn
                :loading="loading"
                :disabled="loading"
                color="blue-grey"
                class="white--text"
                @click.native="upload"
        >
            Upload
            <v-icon right dark>cloud_upload</v-icon>
        </v-btn>
    </v-container>
</template>

<style>

</style>

<script>
  export default {
    name: 'name',
    data () {
      return {
        loading: false
      }
    },
    methods: {
      upload () {
        // TODO
      }
    }
  }
</script>
