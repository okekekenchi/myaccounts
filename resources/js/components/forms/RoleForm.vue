<template>

  <validation-observer ref="formValidator" v-slot="{ invalid, dirty }">

    <v-form @submit.prevent="submitForm" class="p-3 pb-10 h-auto overflow-auto" style="margin-top:50px;">

      <v-row no-gutters>

        <v-col cols="12" class="p-0">
          <validation-provider v-slot="{ errors }" name="name" rules="required|min:2|max:100">
            <v-text-field v-model="role.name" outlined dense color="grey" height="30"
              label="Name *" :error-messages="errors" error-count="1" style="font-size: 0.9em;"/>
          </validation-provider>
        </v-col>

        <v-col v-if="!role.id" cols="12" class="p-0">
          <validation-provider v-slot="{ errors }" name="clone" rules="required">
            <v-select v-model="role.clone" :menu-props="{ contentClass:'custom-scroll', maxHeight: '200px' }"
              label="Select role to clone *" dense outlined item-text="name" item-value="id"
              hide-selected :error-messages="errors" error-count="1" color="grey" :items="roleList" height="30"
              style="font-size: 0.9rem;"/>
          </validation-provider>
        </v-col>
        
        <v-col v-if="!role.id" cols="12" class="p-0">
          <v-radio-group v-model="role.scope" mandatory dense label="Default scope *" class="m-0 p-0">
            <v-radio v-for="scope in scopes" :key="scope.id" class="m-0 p-0 ml-3" :value="scope"
              :label="`${scope.name} access`"/>
          </v-radio-group>
        </v-col>

        <v-col cols="12" class="p-0" v-if="role.scope && !role.id">
          <v-alert text color="info" dense dark icon="mdi-school">
            <v-row no-gutters>
              <v-col cols="12" class="pb-0">
                <p class="black--text text-caption font-weight-bold m-0 p-0 text-amazon">{{ role.scope.name }} access</p>
              </v-col>
              <v-col cols="12" class="pt-1">
                <p class="secondary--text text-caption m-0 p-0 ">{{ role.scope.comment }}</p>
              </v-col>
            </v-row>
          </v-alert>
        </v-col>
        
        <v-divider class="mt-0"/>

        <v-card-actions class="w-100 grey lighten-5 position-fixed" style="bottom:0; left:0;">
          <v-spacer/>
          <v-btn type="submit" depressed :disabled="invalid || !dirty" v-text="dirty ? 'Save':'Saved'"
            :color="`${color} darken-4`" height="30" width="100" :loading="loading" class="text-white"/>    
        </v-card-actions>
      
      </v-row>

      <v-snackbar app v-model="alert" :timeout="3000" right color="secondary darken-1"> {{ message }}
        <template v-slot:action="{ attrs }">
          <v-btn :color="color" icon v-bind="attrs" @click="alert = false" >
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </template>
      </v-snackbar>
    </v-form>
  </validation-observer>
</template>

<script>
  export default {

    props: {
      display: { type: Boolean, required: true },
      role: { type: Object, require: true },
    },

    data() {
      return {
        loading: false,
        color: this.$store.getters.theme.color,
        message: null,
        alert: false,
        scopes: [
          { id: 1, name: 'Global', comment: 'This is useful for Admins, Managers and Marketers who require a wider acces to data accross the organization.'},
          { id: 3, name: 'Territory', comment: 'This is ideal for sales and marketing teams who need to access data by territories—territories defined by regions, sources, or business size.'},
          { id: 2, name: 'Restricted', comment: 'This works best for vendors, contractors and partners who need to have restricted access to your data.'},
        ],
        roleList: []
      }
    },

    watch: {

      display(displayed)
      {
        if ( displayed ) {
          this.resetForm()
          this.loadRoleList()
        }
      }
    },
    
    mounted() {
      $('.v-navigation-drawer__content').addClass('custom-scroll')
    },

    methods: {

      loadRoleList() {
        this.$store
          .dispatch('Role/list', { trashed: 0, selectedColumns: ['id', 'name']})
          .then(items => this.roleList = items )
          .catch(error => console.log(error) )
      },

      submitForm() {
        this.loading = true
        
        if (!this.role.id) {
          this.role.scope = this.role.scope ? this.role.scope.name.toLowerCase() : ''
        }

        this.$store
            .dispatch(`Role/${ this.role.id ? 'update' : 'create' }`, this.role)
            .then( data => {
              this.loading = false
              this.$emit("update:display", false)
              this.$emit("on-submit", data.message)
              this.showMessage('saved')
            })
            .catch(error => {
              this.loading = false
              
              this.$refs.formValidator.setErrors(
                error.response.status === 422 ? error.response.data.errors : [] )

              if (error.response.status === 403) {
                this.showMessage('Request failed')
              }
            })
      },

      resetForm() {
        this.$refs.formValidator.reset()
        this.sendingRequest = false
      },
      
      showMessage(message) {
        this.message = message
        this.alert = true
      },

    },
    
  }
</script>

<style scoped>

  >>>.v-select__slot label {
    padding-top: 5px !important;
  }

  >>>.v-radio .v-label {
    font-size: .75rem;
    margin-top: 7px;
    letter-spacing: .5px;
  }

  .section-title {
    line-height: 20px;
    font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;
    font-size: .95rem !important;
    letter-spacing: .2px;
    color: #595959;
  }
</style>