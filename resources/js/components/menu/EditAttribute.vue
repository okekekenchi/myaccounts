<template>
  <v-menu v-model="editMenu" absolute :close-on-content-click="false" offset-y
    content-class="elevation-3"  min-width="300" nudge-left="100" nudge-bottom="13">
    <template v-slot:activator="{ on, attrs }">
      <v-btn v-show="display" icon small dark plain @click="editMenu = true"
        v-bind="attrs" v-on="on">
        <v-icon size="17" color="black">mdi-pencil-outline</v-icon>
      </v-btn>
    </template>

    <v-card rounded="5" flat class="overflow-hidden" elevation="15">
      <validation-observer ref="formValidator" v-slot="{ invalid, dirty }">
        <v-form @submit.prevent="editAttribute">
          <v-row no-gutters dense class="pt-2 pl-3 pr-3 pb-0">
            <v-col>
              <p class="m-0 pb-1">{{ label }}</p>
              <validation-provider v-slot="{ errors }" :name="name">
                <v-text-field v-model="model[name]" outlined dense color="grey" height="25"
                  :error-messages="errors" error-count="1" style="font-size: 0.85em;"
                  :type="type || 'text'"/>
              </validation-provider>
            </v-col>
          </v-row>

          <v-divider class="m-0"/>

          <v-row no-gutters>
            <v-col class="text-center">
              <v-btn v-text="'Cancel'" plain text small tile style="wdth:100%,"
                @click="editMenu = false"/>
            </v-col>

            <v-divider vertical/>

            <v-col class="text-center">
              <v-btn color="success darken-2" :disabled="invalid || !dirty" style="wdth:100%;"
                :loading="sendingRequest" plain text type="submit" small v-text="'Save'" tile/>
            </v-col>
          </v-row>
        </v-form>
      </validation-observer>
    </v-card>
  </v-menu>
</template>

<script>
  
  export default {

    props: [ 'display', 'model_name', 'model_string', 'name', 'label', 'type' ],

    data() {
      return {
        editMenu: false,
        sendingRequest: false,
        model: {}
      }
    },

    watch: {
      editMenu(val) {
        if ( val ) {
          this.resetForm()
          this.model = JSON.parse(this.model_string)
        }
      }
    },

    methods: {

      editAttribute()
      {
        this.sendingRequest = true

        this.$store
            .dispatch(`${this.model_name}/update`, this.model)
            .then(data => {
              this.$emit("on-submit", data.message)
              this.editMenu = false
            })
            .catch( error => {
              console.info(error)
              this.$refs.formValidator.setErrors(
                  error.response.status === 422 ? error.response.data.errors : [] )
            })
            .finally(res => this.sendingRequest = false)            
      },

      resetForm() {
        if( this.$refs.formValidator) this.$refs.formValidator.reset()
        this.sendingRequest = false
      }

    },
  }

</script>

<style scoped>
  >>>.v-text-field .v-input__control .v-input__slot {
    min-height: 0 !important;
    padding: 1px 8px !important;
    margin-bottom: 2px !important;
    display: flex !important;
    align-items: center !important;
  }

  >>>.v-text-field .v-input__control .v-input__slot .v-input__append-inner {
    margin-top: 4px !important;
  }

  >>>.v-text-field .v-input__control .v-input__slot .v-input__prepend-inner {
    margin-top: 4px !important;
  }

  >>>.v-text-field .v-input__control .v-input__slot label {
    margin-top: -4px !important;
    font-size: 1em;
  }
  
  >>>.v-text-field .v-input__control .v-input__slot label.v-label--active {
    margin: 0 0 0 5px !important;
  }

  >>>.v-text-field__details {
    margin: 2px !important;
  }

</style>