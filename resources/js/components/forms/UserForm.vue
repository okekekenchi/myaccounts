<template>

  <div style="height: calc(100vh - 90px);" v-if="!formLoading">

    <validation-observer ref="formValidator">
      <v-form @submit.prevent="submitForm" class="p-3 pb-10 h-auto overflow-auto"
        style="margin-top: 50px">

        <v-row no-gutters>
          <v-col>
            <validation-provider v-slot="{ errors }" name="first_name" rules="required|max:50">
              <v-text-field v-model="user.first_name" dense color="grey" height="25"
                label="First Name *" :error-messages="errors" error-count="1" outlined/>
            </validation-provider>
          </v-col>
        </v-row>

        <v-row no-gutters>
          <v-col>
            <validation-provider v-slot="{ errors }" name="last_name" rules="required|max:50">
              <v-text-field v-model="user.last_name" dense color="grey" height="25"
                label="Last Name *" :error-messages="errors" error-count="1" outlined/>
            </validation-provider>
          </v-col>
        </v-row>

        <v-row no-gutters>
          <v-col>
            <validation-provider v-slot="{ errors }" name="middle_name" rules="max:50">
              <v-text-field v-model="user.middle_name" dense color="grey" height="25"
                label="Middle Name" :error-messages="errors" error-count="1" outlined/>
            </validation-provider>
          </v-col>
        </v-row>

        <v-row no-gutters>
          <v-col>
            <validation-provider v-slot="{ errors }" name="email" rules="required|email|max:75">
              <v-text-field v-model="user.email" dense color="grey" height="25"
                label="Email *" :error-messages="errors" error-count="1" outlined/>
            </validation-provider>
          </v-col>
        </v-row>

        <v-row no-gutters>
          <v-col>
            <validation-provider v-slot="{ errors }" name="designation" rules="max:50">
              <v-text-field v-model="user.designation" dense color="grey" height="25"
                label="Designation" :error-messages="errors" error-count="1" outlined/>
            </validation-provider>
          </v-col>
        </v-row>

        <v-row no-gutters>
          <v-col class="custom--select">
            <validation-provider v-slot="{ errors }" name="report to">
              <v-select v-model="user.report_to" hide-selected color="grey"
                outlined dense height="25" label="Report To"
                :error-messages="errors" error-count="1" item-color="grey"
                :items="users" item-text="name" item-value="id"
                :menu-props="{ contentClass: 'custom-scroll white', maxHeight: '170px' }"/>
            </validation-provider>
          </v-col>
        </v-row>

        <v-row no-gutters>
          <v-col>
            <validation-provider v-slot="{ errors }" name="mobile_number" rules="required|max:50">
              <v-text-field v-model="user.mobile_number" dense color="grey" height="25"
                label="Mobile Number *" :error-messages="errors" error-count="1" outlined/>
            </validation-provider>
          </v-col>
        </v-row>

        <v-row no-gutters>
          <v-col>
            <validation-provider v-slot="{ errors }" name="work_number" rules="max:50">
              <v-text-field v-model="user.work_number" dense color="grey" height="25"
                label="Work Number" :error-messages="errors" error-count="1" outlined/>
            </validation-provider>
          </v-col>
        </v-row>

        <v-row no-gutters>
          <v-col>
            <validation-provider v-slot="{ errors }" name="timezone">
              <v-autocomplete v-model="user.timezone" hide-selected label="Timezone"
                color="grey" item-color="grey" hide-no-data dense height="25"
                item-text="name" item-value="id" :items.sync="timezones"
                :menu-props="{contentClass:'custom-scroll white', maxHeight: '150px'}"
                @change="dirty=true" :error-messages="errors" outlined />
            </validation-provider>
          </v-col>
        </v-row>

        <v-row no-gutters v-if="user.id">
          <v-col>
            <validation-provider name="active">
              <v-checkbox  class="m-0 p-0" v-model="user.active" color="green darken-2"
                hide-details dense :ripple="false">
                <template v-slot:label>
                  <h6 class="text-secondary">Active</h6>
                </template>
              </v-checkbox>
            </validation-provider>
          </v-col>
        </v-row>

        <v-card-actions class="w-100 grey lighten-5 position-fixed" style="bottom:0; left:0;">
          <v-spacer/>
          <v-btn type="submit" class="text-white mr-1 text-capitalize"
            color="green darken-4" width="100" max-height="32" :loading="sendingRequest">
            {{'Save'}}
          </v-btn>
        </v-card-actions>
      </v-form>
    </validation-observer>

    <v-snackbar v-model="alert" :timeout="3000" left color="secondary darken-1" > {{ message }}
      <template v-slot:action="{ attrs }">
        <v-btn :color="color" icon v-bind="attrs" @click="alert = false" >
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </template>
    </v-snackbar>
  </div>

  <v-row no-gutters v-else class="grey lighten-5 fill-height">
    <v-col class="d-flex justify-content-center" style="margin-top: 60%; height:38px;">
      <v-card-text class="text-caption text-center">
        Form Loading <b>...</b>
        <v-progress-circular indeterminate class="ml-3 text-success text--darken-4" size="20" width="2"/>
      </v-card-text>
    </v-col>
  </v-row>
</template>

<script>

  export default {

    props: {
      demo: { type: Boolean, default: false }, //Form is loaded for preview only if set to true
      display: { type: Boolean, required: true },
      user: { type: Object, required: true }
    },

    data() {
      return {
        alert: false,
        message: null,
        color: this.$store.getters.theme.color,
        sendingRequest: false,

        users: [],
        timezones: [],
        teams: [],
        roles: [],
        formLoading: true,
      }
    },

    mounted() {
      $('.v-navigation-drawer__content').addClass('custom-scroll')
    },

    watch: {

      async display(displayed)
      {
        if ( displayed ) {
          this.resetForm()
          this.formLoading = true
          await this.loadUsers()     
          await this.loadTimezones()
          this.formLoading = false
        }
      }
    },

    methods: {

      showMessage(message) {
        this.message = message
        this.alert = true
      },

      async loadUsers()
      {
        const selectedColumns = ['id', 'name' ],
              filter = { type: 'personal'}

        await this.$store
                  .dispatch('list', { trashed: 0, selectedColumns, filter })
                  .then( items => this.users = items)
                  .catch(error => this.showMessage('Error loading user list.'))
      },

      async loadTimezones() {
        await this.$store
                  .dispatch('timezone')
                  .then( timezones => this.timezones = timezones)
                  .catch(error =>  console.log(error))
      },
       
      loadRoles() {
        this.$store
            .dispatch( 'role', { trashed: 0, selectedColumns: ['id', 'name']} )
            .then( items => this.roles = items)
            .catch( error => console.log(error) )
      },
       
      loadTeams() {
        this.$store
            .dispatch( 'team', { trashed: 0, selectedColumns: ['id', 'name']} )
            .then( items => this.teams = items)
            .catch( error => console.log(error) )
      },

      async submitForm()
      {
        if (this.demo) {
          this.$emit("update:display", false)
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

        this.sendingRequest = true

        await this.$store
                  .dispatch(`${ this.user.id ? 'update' : 'create' }`, this.user)
                  .then(data => {
                    this.$emit("update:display", false)
                    this.$emit("on-submit", data.message)
                  })
                  .catch( error => {
                    switch(error.response.status)
                    {
                      case 406:
                        this.showMessage(error.response.data.message)
                        break
                      case 422:
                        this.$refs.formValidator.setErrors(error.response.data.errors)
                        break
                      default:
                        this.$refs.formValidator.setErrors([])
                    }
                  })
                  .finally( () => this.sendingRequest = false)
      },

      resetForm() {
        if (this.$refs.formValidator) this.$refs.formValidator.reset()
        this.sendingRequest = false
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
  font-size: 0.95em;
}

>>>.v-text-field .v-input__control .v-input__slot label.v-label--active {
  margin: 0 0 0 5px !important;
}

>>>.v-text-field__details {
  margin: 2px !important;
}

  >>> .v-input__control .v-input__slot .v-input--selection-controls__input {
    margin-top: -13px;
    margin-left: -10px;
  }

  >>> .custom--select .v-select__slot label {
    padding-top: 4px !important;
  }

  >>> .custom--select .v-text-field .v-input__control .v-input__slot .v-input__append-inner {
    margin-top: 8px !important;
  }
</style>