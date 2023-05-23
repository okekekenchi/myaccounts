<template>

  <v-container fluid class="">

    <validation-observer ref="formValidator">

      <v-form @submit.prevent="changePassword">

        <v-row no-gutters>
          <v-col cols="12">
            <p class="text-capitalize h3 text-secondary">{{'Reset Password'}}</p>
          </v-col>
        </v-row>
        
        <v-row no-gutters style="max-width: 300px">
          <v-col cols="12">
            <validation-provider v-slot="{ errors }" rules="required|min:8|max:50" name="old">
              <v-text-field v-model="password.old" outlined dense label="Old Password *"
                :error-messages="errors" error-count="1" height="25" color="grey" autocomplete 
                :type="showOldPassword ? 'text':'password'" filled tabindex="10">
                
                <template v-slot:append>
                  <v-icon size="17" @click.stop="showOldPassword = !showOldPassword">
                    {{ showOldPassword ? 'mdi-eye-off' : 'mdi-eye' }}</v-icon>
                </template>
              </v-text-field>
            </validation-provider>
          </v-col>
        </v-row>

        <v-row no-gutters style="max-width: 300px">
          <v-col cols="12">
            <validation-provider v-slot="{ errors }" rules="required|min:8|max:50" name="new">
              <v-text-field v-model="password.new" outlined dense label="New Password *"
                :error-messages="errors" error-count="1" height="25" color="grey" autocomplete 
                :type="showNewPassword ? 'text':'password'" tabindex="11">
                
                <template v-slot:append>
                  <v-icon size="17" @click.stop="showNewPassword = !showNewPassword">
                    {{ showNewPassword ? 'mdi-eye-off' : 'mdi-eye' }}
                  </v-icon>
                </template>
              </v-text-field>
            </validation-provider>
          </v-col>
        </v-row>

        <v-divider class="mt-0"/>
        
        <v-row no-gutters>
          <v-spacer class="d-sm-block d-none"/>
          <v-btn type="submit" :color="`green darken-3`" height="30"
            :loading="loading" class="text-white text-capitalize">Reset Password</v-btn>
        </v-row>
      </v-form>
    </validation-observer>

    <v-snackbar v-model="alert" :timeout="3000" left color="green darken-4">
      <v-icon v-if="alert_type == 1" left>mdi-check</v-icon>
      <v-icon v-else-if="alert_type == 2" left>mdi-information</v-icon>
      <v-icon v-else left>mdi-alert-circle</v-icon>
      {{ message }}
      <template v-slot:action="{ attrs }">
        <v-btn color="white" icon v-bind="attrs" @click="alert = false">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>

<script>
  export default {

    props: ['user_id'],

    data() {
      return {
        loading: false,
        color: this.$store.getters.theme.color,
        message: null,
        alert: false,
        alert_type: 0,
        showOldPassword: false,
        showNewPassword: false,
        password: {}
      }
    },

    methods: {

      changePassword()
      {
        if (!this.user_id) return

        if ( this.$refs.formValidator.flags.invalid ) {
          this.showMessage('Fill all required fields.', 0)
          return
        }

        if ( this.$refs.formValidator.flags.dirty == false ) {
          this.showMessage('No changes made.', 0)
          return
        }

        this.password.id = this.user_id
        this.loading = true

        this.$store.dispatch('changePassword', this.password)
            .then( res => this.$store.dispatch('logout', { id: this.user_id }) )
            .catch( error => {
              switch(error.response.status)
              {
                case 406:
                  this.showMessage(error.response.data.message, 0)
                  break
                case 422:
                  this.$refs.formValidator.setErrors(error.response.data.errors)
                  break
                default:
                  this.$refs.formValidator.setErrors([])
              }
            })
            .finally( () => this.loading = false)
      },

      showMessage(msg, alert_type) {
        this.message = msg
        this.alert = true
        this.alert_type = alert_type
      }
    }    
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
  margin-top: 3px !important;
}

>>>.v-text-field .v-input__control .v-input__slot label {
  margin-top: -4px !important;
  font-size: 0.92em;
}

>>>.v-text-field .v-input__control .v-input__slot label.v-label--active {
  margin: 0 0 0 5px !important;
}

>>>.v-text-field__details {
  margin: 2px !important;
}
</style>
