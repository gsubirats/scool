<template>
    <v-container grid-list-md>
        Imatges disponibles:

        <v-select
                :items="availablePhotos"
                v-model="photo"
                label="Selecciona foto"
                item-text="name"
                item-value="name"
                multiple
                chips
                autocomplete
        >
            <template slot="selection" slot-scope="data">
                <v-chip
                        :selected="data.selected"
                        :key="JSON.stringify(data.item)"
                        close
                        class="chip--select-multi"
                        @input="data.parent.selectItem(data.item)"
                >
                    <v-avatar>
                        <img :src="data.item.avatar">
                    </v-avatar>
                    {{ data.item.name }}
                </v-chip>
            </template>
            <template slot="item" slot-scope="data">
                <template>
                    <v-list-tile-avatar>
                        <img :src="'/teacher_photos/' + data.item.slug">
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.filename"></v-list-tile-title>
                        <!--<v-list-tile-sub-title v-html="data.item.group"></v-list-tile-sub-title>-->
                    </v-list-tile-content>
                </template>
            </template>
        </v-select>

        <!--<v-layout row wrap>-->
            <!--<v-flex v-for="photo in availablePhotos" :key="photo" md2>-->
                <!--<v-card>-->
                    <!--<v-card-media src="/static/doc-images/cards/desert.jpg" height="200px" >-->
                    <!--</v-card-media>-->
                    <!--<v-card-title primary-title>-->
                            <!--<p>{{ photo.filename }}</p>-->
                    <!--</v-card-title>-->
                <!--</v-card>-->
            <!--</v-flex>-->
        <!--</v-layout>-->
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
        <v-btn
                :loading="refreshing"
                :disabled="refreshing"
                color="info"
                class="white--text"
                @click.native="refresh"
        >
            Actualitzar
            <v-icon right dark>refresh</v-icon>
        </v-btn>
    </v-container>
</template>

<style>

</style>

<script>
  export default {
    data () {
      return {
        loading: false,
        refreshing: false,
        photo: null
      }
    },
    props: {
      availablePhotos: {
        type: Array,
        required: true
      }
    },
    methods: {
      upload () {
        // TODO
      },
      refresh () {
        // TODO
      }
    }
  }
</script>
