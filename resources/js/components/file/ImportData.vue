<template>
  <v-dialog persistent v-model="importDialog"
    width="700" transition="dialog-bottom-transition"
    content-class="custom-scroll grey lighten-5">

    <template v-slot:activator="{ on, attrs }">
      <slot
        name="activator"
        :attrs="attrs"
        :on="on">

        <!-- DEFAULT SCOPE -->
        <v-btn :ripple="false" small rounded v-bind="attrs" v-on="on"
          style="text-transform:none; font-size: small;"> {{'Import Data'}}
  
          <v-icon dense right color="green darken-2">mdi-upload</v-icon>
        </v-btn>
      </slot>
    </template>
    
    <v-form @submit.prevent="submitForm" class="p-0 m-0">

      <v-card tile flat min-height="450px">
        <v-card-title
          class="grey lighten-5 p-0 pl-3 overflow-hidden"
          style="height:40px;">
          <h6 class="text-black text-uppercase pt-1" v-text="'data import'"></h6>
          <v-spacer/>

          <v-slide-x-reverse-transition>
            <v-chip v-if="data_to_import.length > 0"
              class="m-0 p-0 pr-2 pl-2"
              color="success darken-3"
              label small
              style="margin-right: -2px !important;">
              {{ data_to_import.length }} {{' records found.'}}
            </v-chip>
          </v-slide-x-reverse-transition>
        </v-card-title>

        <input type="file" :accept="fileFormat" class="d-none" ref="file_upload"
          @change="loadFile($event, false)" @click="clearCache"/>

        <v-card draggable="false" tile class="pt-5 pb-5"
          @drop.prevent="loadFile($event)"
          @dragover.prevent="dragover = true"
          @dragenter.prevent="dragover = true"
          @dragleave="dragover = false">

          <v-divider class="m-0" style="border: none; border-bottom: 1.5px dashed seagreen;"/>

          <v-card-text class="pl-0 pr-0 grey lighten-4" style="min-height:300px">
            <v-row no-gutters class="text-center mt-5">
              <v-col cols="12">
                <v-icon :class="[dragover?'mt-2, mb-6':'mt-5']" size="70"
                  :color="file ? 'success darken-2':''">
                  mdi-cloud-upload
                </v-icon>

                <p class="secondary--text text--lighten-5 text-h4 mt-3">Drag file here</p>

                <div class="m-0 p-0 mt-2 white">
                  <v-divider/>
                  <p class="secondary--text text--lighten-2 text-caption p-0"> or preferably </p>
                  <v-divider/>
                </div>
              </v-col>

              <v-col cols="12">
                <v-btn @click="selectFile" rounded :color="$store.getters.theme.new_button" 
                  style="text-transform: none; font-size: medium;" dark small>

                  <div v-if="file" class="m-0 p-0 row">
                    <p class="m-0 text-truncate" style="max-width: 230px;">{{`${file.name}`}}</p>
                    <p class="m-0">{{` | (${Kenxi.floor(file.size/1024, 2)} kB)`}}</p>
                  </div>

                  <p v-else class="m-0">Select a file from your device</p>
                </v-btn>
              </v-col>

            </v-row>
          </v-card-text>
        </v-card>

        
        <v-card-actions class="w-100 grey lighten-5 position-absolute" style="bottom:0; left:0;">
          <v-spacer/>
          <v-btn depressed v-text="'Cancel'" max-height="32" outlined plain class="font-weight-bold"
            width="100" @click.prevent="closeImportDialog()" :style="{textTransform: 'none'}"/>
 <!-- @click.prevent="submit()" -->
          <v-btn type="submit" depressed v-text="'Import your data'" max-height="32" width="130"
            :style="{background: color, textTransform: 'none'}" class="text-white"
            :disabled="!canImport()" :loading="importLoading"/>
        </v-card-actions>
        
      </v-card>
    </v-form>

    <v-snackbar v-model="alert" :timeout="5000" top color="secondary darken-1"> {{ message }}
      <template v-slot:action="{ attrs }">
        <v-btn :color="color" icon v-bind="attrs" @click="alert = false" >
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </template>
    </v-snackbar>
  </v-dialog>
</template>

<script>
  import Kenxi from '../../services/Kenxi.js'
  import FileService from '../../services/File.js'

  export default {

    props: {
      model: { type: String, required: true},
      fields: { type: Array, required: true},
    },

    data() {
      return {
        color: this.$store.getters.theme.color,
        importDialog: false,
        data_to_import: [],
        payload: { offset: 0, limit: 1000 },
        fileFormat: '.csv',
        file_max_size: 40096,
        dragover: false,
        file: null,
        Kenxi: new Kenxi(),
        alert: false,
        message: null,
        importLoading: false,
      }
    },

    watch: {
      file(val)
      {
        if ( !val )  return

        if (val.size > this.file_max_size) {
          this.showMessage(`File is too large. Maximum file size is ${this.file_max_size} kB`)
        }
      },
    },

    methods: {

      showMessage(message) {
        this.message = message
        this.alert = true
      },

      closeImportDialog()
      {
        this.importDialog = false
        this.resetForm()
      },

      clearCache(e) {
        e.target.value = null
      },

      selectFile() {
        this.$refs.file_upload.click()
      },

      async loadFile(e, dragdrop = true) {
        
        this.$emit('input', null)
        this.dragover = false    
        this.file = dragdrop ? e.dataTransfer.files[0] : e.target.files[0]
        
        if ( !this.file ) this.showMessage("Invalid file uploaded.")

        this.data_to_import = await (new FileService()).import(this.file)
        
        if (!this.data_to_import.length) this.showMessage('File does not contain data.')
      },

      async submitForm()
      {
        this.importLoading = true
        
        await this.$store
                  .dispatch( `${this.model}/import`, { file: this.file } )
                  .then(data => {
                    this.$emit("on-import", data.message)

                    if (data.list_of_data_with_error.length)
                    {
                      console.log(data.list_of_data_with_error)
                    }
                    
                    this.closeImportDialog()
                  })
                  .catch( error => {
                    console.log(error)
                    this.showMessage('Error importing file. Data does not meet the import rules.')
                  })
                  .finally( e => this.importLoading = false)
      },

      canImport()
      {
        if (!this.file || this.data_to_import.length < 1) return false

        return (this.file.size > 0 && this.file.size < this.file_max_size) ? true : false
      },

      resetForm() {
        if (this.$refs.formValidator) this.$refs.formValidator.reset()
        this.file = null
        this.data_to_import = []
      }
    },
  }

  /**
   * Object.keys(array).forEach()
   */
</script>