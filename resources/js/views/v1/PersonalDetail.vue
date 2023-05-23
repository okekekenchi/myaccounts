<template>
  <div class="col col-12" style="height: calc(100vh - 45px);">

    <!-- Breadcrumb Begins -->
    <div class="row p-2 pl-3 mb-5 w-100" style="hieght:35px">
      <router-link :to=" {name: 'home'} " class="primary--text text-decoration-none">
        {{'Home'}}
      </router-link>

      <v-icon size="14" dense class="p-0 ml-3 mr-3">mdi-chevron-right</v-icon>
      
      <p class="text-secondary m-0 p-0" v-if="user.type == 'business'" >{{ 'Company Detail' }}</p>
      <p class="text-secondary m-0 p-0" v-else >{{ 'Personal Detail' }}</p>
    </div>
    <!-- Breadcrumb Ends -->

    <div class="col col-12 pb-5 custom-scroll" v-if="user.social_media"
      style="height: calc(100vh - 110px); overflow-y: scroll;">
      <div class="col col-11 col-sm-8 m-auto elevation-3 rounded-lg">

        <validation-observer ref="formValidator">
          <v-form @submit.prevent="updateProfile" class="p-0 mt-3">

            <v-row no-gutters v-if="user.type == 'business'">
              <v-col cols="4" class="user-select-none">
                <p class="p-0 pt-1">Business Name <v-icon color="secondary" dense>mdi-label-outline</v-icon></p>
              </v-col>

              <v-col>            
                <validation-provider v-slot="{ errors }" name="business_name" rules="required|max:100">
                  <v-text-field v-model="user.business_name" dense color="grey"
                    :error-messages="errors" error-count="1" :outlined="!editing"
                    :solo="editing" :flat="editing" :readonly="editing"/>
                </validation-provider>
              </v-col>
            </v-row>

            <v-row no-gutters v-if="user.type != 'business'">
              <v-col cols="4" class="user-select-none">
                <p class="p-0 pt-1">First Name</p>
              </v-col>

              <v-col>            
                <validation-provider v-slot="{ errors }" name="first_name" rules="required|max:50">
                  <v-text-field v-model="user.first_name" dense color="grey"
                    :error-messages="errors" error-count="1" :outlined="!editing"
                    :solo="editing" :flat="editing" :readonly="editing"/>
                </validation-provider>
              </v-col>
            </v-row>
            
            <v-row no-gutters v-if="user.type != 'business'">
              <v-col cols="4" class="user-select-none">
                <p class="p-0 pt-1">Last Name</p>
              </v-col>

              <v-col>            
                <validation-provider v-slot="{ errors }" name="last_name" rules="required|max:50">
                  <v-text-field v-model="user.last_name" dense color="grey"
                    :error-messages="errors" error-count="1" :outlined="!editing"
                    :solo="editing" :flat="editing" :readonly="editing"/>
                </validation-provider>
              </v-col>
            </v-row>
            
            <v-row no-gutters v-if="user.type != 'business'">
              <v-col cols="4" class="user-select-none">
                <p class="p-0 pt-1">Middle Name</p>
              </v-col>

              <v-col>            
                <validation-provider v-slot="{ errors }" name="middle_name" rules="max:50">
                  <v-text-field v-model="user.middle_name" dense color="grey"
                    :error-messages="errors" error-count="1" :outlined="!editing"
                    :solo="editing" :flat="editing" :readonly="editing"/>
                </validation-provider>
              </v-col>
            </v-row>
            
            <v-row no-gutters>
              <v-col cols="4" class="user-select-none">
                <p class="p-0 pt-1">Designation <v-icon color="secondary" dense>mdi-account-tie-woman</v-icon></p>
              </v-col>

              <v-col>            
                <validation-provider v-slot="{ errors }" name="designation" rules="max:50">
                  <v-text-field v-model="user.designation" dense color="grey"
                    :error-messages="errors" error-count="1" :outlined="!editing"
                    :solo="editing" :flat="editing" :readonly="editing"/>
                </validation-provider>
              </v-col>
            </v-row>
            
            <v-row no-gutters>
              <v-col cols="4" class="user-select-none">
                <p class="p-0 pt-1">Mobile Number <v-icon color="secondary" dense>mdi-cellphone</v-icon></p>
              </v-col>

              <v-col>            
                <validation-provider v-slot="{ errors }" name="mobile_number" rules="max:18">
                  <v-text-field v-model="user.mobile_number" dense color="grey"
                    :error-messages="errors" error-count="1" :outlined="!editing"
                    :solo="editing" :flat="editing" :readonly="editing"/>
                </validation-provider>
              </v-col>
            </v-row>
            
            <v-row no-gutters>
              <v-col cols="4" class="user-select-none">
                <p class="p-0 pt-1">Work Number <v-icon color="secondary" dense>mdi-phone-outline</v-icon></p>
              </v-col>

              <v-col>            
                <validation-provider v-slot="{ errors }" name="work_number" rules="max:18">
                  <v-text-field v-model="user.work_number" dense color="grey"
                    :error-messages="errors" error-count="1" :outlined="!editing"
                    :solo="editing" :flat="editing" :readonly="editing"/>
                </validation-provider>
              </v-col>
            </v-row>

            <v-row no-gutters>
              <v-col cols="4" class="user-select-none">
                <p class="p-0 pt-1">Timezone <v-icon color="black" dense>mdi-timelapse</v-icon></p>
              </v-col>

              <v-col>
                <validation-provider v-slot="{ errors }" name="timezone">
                  <v-autocomplete v-model="user.timezone" hide-selected
                    color="grey" item-color="grey" hide-no-data dense
                    item-text="name" item-value="id" :items.sync="timezones"
                    :menu-props="{contentClass:'custom-scroll white', maxHeight: '150px'}"
                    @change="dirty=true" :error-messages="errors" error-count="1"
                    :solo="editing" :flat="editing" :readonly="editing" :outlined="!editing"/>
                </validation-provider>
              </v-col>
            </v-row>

            <v-row no-gutters>
              <v-col cols="4" class="user-select-none">
                <p class="p-0 pt-1">Reports to <v-icon color="black" dense>mdi-source-branch</v-icon></p>
              </v-col>

              <v-col>
                <validation-provider v-slot="{ errors }" name="timezone">
                  <v-autocomplete v-model="user.report_to" hide-selected
                    color="grey" item-color="grey" hide-no-data dense
                    item-text="name" item-value="id" :items.sync="users"
                    :menu-props="{contentClass:'custom-scroll white', maxHeight: '150px'}"
                    @change="dirty=true" :error-messages="errors" error-count="1"
                    :solo="editing" :flat="editing" :readonly="editing" :outlined="!editing"/>
                </validation-provider>
              </v-col>
            </v-row>
            
            <v-row no-gutters>
              <v-col cols="4" class="user-select-none">
                <p class="p-0 pt-1">Website <v-icon color="secondary" dense>mdi-web</v-icon></p>
              </v-col>

              <v-col>            
                <validation-provider v-slot="{ errors }" name="website" rules="max:150">
                  <v-text-field v-model="user.website" dense color="grey"
                    :error-messages="errors" error-count="1" :outlined="!editing"
                    :solo="editing" :flat="editing" :readonly="editing"/>
                </validation-provider>
              </v-col>
            </v-row>
            
            <v-row no-gutters>
              <v-col cols="4" class="user-select-none">
                <p class="p-0 pt-1">Facebook <v-icon color="primary" dense>mdi-facebook</v-icon></p>
              </v-col>

              <v-col>            
                <validation-provider v-slot="{ errors }" name="facebook" rules="max:150">
                  <v-text-field v-model="user.social_media.facebook" dense color="grey"
                    :error-messages="errors" error-count="1" :outlined="!editing"
                    :solo="editing" :flat="editing" :readonly="editing"/>
                </validation-provider>
              </v-col>
            </v-row>
            
            <v-row no-gutters>
              <v-col cols="4" class="user-select-none">
                <p class="p-0 pt-1">Twitter <v-icon color="primary" dense>mdi-twitter</v-icon></p>
              </v-col>

              <v-col>            
                <validation-provider v-slot="{ errors }" name="twitter" rules="max:150">
                  <v-text-field v-model="user.social_media.twitter" dense color="grey"
                    :error-messages="errors" error-count="1" :outlined="!editing"
                    :solo="editing" :flat="editing" :readonly="editing"/>
                </validation-provider>
              </v-col>
            </v-row>
            
            <v-row no-gutters>
              <v-col cols="4" class="user-select-none">
                <p class="p-0 pt-1">Instagram <v-icon color="pink" dense>mdi-instagram</v-icon></p>
              </v-col>

              <v-col>            
                <validation-provider v-slot="{ errors }" name="instagram" rules="max:150">
                  <v-text-field v-model="user.social_media.instagram" dense color="grey"
                    :error-messages="errors" error-count="1" :outlined="!editing"
                    :solo="editing" :flat="editing" :readonly="editing"/>
                </validation-provider>
              </v-col>
            </v-row>
            
            <v-row no-gutters>
              <v-col cols="4" class="user-select-none">
                <p class="p-0 pt-1">LinkedIn <v-icon color="primary" dense>mdi-linkedin</v-icon> </p>
              </v-col>

              <v-col>            
                <validation-provider v-slot="{ errors }" name="linkedin" rules="max:150">
                  <v-text-field v-model="user.social_media.linkedin" dense color="grey"
                    :error-messages="errors" error-count="1" :outlined="!editing"
                    :solo="editing" :flat="editing" :readonly="editing"/>
                </validation-provider>
              </v-col>
            </v-row>

            <v-btn :loading="loading" type="submit" height="60" width="100"
              color="white green--text text--darken-4"
              class="rounded-lg position-fixed font-weight-bold elevation-10 text-capitalize"
              style="top: 55px; right: 30px; font-size: 20px;">
              {{ submit_text }}
              <v-icon dense right v-if="editing">mdi-pencil-outline</v-icon>
            </v-btn>    
            
          </v-form>
        </validation-observer>
      </div>
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
        timezones: [],
        loading: false,
        editing: true,
        submit_text: 'Edit',
        alert: false,
        alert_type: 0,
        message: '',
        users: [],
        social_media: {facebook: null, twitter:null, instagram:null, linkedin:null}
      }
    },

    async created() {
      await this.loadTimezones()
      await this.loadUsers()
    },

    mounted() {
      this.$store.commit('user', this.user)
      this.user.social_media = this.user.social_media || this.social_media
    },

    methods: {

      async loadTimezones() {
        await this.$store.dispatch('timezone')
                  .then( timezones => this.timezones = timezones)
                  .catch(error =>  console.log(error))
      },

      async loadUsers() {
        let selectedColumns = [ 'id', 'name', 'business_name' ]
        await this.$store.dispatch('list', {trashed: 0, selectedColumns})
                  .then( items => this.users = items.filter(({id}) => id !== this.user.id))
                  .catch(error =>  console.log(error))
      },

      async updateProfile() {

        if (this.editing) {
          this.submit_text = 'Save'
          this.editing = false
          return
        }

        if ( this.$refs.formValidator.flags.invalid ) {
          this.showMessage('Fill all required fields.', 0)
          return
        }

        if ( this.$refs.formValidator.flags.dirty == false ) {
          this.showMessage('No changes made.', 0)
          return
        }
        
        this.loading = true

        await this.$store.dispatch('update', this.user)
                  .then( res => {
                    this.$store.commit('user', this.user)           
                    this.showMessage('Personal Details Saved.', 1)
                  })
                  .catch( error => {
                    this.showMessage('Record was not saved', 0)
                    this.$refs.formValidator.setErrors(
                      ([422, 500]).includes(error.response.status) ? error.response.data.errors : []
                    )
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
    font-size: .9rem;
  }

  >>>.v-text-field__details {
    margin: 2px !important;
  }
</style>