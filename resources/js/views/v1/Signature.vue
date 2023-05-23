<template>
  <div class="col col-12" style="height: calc(100vh - 45px);">

    <!-- Breadcrumb Begins -->
    <div class="row p-2 pl-3 mb-5 w-100" style="hieght:35px">
      <router-link :to=" {name: 'home'} " class="primary--text text-decoration-none">
        {{'Home'}}
      </router-link>

      <v-icon size="14" dense class="p-0 ml-3 mr-3">mdi-chevron-right</v-icon>
      
      <p class="text-secondary m-0 p-0">{{ 'Signature' }}</p>
    </div>
    <!-- Breadcrumb Ends -->


    <div class="m-auto rounded" style="max-width: 700px">
      <v-tabs v-model="tabs" fixed-tabs :color="color" height="40">
        <v-tab href="#email" :class="`${color}--text text-decoration-none`">
          <p class="m-0 text-capitalize">
            Email <v-icon color="green darken-3" dense>mdi-at</v-icon>
          </p>
        </v-tab>

        <v-tab href="#document" :class="`${color}--text text-decoration-none`" >
          <p class="m-0 text-capitalize">
            Document <v-icon color="green darken-3" dense>mdi-file-document</v-icon>
          </p>
        </v-tab>
      </v-tabs>

      <v-divider class="m-0 p-0 mr-2 ml-2"/>
      
      <v-tabs-items v-model="tabs">
        <v-tab-item value="email">
          <v-card flat height="320" class="m-2 p-3" outlined>
            <rich-text v-model="richtextContent"/>
          </v-card>
        </v-tab-item>

        <v-tab-item value="document">
          <v-card flat height="320" class="m-2 p-3" outlined>

            <v-alert border="left" colored-border :color="color" dense dismissible elevation="3" type="info">
              <p class="text-caption mb-0 pb-0">
                Document (e.g. sales order, purchase order e.t.c) approved by you shall have this signature.
              </p>
            </v-alert>

            <div class="row mt-3">
              <v-btn depressed class="m-4" outlined text rounded
                @click.prevent="documentSignature = true">
                Add your signature
                <v-icon color="secondary darken-5" right>mdi-draw</v-icon>
              </v-btn>
              
              <div class="m-4" v-if="$store.getters.user.signature"
                style="max-width:260px; max-height:210px;">
                <v-img draggable="false" class="rounded" contain max-height="200" max-width="250"
                  :src="$store.getters.user.signature.document || temporary_signature"/>
              </div>
            </div>

            <v-dialog v-model="documentSignature" max-width="700px" persistent
              @click:outside="documentSignature = false">
              <file-upload :display.sync="documentSignature" :dense="true"
                :multiple="false" :title="`Add Document Signature`"
                @files-uploaded="saveDocumentSignature($event)"/>
            </v-dialog>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </div>
    
  </div>
</template>

<script>

  export default {

    data() {
      return {
        tabs: null,
        documentSignature: false,
        richtextContent: null,
        color: this.$store.getters.theme.color,
        src: null,
        user,
        delay_func: null,
        loading: true,
        temporary_signature: null
      }
    },

    watch: {
      richtextContent: {
        deep: true,
        handler(signature) {
          if (this.loading) {
            this.loading = false
            return
          }
          if (!signature || this.$route.params.id) return

          if (this.delay_func) clearTimeout(this.delay_func)
          this.delay_func = setTimeout(() => this.saveSignature('email', signature), 1200)
        }
      }
    },

    created() {
      if (this.user.signature) {
        this.richtextContent = this.user.signature.email || ''
      }
    },

    mounted() {
      this.$store.commit('user', this.user)
    },

    methods: {

      saveDocumentSignature({ file, params })
      {
        const reader = new FileReader()
        reader.readAsDataURL( file.file )

        reader.onload = async (e) =>  {
          let signature = e.target.result
          await this.saveSignature('document', signature)
        }
      },

      async saveSignature(type, signature)
      {
        if (!this.user.id) return
        
        await this.$store
                  .dispatch( 'saveSignature', { id: this.user.id, type, signature })
                  .then( res => {
                    this.temporary_signature = signature
                    this.$store.commit('saveSignature', { type, signature }) 
                  })
                  .catch(error => console.log(error) )
      }
    }
  }
</script>

<style scoped>

  >>>.v-tab {
    letter-spacing: normal;
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
  }

  >>>.v-text-field .v-input__control .v-input__slot {
    min-height: 0 !important;
    padding: 1px 8px !important;
    margin-bottom: 2px !important;
    display: flex !important;
    align-items: center !important;
  }

  >>>.v-text-field .v-input__control .v-input__slot .v-input__append-inner {
    margin-top: 3px !important;
  }

  >>>.v-text-field .v-input__control .v-input__slot .v-input__prepend-inner {
    margin-top: 3px !important;
  }

  >>>.v-text-field .v-input__control .v-input__slot .v-select__slot label {
    margin-top: 0px !important;
    font-size: 0.9em;
  }

  >>>.v-text-field .v-input__control .v-input__slot label {
    margin-top: -4px !important;
    font-size: .95rem;
  }
  
  >>>.v-text-field .v-input__control .v-input__slot label.v-label--active {
    margin: 0 0 0 5px !important;
  }

  >>>.v-text-field__details {
    margin: 2px !important;
  }
</style>