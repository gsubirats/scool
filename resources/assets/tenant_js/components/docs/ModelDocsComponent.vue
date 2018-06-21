<template>
    <v-dialog v-model="dialog" fullscreen @keydown.esc="dialog = false">
        <v-tooltip bottom slot="activator" v-if="tooltip">
            <v-btn icon slot="activator" style="margin: 0px">
                <v-icon :color="color" :dark="dark">{{icon}}</v-icon>
            </v-btn>
            <span v-html="tooltip"></span>
        </v-tooltip>
        <v-btn icon slot="activator" v-else style="margin: 0px">
            <v-icon :color="color" :dark="dark">{{icon}}</v-icon>
        </v-btn>
        <v-card>
            <v-card-title class="headline" v-html="title"></v-card-title>
            TODO show docs here!!!
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" flat @click.native="dialog = false" v-html="cancel"></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  export default {
    name: 'ModelDocsComponent',
    data () {
      return {
        dialog: false
      }
    },
    props: {
      icon: {
        type: String,
        default: 'attach_file'
      },
      tooltip: {
        type: String,
        default: 'Fitxers associats'
      },
      title: {
        type: String,
        default: 'Fitxers associats'
      },
      cancel: {
        type: String,
        default: 'Sortir'
      }
    }
  }
</script>

<style scoped>

</style>