<template>
  <div class="col col-12" style="height: calc(100vh - 45px);" v-if="user">

    <!-- Breadcrumb Begins -->
    <div class="row p-2 pl-3 mb-5 w-100" style="hieght:35px">
      <router-link :to=" {name: 'home'} " class="primary--text text-decoration-none">
        {{'Home'}}
      </router-link>

      <v-icon size="14" dense class="p-0 ml-3 mr-3">mdi-chevron-right</v-icon>
      
      <p class="text-secondary m-0 p-0" v-if="user.type == 'business'" >{{ 'Company Detail' }}</p>
      <p class="text-secondary m-0 p-0" v-else >{{ 'Security' }}</p>
    </div>
    <!-- Breadcrumb Ends -->
    
    <div class="col m-auto custom-scroll user-select-none"
      style="height: calc(100vh - 110px); overflow-y: scroll; max-width: 700px;">    

      <v-card rounded class="m-auto" height="auto">
        <div cols="col col-12 m-0">
          <p class="text-capitalize h3 text-secondary m-0 pl-3 pt-3">
            {{'Multi-factor Authentication'}}
          </p>
        </div>

        <div class="row col-12 m-0 pt-0">
          <div class="row col-3 m-0 text-center justify-content-center">
            <v-icon size="65" color="green darken-3" class="elevation-1 rounded-circle">
              mdi-security
            </v-icon>
          </div>

          <div class="row col-8 m-0 align-items-center">
            <p class="pt-0 mt-0">Enable two factor Authentication (2FA)</p>
            <v-spacer/>
            <v-switch v-model="user.two_factor_auth" :color="`green darken-2`" hide-details flat
              dense style="margin-top:-5px" @change="two_factor_auth_changed"/>
          </div>
        </div>
      </v-card>

      <br>
      <br>

      <v-card rounded class="m-auto"> 
        <change-password :user_id="user.id"/>
      </v-card>
    </div>
    

    <v-snackbar v-model="alert" :timeout="3000" left color="secondary darken-3">
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
  </div>
</template>

<script>

  export default {

    data() {
      return {
        color: this.$store.getters.theme.color,
        user,
        alert: false,
        alert_type: 0,
        message: '',
      }
    },
    
    mounted() {
      this.$store.commit('user', this.user)
    },

    methods: {
      async two_factor_auth_changed(val)
      {
        const payload = {
          id: this.user.id,
          enable: val
        }
        await this.$store.dispatch('mfa', payload)
                  .then((res) => this.showMessage(res.data.message, 1))
                  .catch((error) => {
                    this.$refs.formValidator.setErrors(
                      ([422, 500]).includes(error.response.status) ? error.response.data.errors : []
                    )
                  })
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
    font-size: .9rem;
  }

  >>>.v-text-field__details {
    margin: 2px !important;
  }
</style>

