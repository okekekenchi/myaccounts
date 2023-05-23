<template>
  <div class="m-0 p-0 white">

    <input type="file" accept="image/*" class="d-none" ref="file_upload" @click="clearCache"
      @change="loadFile($event, false)"/>

    <v-card draggable="false"
      @drop.prevent="loadFile($event)"
      @dragover.prevent="dragover = true"
      @dragenter.prevent="dragover = true"
      @dragleave="dragover = false">
      
      <v-toolbar outlined flat class="pt-3 pb-4 pr-3" :dense="dense">
        <p class="text-secondary text-dark pl-1" :class="dense?'text-h6':'text-h5'">{{ title }}</p>
        <v-spacer/>

        <v-btn small icon @click.stop="submit" :disabled="uploadedFiles.length == 0"
          depressed style="margin-top:-15px">
          <v-icon id="upload-button">mdi-upload</v-icon>
        </v-btn>

        <v-btn small @click="closeDialog" icon depressed style="margin-top:-15px">
          <v-icon id="close-button">mdi-close</v-icon>
        </v-btn>
      </v-toolbar>

      <v-card-text class="white mt-2 pt-5">
        <v-row class="white d-flex flex-column rounded m-0 custom-scroll" justify="center"
          style="min-height:250px; outline: 2px dashed black !important;" dense align="center">

          <div v-if="src" style="max-width:300px; max-height:250px;">
            <v-img :src="src" class="rounded" style="max-width:300px; max-height:250px;" draggable="false"/>
          </div>

          <div v-else class="text-center col-12 row">

            <div :class="dense? 'col-6':'col-12'">
              <v-icon :class="[dragover ? 'mt-2, mb-6':'mt-5']" size="70">
                mdi-cloud-upload
              </v-icon>

              <p class="secondary--text text--lighten-5 text-h4 mt-3">
                Drag file{{ multiple?'s':'' }} here
              </p>

              <div class="m-0 p-0 mt-2" v-if="!dense">
                <v-divider/>
                <p class="secondary--text text--lighten-2 text-caption p-0"> or preferably </p>
                <v-divider/>
              </div>
            </div>

            <v-divider vertical v-if="dense"/>

            <div class="overflow-hidden" :class="dense? 'col-6':'col-12'"
              :style="{ marginTop: dense?'60px':'0px' }">
              <v-btn shaped @click.prevent="addFile" rounded color="success darken-2"
                class="mt-0  mb-3 text-capitalize" >
                Select {{ multiple?'':'a' }} file{{ multiple?'s':'' }} from your device
              </v-btn>
            </div>
          </div>
        </v-row>

      </v-card-text>

      <v-card-actions v-if="multiple" class="justify-content-center w-100" style="min-height: 50px;">
        
        <v-slide-group v-if="uploadedFiles.length > 0" show-arrows class="w-100 m-0 p-0" active-class="danger" >
          <v-slide-item v-for="file, index in uploadedFiles" :key="index" v-slot="{ active, toggle }"
            class="m-0 p-0" :class="`${color} lighten-5`">
            <v-card class="ma-2" height="50" width="50" @click="toggle">

              <v-row class="fill-height round" align="center" justify="center">

                <v-icon v-if="active" :color="`${color} darken-1`" size="25"
                  @click="removeFile(file)" class="pt-2">
                  mdi-close-circle-outline
                </v-icon>
                <v-scale-transition v-else>
                  <v-img :src="file.url" class="mt-3 round" height="50px" width="55px"
                    style="max-width:55px; max-height:50px;"/>
                </v-scale-transition>
              </v-row>
              
            </v-card>
          </v-slide-item>

          <v-slide-item class="m-0 p-0">
            <v-tooltip bottom color="secondary">
              <template v-slot:activator="{ on, attrs }">
                <v-btn elevation="2" color="" class="ma-2" @click="addFile" v-bind="attrs" v-on="on"
                  height="50px" small>
                  <v-scale-transition>
                    <v-icon dense color="secondary" size="30">mdi-plus</v-icon>
                  </v-scale-transition>
                </v-btn>
              </template>
              <span>add file</span>
            </v-tooltip>
          </v-slide-item>
        </v-slide-group>

      </v-card-actions>
    </v-card>
      
    <v-snackbar v-model="alert" :timeout="3000" left color="secondary darken-1 text-white"> {{ message }}
      <template v-slot:action="{ attrs }">
        <v-btn :color="color" icon v-bind="attrs" @click="alert = false" >
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
  export default {

    props: {
      display: { type: Boolean, required: true }, // set visibility of the form interface
      multiple: { type: Boolean, default: false },  // If set to true, multiple files can be uploaded
      dense: { type: Boolean, default: false }, // Reduces the overall height of the form
      title: { type: String, default: "Add Image" },  // Form title
      params: { type: Object, default: null}, // Any parameter you wish to receive when files are uploaded
    },

    data() {
      return {
        color: this.$store.getters.theme.color,
        dragover: false,
        uploadedFiles: [],
        requiredType: /image.*/,
        src: null,
        alert: false,
        message: ''
      }
    },

    mounted() {
      this.$emit('input', null)
      this.uploadedFiles = []
      this.src = null
    },

    methods: {

      showMessage(message) {
        this.alert = true
        this.message = message
      },

      clearCache(e) {
        e.target.value = null
      },

      closeDialog() {
        this.$emit("update:display", false)
        this.uploadedFiles = []
        this.src = null
        this.$emit('input', this.src)
      },

      removeFile (file)
      {
        const index = this.uploadedFiles.findIndex( file => file.file.name === file.file.name )
        
        if (index > -1) this.uploadedFiles.splice(index, 1)

        if (index == 0) this.src = null
      },

      addFile() {
        this.$refs.file_upload.click()
      },

      loadFile(e, dragdrop = true) {
        
        if (this.uploadedFiles.length) {
          this.$emit('input', null)
          this.uploadedFiles = []
          this.src = null
        }
        
        this.dragover = false
        
        let files = dragdrop ? e.dataTransfer.files : e.target.files
        
        if ( !files.length ) return

        let has_invalid_file_type = false

        Array.prototype.forEach.call( files, file => {
          if ( !file.type.match( this.requiredType ) ) {
            has_invalid_file_type = true
          }
        })

        if ( has_invalid_file_type ) {
          this.showMessage('Invalid file type')
          return false
        }
        
        if ( this.multiple )
        {
          Array.prototype.forEach.call( files, file => {
            this.uploadedFiles.push({ file, url: '' })
            this.setFileUrl(file)
          })
        }
        else {
          if ( files.length == 1 )
            this.setFileUrl(files[0]) 
          else
            this.showMessage("Only one file can be uploaded at a time")
        }
      },

      setFileUrl(file) {
        const reader = new FileReader()

        reader.readAsDataURL( file )
        reader.onload = e =>  {
          this.src = e.target.result
          this.uploadedFiles.push({ file, url: this.src })
        }
      },

      submit() {
        this.$emit("files-uploaded", 
            { 'file': this.multiple ? this.uploadedFiles :  this.uploadedFiles[0], 'params': this.params })
        this.closeDialog()
      }
    },
  }
</script>