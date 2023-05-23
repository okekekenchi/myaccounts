<template>
  <v-container fluid class="p-0">
    
    <v-card flat tile class="pt-3">

      <h5 class="m-0 pl-4 pt-2">Clone {{model}}</h5>

      <validation-observer ref="formValidator" v-slot="{ invalid }">
        <v-form @submit.prevent="clone">
          <v-card-text class="pb-0">
            <validation-provider v-slot="{ errors }" name="name"
              :rules="{required:true,max:30,alpha_spaces:true}">
              <v-text-field v-model="name" dense color="grey" outlined height="27"
                :error-count="1" :error-messages="errors" :label="`${model} Name`"/>
            </validation-provider>
          </v-card-text>

          <v-divider class="m-0"/>
          
          <v-row no-gutters>
            <v-col class="text-center">
              <v-btn v-text="'Cancel'" plain tile text style="width: 100%"
                @click="$emit('update:display', false)"/>
            </v-col>

            <v-divider vertical/>

            <v-col class="text-center">
              <v-btn v-text="'Clone'" color="success darken-2" style="width: 100%"
                :loading="sendingRequest" :disabled="invalid" plain tile text type="submit"/>
            </v-col>
          </v-row>
        </v-form>
      </validation-observer>
    </v-card>
  </v-container>
</template>

<script>

  export default {
    
    props: {
      display: { type: Boolean, default: false, required: true },
      model: { default: null },
      model_id: { default: null }
    },

    data() {
      return {
        name: null,
        sendingRequest: false
      }
    },
    watch: {
      display(displayed)
      {
        if ( displayed ) {
          this.resetForm()
        }
      }
    },

    methods: {

      clone() {
        this.sendingRequest = true

        const payload = { 
          id: this.model_id,
          name: this.name
        }

        this.$store.dispatch(`${this.model.replaceAll(' ', '')}/clone`, payload)
            .then(data => {
              this.$emit("on-submit", )
              this.$emit("update:display", false)
              this.resetForm()
            })
            .catch(error => {
              console.log(error)
              this.sendingRequest = false
              this.$refs.formValidator.setErrors(
                  error.response.status === 422 ? error.response.data.errors : [] )
            })
      },

      resetForm() {
        this.$refs.formValidator.reset()
        this.name = null
        this.sendingRequest = false
      }
    }
  }
</script>
