<template>

  <v-row no-gutters class="grey lighten-5 custom-scroll overflow-y-auto h-100" style="max-height:580px">

    <div class="grey lighten-5 p-0 col-12" style="position:absolute; top:0px; left:0; z-index:1;">

<!-- BREADCRUMB -->
      <v-card flat tile class="row m-0 p-2 pl-3" height="35">

        <router-link :to="{name: 'home'}" class="primary--text text-decoration-none">
          {{'Home'}}
        </router-link>

        <v-icon size="14" dense class="p-0 ml-3 mr-3">mdi-chevron-right</v-icon>

        <router-link :to="{name: 'role'}" class="primary--text text-decoration-none">
          Role
        </router-link>

        <v-icon size="14" dense class="p-0 ml-3 mr-3">mdi-chevron-right</v-icon>
        
        <p class="text-secondary m-0 p-0"> Manage Role </p>
      </v-card>
<!-- BREADCRUMB eNDS-->

<!-- BANNER -->
      <v-card height="100" width="850" class="p-4 m-auto mt-5 row overflow-x-hidden">
        
        <v-icon size="25" dense color="secondary" class="">mdi-account-key-outline</v-icon>

        <h5 v-if="role" class="ml-5 mt-4" style="letter-spacing:0.8px">{{ role.name }}</h5>

        <v-spacer/>

        <div class="m-0 p-0 row mt-3 w-auto custom-scroll"><v-spacer/>
          <v-btn depressed small outlined text class="mr-3">
            <v-icon size="15" dense left>mdi-pencil</v-icon>
            Edit Role
          </v-btn>

          <v-btn depressed small outlined text>
            <v-icon size="15" dense left>mdi-account-multiple-plus-outline</v-icon>
            Assign Users
          </v-btn>
        </div>
      </v-card>
<!-- BANNER ENDS -->

<!-- PERMISSION TABS -->    
      <v-tabs v-model="tabs" fixed-tabs :color="color" height="35" dense class="m-auto mt-2" style="max-width:850px;">

        <v-tab href="#tabs-transaction" class="text-decoration-none role-tabs" active-class="text-dark">
          Transactions </v-tab>

        <v-tab href="#tabs-field" class="text-decoration-none role-tabs" active-class="text-dark">
          Fields </v-tab>

        <v-tab href="#tabs-nav" class="text-decoration-none role-tabs" active-class="text-dark">
          Navigation Bar </v-tab>

        <v-tab href="#tabs-users" class="text-decoration-none role-tabs" active-class="text-dark">
          Assigned Users (2) </v-tab>
      </v-tabs>

<!-- PERMISSION TABS ENDS --> 
      <v-divider class="m-0 p-0" />
    </div>

  <!-- PERMISSION CONTENT -->      
    <div :class="`grey lighten-5 p-0 m-0 mt-1 col-12 overflow-y-auto custom-scroll`"
      style="position:absolute; top:200px; left:0; z-index:1; height: calc(100vh - 260px);">

      <v-card flat tile width="850" class="m-auto h-100" color="transparent" :loading="loadingRole">
        <v-tabs-items v-model="tabs" class="transparent" v-if="role && !loadingRole">
          <v-tab-item value="tabs-transaction">
            
            <v-card flat tile width="215" class="overflow-x-visible h-100 d-none d-sm-block" color="transparent"
              style=" position:fixed; top:248px;">
              <v-navigation-drawer floating permanent class="h-100" style="margin-left:5px;">
                <v-list nav dense tile>
                  <v-list-item-group>
                    <v-list-item v-for="(permission, i) in role.transaction_permissions" :key="i" 
                      :class="`list-title`" style="min-height:30px; height:30px;"
                      @click.prevent="goToDiv(`${permission.module}`)">
                      
                      {{ permission.module }}
                    </v-list-item>
                  </v-list-item-group>
                </v-list>
              </v-navigation-drawer>
            </v-card>
            
            <v-card flat tile color="transparent" class="overflow-x-visible p-0 permission-tab-item-ml">
              <div :id="permission.module" v-for="(permission, i) in role.transaction_permissions" :key="i"
                class="lime lighten-5">                
                <v-divider class="m-0"/>
                <p class="m-0 p-0 pl-2 text-secondary text-caption font-weight-bolder">{{ permission.module }}</p>
                <v-divider class="m-0"/>
                <v-card tile flat height="auto" class="p-1 mb-3">
                  <transaction-permissions :role_id="role_id" :permissions="permission.transactions"/>
                </v-card>
              </div>
            </v-card>
          </v-tab-item>

          <v-tab-item value="tabs-field">
            <v-card flat tile width="215" class="overflow-x-visible h-100 d-none d-sm-block" color="transparent"
              style=" position:fixed; top:248px;">
              <v-navigation-drawer floating permanent class="h-100" style="margin-left:5px;">
                <v-list nav dense tile>
                  <v-list-item-group>
                    <v-list-item v-for="(permission, i) in role.field_permissions" :key="i" 
                      :class="`list-title`" style="min-height:30px; height:30px;"
                      @click.prevent="goToDiv(`${permission.transaction}`)">
                      
                      {{ permission.transaction }}
                    </v-list-item>
                  </v-list-item-group>
                </v-list>
              </v-navigation-drawer>
            </v-card>
            
            <v-card flat tile color="transparent" class="overflow-x-visible p-0 permission-tab-item-ml">
              <div :id="permission.transaction" v-for="(permission, i) in role.field_permissions" :key="i"
                class="lime lighten-5">
                <v-divider class="m-0"/>
                <p class="m-0 p-0 pl-2 text-secondary text-caption font-weight-bolder">{{ permission.transaction }}</p>
                <v-divider class="m-0"/>
                <v-card tile flat height="auto" class="p-1 mb-3">
                  <field-permissions :role_id="role_id" :permissions="permission.fields"/>
                </v-card>
              </div>
            </v-card>
          </v-tab-item>

          <v-tab-item value="tabs-nav">
            <v-card flat height="300" class="blue m-2">
              
            </v-card>
          </v-tab-item>

          <v-tab-item value="tabs-users">
            <v-card flat height="300" class="red m-2">
              
            </v-card>
          </v-tab-item>
        </v-tabs-items>

      </v-card>
    </div>    
  </v-row>
</template>

<script>

  import TransactionPermissions from './TransactionPermissions.vue'
  import FieldPermissions from './FieldPermissions.vue'

  export default {

    components: { TransactionPermissions, FieldPermissions },

    data() {
      return {
        tabs: null,
        role_id: null,
        role: null,
        color: this.$store.getters.theme.color,
        roles: [],
        loadingRole: true,
        isMounted: false,
      }
    },

    created() {
      this.role_id = this.$route.params.id
      this.findRole(this.role_id)
    },

    watch: {
      role: {
        deep: true,
        immediate:true,
        handler(val) {
          if (!val || !this.isMounted || !this.role_id) return
          
          this.updateRolePermissions()
        }
      }
    },

    mounted() {
      this.isMounted = true
    },

    methods: {
      
      findRole(id) {
        if (!id) return

        this.$store
            .dispatch('Role/find', { id })
            .then( role => {
              this.role = role
              this.loadingRole = false
            })
            .catch( error => console.log(error))
      },

      updateRolePermissions() {
        this.$store
            .dispatch('Role/updatePermission', this.role)
            .catch( error => console.log(error))
      },

      goToDiv(id) {
        document.getElementById(id).scrollIntoView()
      }

      // findTransactions() {
      //   this.$store
      //       .dispatch('Transaction/list', { trashed: 0 })
      //       .then( transactions => {
      //         this.transactions = transactions
      //       })
      //       .catch( error => console.log(error))
      // }
    },

  }
</script>

<style scoped>

  .permission-tab-item-ml {
    margin-left:230px;
    width: calc(100% - 235px);
  }

  @media screen and (max-width: 599.9px) {
    .permission-tab-item-ml {
      margin-left: 10px;
      width: calc(100% - 15px);
    }
  }
  >>>.role-tabs {
    letter-spacing: 0.3px;
    font-size: 10px;
  }

  .list-title {
    line-height: 10px;
    font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;
    font-size: .75rem !important;
    letter-spacing: .2px;
    color: #757575 !important;
    text-decoration:none;
  }

</style>