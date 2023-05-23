<template>
  <div class="col col-12" style="height: calc(100vh - 45px);">

    <!-- Breadcrumb Begins -->
    <div class="row p-2 pl-3 mb-5 w-100 user-select-none" style="hieght:35px">
      <router-link :to=" {name: 'dashboard'} " class="primary--text text-decoration-none">
        {{'Home'}}
      </router-link>

      <v-icon size="14" dense class="p-0 ml-3 mr-3">mdi-chevron-right</v-icon>
      
      <p class="text-secondary m-0 p-0">{{ 'API Key' }}</p>
    </div>
    <!-- Breadcrumb Ends -->
    
    <div class="col m-auto custom-scroll user-select-none"
      style="height: calc(100vh - 110px); overflow-y: scroll; max-width: 700px;">    

      <v-hover v-slot="{ hover }" close-delay="1000">
        <v-card rounded class="m-auto" height="auto" outlined>
          <v-row no-gutters class="grey lighten-5">
            <v-spacer/>
            <v-col cols="auto" style="height: 30px">
              <v-fade-transition>
                <v-btn v-if="hover" @click.prevent="copyKey" icon class="text-white">
                  <v-icon :color="`green darken-3`" >mdi-clipboard-text</v-icon>
                </v-btn>
              </v-fade-transition>
            </v-col>
          </v-row>

          <v-row no-gutters class="grey lighten-5">
            <v-col class="grey--text p-2" style="font-size: 11px;">
              <p>{{ access_token() }}</p>
            </v-col>
          </v-row>

          <v-divider class="m-0"/>
          
          <v-row no-gutters>
            <v-spacer/>
            <v-col cols="auto">
              <v-btn @click="refreshToken()" :color="`green darken-3`" height="30" width="100"
                :loading="loading" class="text-white text-capitalize m-2">Reset Key</v-btn>
            </v-col>
          </v-row>
        </v-card>
      </v-hover>
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
        alert: false,
        alert_type: 0,
        message: '',
        loading: false
      }
    },

    methods: {

      async refreshToken()
      {
        this.loading = true

        await this.$store.dispatch('refreshToken')
                  .then( res => this.showMessage('Refreshed', 1))
                  .catch( error => this.showMessage('Failed to reset key.', 0))
                  .finally( () => this.loading = false)
      },

      access_token() {
        return localStorage.access_token
      },
      
      async copyKey() {
        await navigator.clipboard.writeText(this.access_token())
        this.showMessage('Copied', 1)
      },

      showMessage(msg, alert_type) {
        this.message = msg
        this.alert = true
        this.alert_type = alert_type
      }
    }
  }
</script>
