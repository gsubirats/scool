<template>
    <v-container grid-list-md>
    <v-layout row wrap>
        <v-flex xs8>
            <v-alert v-model="dimensionsAlert" type="warning" dismissible>
                Les fotos han de tenir unes dimensions de 670*790
            </v-alert>

            <h1 class="title">Fotos disponibles:</h1>

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


            <v-alert :value="true" type="error" v-for="error in errors" :key="error.id">
                <template v-for="errorMessage in errors">
                    {{ errorMessage[0] }}
                </template>
            </v-alert>

            <form class="upload">
                <input
                        ref="photo"
                        type="file"
                        name="teacher_photo"
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
            <v-btn v-if="internalAvailablePhotos.length > 0"
                    href="/unassigned_teacher_photos"
                    color="info"
                    class="white--text"
            >
                Baixar zip
                <v-icon right dark>file_download</v-icon>
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

            <v-dialog v-model="removeAlldialog" persistent max-width="290">
                <v-btn slot="activator"
                       color="error"
                >
                    Esborrar tots
                    <v-icon right dark>delete</v-icon>
                </v-btn>
                <v-card>
                    <v-card-title class="headline">Si us plau confirmeu</v-card-title>
                    <v-card-text>Esteu segurs que voleu esborrar totes les fotos?</v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" flat @click.native="removeAlldialog = false">Cancel·lar</v-btn>
                        <v-btn color="red darken-1"
                               flat
                               :loading="removingAll"
                               :disabled="removingAll"
                               @click.native="removeAll">Eliminar tots</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-model="removeDialog" persistent max-width="290" v-if="photo">
                <v-btn slot="activator"
                       color="error"
                >
                    Eliminar
                    <v-icon right dark>delete</v-icon>
                </v-btn>
                <v-card>
                    <v-card-title class="headline">Si us plau confirmeu</v-card-title>
                    <v-card-text>Esteu segurs que voleu esborrar la foto?</v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" flat @click.native="removeDialog = false">Cancel·lar</v-btn>
                        <v-btn color="red darken-1"
                               flat
                               :loading="deleting"
                               :disabled="deleting"
                               @click.native="removeSelectedPhoto">Eliminar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-btn
                    v-if="photo"
                    :href="'/teacher_photo/' + photo.slug + '/download'"
                    @click.native="downloadSelectedPhoto"
            >
                Baixar
                <v-icon right dark>file_download</v-icon>
            </v-btn>

            <v-layout row wrap>
                <v-flex xs2>
                    <v-switch
                            label="Veure graella"
                            v-model="availableGrid"
                    ></v-switch>
                </v-flex>
                <v-flex xs4>
                    <v-checkbox
                            label='Només fotos pendents assignar'
                            v-model="pendingPhotos"
                    ></v-checkbox>
                </v-flex>
            </v-layout>

        </v-flex>
        <v-flex xs4>
            <v-alert v-model="dimensionsAlert" type="warning" dismissible>
                Podeu pujar múltiples fotos de cop utilitzant un fitxer zip
            </v-alert>
            <h1 class="title">Fitxers zip:</h1>

            <v-select
                    :items="internalZips"
                    v-model="zipFile"
                    label="Selecciona fitxer zip"
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
                        {{ data.item.filename }}
                    </v-chip>
                </template>
                <template slot="item" slot-scope="data">
                    <template>
                        <v-list-tile-content>
                            <v-list-tile-title v-html="data.item.filename"></v-list-tile-title>
                        </v-list-tile-content>
                    </template>
                </template>
            </v-select>


            <v-alert :value="true" type="error" v-for="error in zipErrors" :key="error.id">
                <template v-for="errorMessage in errors">
                    {{ errorMessage[0] }}
                </template>
            </v-alert>

            <form class="upload">
                <input
                        ref="zip"
                        type="file"
                        name="photos"
                        accept="application/zip, application/octet-stream"
                        :disabled="uploadingZip"
                        @change="zipChange"/>

            </form>

            <v-btn
                    :loading="uploadingZip"
                    :disabled="uploadingZip"
                    color="blue-grey"
                    class="white--text"
                    @click.native="uploadZip"
            >
                Pujar zip
                <v-icon right dark>cloud_upload</v-icon>
            </v-btn>

            <v-btn
                    v-if="zipFile"
                    :href="'/unassigned_teacher_photos/' + zipFile.slug"
                    color="info"
                    class="white--text"
            >
                Baixar
                <v-icon right dark>file_download</v-icon>
            </v-btn>
            <v-btn
                    v-if="zipFile"
                    :loading="deletingZip"
                    :disabled="deletingZip"
                    color="red"
                    class="white--text"
                    @click.native="removeSelectedZip"
            >
                Eliminar
                <v-icon right dark>delete</v-icon>
            </v-btn>
        </v-flex>
        <v-flex xs12>
            <v-layout row wrap v-if="availableGrid">
                <v-flex v-for="photo in internalAvailablePhotos" :key="photo.slug" md2 lg1>
                    <v-card>
                        <v-card-media :src="'/teacher_photo/' + photo.slug" height="200px" >
                        </v-card-media>
                        <v-card-title primary-title>
                            <p>
                                <input v-if="editing === photo.slug"
                                       @keyup.esc="cancelEditing(photo)"
                                       @keyup.enter="edit(photo)"
                                       type="text" v-model="filename"
                                       @focus="$event.target.select()" id="filename">
                                <span v-else @dblclick="confirmEdit(photo)">{{ photo.filename }}</span>
                                <v-icon color="green" @click="confirmEdit(photo)" v-if="editing !== photo.slug">edit</v-icon>
                                <template v-else>
                                    <v-icon color="red" @click="cancelEditing(photo)" dark>cancel</v-icon>
                                    <v-icon color="green" dark @click="edit(photo)">done</v-icon>
                                </template>
                            </p>
                        </v-card-title>
                        <v-card-actions>
                            <v-btn
                                    v-if="confirmingRemove !== photo.slug"
                                   flat color="red"
                                   @click="confirmRemove(photo)"
                                   icon
                            >
                                <v-icon dark>delete</v-icon>
                            </v-btn>
                            <template v-else>
                                <v-btn flat color="green" @click="cancelRemove(photo)"
                                       icon
                                >

                                    <v-icon dark>cancel</v-icon>
                                </v-btn>

                                <v-btn flat color="red" @click="remove(photo)"
                                       :loading="deleting === photo.slug"
                                       :disabled="deleting === photo.slug"
                                       icon
                                >

                                    <v-icon dark>done</v-icon>
                                </v-btn>
                            </template>

                            <v-btn flat
                                   :href="'/teacher_photo/' + photo.slug + '/download'"
                                   icon>
                                <v-icon dark>file_download</v-icon>
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-flex>
        <v-flex xs12>
            <h1 class="title">Fotos assignades a professors</h1>
            <v-checkbox
                    label='Només professors sense foto'
                    v-model="onlyteachersWithoutPhoto"
            ></v-checkbox>
        </v-flex>
        <v-flex xs12>
            <v-layout row wrap v-if="enabledGrid">
                <v-flex v-for="teacher in filteredTeachers" :key="teacher.id" md2 lg1>
                    <v-card>
                        <v-card-media :src="'/user/' + teacher.user.hashid + '/photo'" height="200px" >
                        </v-card-media>
                        <v-card-title primary-title>
                            <p>
                                <span> {{ teacher.code }} | {{ teacher.user.name }} | Foto name: {{ teacher.user.photo }}</span>
                            </p>
                        </v-card-title>
                        <v-card-actions>
                            <v-btn v-if="photo"
                                    slot="activator"
                                    flat color="teal"
                                    icon
                                   title="Assignar foto seleccionada"
                                   @click="assignPhoto(teacher)"
                                   :loading="assigningPhoto"
                                   :disabled="assigningPhoto"
                            >
                                <v-icon dark>add</v-icon>
                            </v-btn>

                            <v-btn v-if="teacher.user.photo"
                                    title="Baixar la foto"
                                    flat
                                   :href="'/user/' + teacher.user.hashid + 'photo/download'"
                                   icon>
                                <v-icon dark>file_download</v-icon>
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>

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
  import withSnackbar from '../mixins/withSnackbar'

  export default {
    mixins: [withSnackbar],
    data () {
      return {
        assigningPhoto: false,
        confirmingRemove: null,
        dimensionsAlert: true,
        deleting: null,
        deletingZip: false,
        uploading: false,
        uploadingZip: false,
        fileUploaded: false,
        refreshing: false,
        photo: null,
        zipFile: null,
        availableGrid: false,
        enabledGrid: true,
        photoPath: '',
        internalAvailablePhotos: this.availablePhotos,
        internalZips: this.zips,
        errors: [],
        zipErrors: [],
        pendingPhotos: true,
        onlyteachersWithoutPhoto: true,
        editing: null,
        filename: '',
        pushEditing: false,
        removingAll: false,
        removeAlldialog: false,
        removeDialog: false
      }
    },
    computed: {
      showPhoto () {
        return this.uploading || this.uploadingZip || this.fileUploaded
      },
      filteredTeachers () {
        if (this.onlyteachersWithoutPhoto) {
          return this.teachers.filter(teacher => {
            return teacher.user.photo === null
          })
        }
        return this.teachers
      }
    },
    props: {
      availablePhotos: {
        type: Array,
        required: true
      },
      zips: {
        type: Array,
        required: true
      },
      teachers: {
        type: Array,
        required: true
      }
    },
    methods: {
      assignPhoto (teacher) {
        this.assigningPhoto = true
        console.log('Assigning photo ' + this.photo + ' to ' + teacher)
        axios.post('/teacher/' + teacher.user.id + '/photo', {
          photo: this.photo.slug
        }).then(response => {
          console.log(response)
          this.assigningPhoto = false
        }).catch(error => {
          console.log(error)
          this.assigningPhoto = false
          this.showError(error)
        })
      },
      upload () {
        this.$refs.photo.click()
      },
      uploadZip () {
        this.$refs.zip.click()
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
            this.showError(error)
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
      zipChange (event) {
        this.uploadingZip = true
        let target = event.target || event.srcElement
        if (target.value.length !== 0) {
          const formData = new FormData()
          formData.append('photos', this.$refs.zip.files[0])
          this.saveZip(formData)
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
      cancelRemove (photo) {
        this.confirmingRemove = null
      },
      confirmRemove (photo) {
        this.confirmingRemove = photo.slug
      },
      removeSelectedPhoto () {
        this.deleting = this.photo.slug
        this.remove(this.photo)
      },
      remove (photo) {
        this.deleting = photo.slug
        axios.delete('/api/v1/unassigned_teacher_photo/' + photo.slug)
          .then(response => {
            this.deleting = null
            this.internalAvailablePhotos.splice(this.internalAvailablePhotos.indexOf(photo), 1)
            this.removeDialog = false
          })
          .catch(error => {
            console.log(error)
            this.showError(error)
          })
      },
      save (formData) {
        axios.post('/api/v1/unassigned_teacher_photo', formData)
          .then(response => {
            this.uploading = false
            this.fileUploaded = true
            this.internalAvailablePhotos.push(response.data)
            this.errors = []
          })
          .catch(error => {
            this.uploading = false
            console.log(error)
            this.errors = error.data && error.data.errors
            this.showError(error)
          })
      },
      saveZip (formData) {
        axios.post('/api/v1/unassigned_teacher_photos', formData)
          .then(response => {
            this.uploadingZip = false
            this.errors = []
            const zip = {
              filename: response.data.filename,
              slug: response.data.slug
            }
            this.internalZips.push(zip)
            this.zipFile = zip
          })
          .catch(error => {
            this.uploadingZip = false
            console.log(error)
            this.errors = error.data && error.data.errors
            this.showError(error)
          })
      },
      removeSelectedZip () {
        this.deletingZip = true
        axios.delete('/api/v1/unassigned_teacher_photos/' + this.zipFile.slug)
          .then(response => {
            this.deletingZip = false
            this.internalZips.splice(this.internalZips.indexOf(this.zipFile), 1)
          })
          .catch(error => {
            console.log(error)
            this.deletingZip = false
            this.showError(error)
          })
      },
      confirmEdit (photo) {
        this.editing = photo.slug
        this.filename = photo.filename
        window.Vue.nextTick(() => {
          document.getElementById('filename').focus()
        })
      },
      edit (photo) {
        this.pushEditing = true
        axios.put('/api/v1/teacher_photo/' + photo.slug, { filename: this.filename })
          .then(response => {
            photo.slug = response.data
            photo.filename = this.filename
            this.pushEditing = false
            this.editing = null
          })
          .catch(error => {
            console.log(error)
            this.pushEditing = false
            this.showError(error)
          })
      },
      cancelEditing (photo) {
        this.editing = null
      },
      removeAll () {
        this.removingAll = true
        axios.delete('/api/v1/unassigned_teacher_photos')
          .then(response => {
            this.removingAll = false
            this.removeAlldialog = false
            this.internalAvailablePhotos = []
          })
          .catch(error => {
            console.log(error)
            this.removingAll = false
            this.showError(error)
          })
      }
    }
  }
</script>
