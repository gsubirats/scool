<template>
    <v-container grid-list-md>
        Fotos disponibles:

        <v-select
                :items="internalAvailablePhotos"
                v-model="photo"
                label="Selecciona foto"
                item-text="filename"
                item-value="name"
                chips
                autocomplete
                clearable
        >
            <template slot="selection" slot-scope="data">
                <v-chip
                        :selected="data.selected"
                        :key="JSON.stringify(data.item)"
                        class="chip--select"
                        @input="data.parent.selectItem(data.item)"
                >
                    <v-avatar>
                        <img :src="'/teacher_photo/' + data.item.slug">
                    </v-avatar>
                    {{ data.item.filename }}
                </v-chip>
            </template>
            <template slot="item" slot-scope="data">
                <template>
                    <v-list-tile-avatar>
                        <img :src="'/teacher_photo/' + data.item.slug">
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.filename"></v-list-tile-title>
                        <!--<v-list-tile-sub-title v-html="data.item.group"></v-list-tile-sub-title>-->
                    </v-list-tile-content>
                </template>
            </template>
        </v-select>

        <template v-if="showPhoto">
            <span v-if="uploading">Pujant la foto...</span>
            <img ref='photoImage' height="59" width="50"
                 :src="photoPath" alt="Uploaded photo" @click="upload" @error="errorOnPhoto" >
            <span v-if="fileUploaded">Foto pujada</span>
        </template>

        <form class="upload">
            <input
                    ref="photo"
                    type="file"
                    name="photo"
                    id="file-input"
                    accept="image/*"
                    :disabled="uploading"
                    @change="photoChange"/>

        </form>

        <v-btn
                :loading="uploading"
                :disabled="uploading"
                color="blue-grey"
                class="white--text"
                @click.native="upload"
        >
            Pujar foto
            <v-icon right dark>cloud_upload</v-icon>
        </v-btn>
        <v-btn
                :loading="uploadingZip"
                :disabled="uploadingZip"
                color="blue-grey"
                class="white--text"
                @click.native="upload"
        >
            Pujar zip
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
        <v-switch
                label="Veure graella"
                v-model="grid"
        ></v-switch>
        <v-layout row wrap v-if="grid">
            <v-flex v-for="photo in internalAvailablePhotos" :key="photo.slug" md2>
                <v-card>
                    <v-card-media :src="'/teacher_photo/' + photo.slug" height="200px" >
                    </v-card-media>
                    <v-card-title primary-title>
                        <p>{{ photo.filename }}</p>
                    </v-card-title>
                    <v-card-actions>
                        <v-btn flat color="red" @click="remove(photo)"
                               :loading="deleting === photo.slug"
                               :disabled="deleting === photo.slug">Eliminar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<style>
    .upload > input
    {
        display: none;
    }
</style>

<script>
  import axios from 'axios'

  export default {
    data () {
      return {
        deleting: null,
        uploading: false,
        uploadingZip: false,
        fileUploaded: false,
        refreshing: false,
        photo: null,
        grid: false,
        photoPath: '',
        internalAvailablePhotos: this.availablePhotos
      }
    },
    computed: {
      showPhoto: function () {
        return this.uploading || this.uploadingZip || this.fileUploaded
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
        this.$refs.photo.click()
      },
      refresh () {
        this.refreshing = true
        axios.get('/api/v1/unassigned_teacher_photo')
          .then(response => {
            this.refreshing = false
            this.internalAvailablePhotos = response.data
          })
          .catch(error => {
            console.log(error)
          })
      },
      photoChange (event) {
        this.uploading = true
        let target = event.target || event.srcElement
        if (target.value.length !== 0) {
          const formData = new FormData()
          formData.append('teacher_photo', this.$refs.photo.files[0])

          this.preview()

          this.save(formData)
        }
      },
      preview () {
        if (this.$refs.photo.files && this.$refs.photo.files[0]) {
          var reader = new FileReader()
          reader.onload = e => {
            this.$refs.photoImage.setAttribute('src', e.target.result)
          }
          reader.readAsDataURL(this.$refs.photo.files[0])
        }
      },
      errorOnPhoto () {
        // TODO
      },
      remove (photo) {
        console.log(photo)
        this.deleting = photo.slug
        axios.delete('/api/v1/unassigned_teacher_photo/' + photo.slug)
          .then(response => {
            this.deleting = null
            this.internalAvailablePhotos.splice(this.internalAvailablePhotos.indexOf(photo),1)
          })
          .catch(error => {
            console.log(error)
          })
      },
      save (formData) {
        axios.post('/api/v1/unassigned_teacher_photo', formData)
          .then(response => {
            this.uploading = false
            this.fileUploaded = true
            this.internalAvailablePhotos.push(response.data)
          })
          .catch(error => {
            console.log(error)
          })
      }
    }
  }
</script>
