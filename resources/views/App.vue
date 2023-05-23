<template>
  <!-- auth -->
  <v-app class="grey" v-if="$store.getters.access_token && user">

    <rightnav-component :isMounted="isMounted" :display.sync="rightDrawer"/>

    <v-app-bar app height="45" flat dense tile color="white">

      <div class="row overflow-hidden w-100 pl-3 pr-3 border-0">
        <v-btn class="mt-2" plain icon small @click="toggleRightdrawer" :ripple="false">
          <v-icon color="grey darken-4" size="25">mdi-menu-open</v-icon>
        </v-btn>

        <v-spacer style="max-width: 10px"/>
        <navsearch-component class="d-sm-block d-none"/>
        <v-spacer/>

        <v-menu transition="slide-x-transition" bottom right offset-y rounded v-model="notifyMenu"
          class="bg-primary" :nudge-right="-20" :nudge-top="-10">
          <template v-slot:activator="{ on, attrs }">

            <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2" v-bind="attrs" v-on="on">
              <template v-slot:activator="{ on, attrs }">
                <v-btn small class="mt-3 mr-4" icon depressed color="white" dark v-bind="attrs" v-on="on"
                  @click.prevent="notifyMenu=true">
                  <v-badge color="error" content="6" overlap>
                    <v-icon size="19" dense color="grey darken-3"> mdi-bell-outline</v-icon>
                  </v-badge>
                </v-btn>
              </template>
              <span class="text-caption">notifications</span>
            </v-tooltip>

          </template>

          <!-- <v-card width="350px" height="400">
            <v-list-item-content class="justify-center">

            </v-list-item-content>
          </v-card> -->
        </v-menu>

        <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
          <template v-slot:activator="{ on, attrs }">
            <v-btn small class="mt-3 mr-2" color="white" icon depressed v-on="on" v-bind="attrs">
              <v-icon dense size="19" color="grey darken-3">mdi-help-circle-outline</v-icon> </v-btn>
          </template>
          <span class="text-caption">any question ?</span>
        </v-tooltip>

        <v-menu transition="slide-x-transition" bottom right offset-y rounded v-model="appMenu"
          :nudge-right="-20" :nudge-top="-8">
          <template v-slot:activator="{ on, attrs }">
        
            <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2"
              v-bind="attrs" v-on="on">
              <template v-slot:activator="{ on, attrs }">
                <v-btn small class="mt-3" icon depressed v-on="on" v-bind="attrs"
                  :fab="false" color="white" @click.prevent="appMenu=true">
                  <v-icon dense size="19" color="grey darken-3">mdi-apps</v-icon>
                </v-btn>
              </template>
              <span class="text-caption">my apps</span>
            </v-tooltip>
          </template>

          <v-card width="250" class="pt-3 grey lighten-5 user-select-none" height="350">
            <v-row no-gutters>
              <div role="button" v-for="(app, i) in apps" :key="i" class="m-1 p-1 mr-0"
                style="height:90px;  width:78px;">
                
                <v-hover v-slot="{ hover }">
                  <a :href="app.url" class="text-decoration-none">
                    <v-card :flat="hover ? false:true" :color="hover ? color:''"
                      class="h-100 p-0 pt-1 pb-2 lighten-5 row m-0 text-center">

                      <v-icon size="40" class="col-12 p-0" :color="hover ? 'white' : app.color">
                        {{ app.icon }}
                      </v-icon>
                      <small class="col-12 p-0 pt-1" :class="hover?'text-white font-weight-bold':''">
                        {{ app.title }}
                      </small>
                    </v-card>
                  </a>
                </v-hover>
              </div>
            </v-row>
          </v-card>
        </v-menu>

        <v-menu transition="slide-x-transition" bottom right offset-y rounded
          :nudge-right="-20" :nudge-top="-8">
          <template v-slot:activator="{ on, attrs }">
            <v-list-item-avatar dark v-bind="attrs" class="elevation-3"
              icon v-on="on" size="31" :color="user ? 'white' : 'black'">
              <v-img contain
                :src="user.profile_picture || '/images/default-profile.png'"/>
            </v-list-item-avatar>
          </template>

          <v-card width="250" class="p-0 grey lighten-5 user-select-none" height="350" v-if="user">
            <div class="row justify-center m-0 pt-10" style="height: 130px">
              <v-avatar color="green darken-3" class="elevation-2">
                <span class="white--text text-h5">{{ getInitials() }}</span>
              </v-avatar>
            </div>

            <div class="col col-12 justify-center white elevation-3 p-0 text-center"
              style="height: 220px; border-radius: 225px 0 0 0;">
              <p class="text-capitalize text-truncate mb-0 mt-3 p-1 h4 text-center h-fit-content w-100" >
                {{ getFullName().toLowerCase() }}
              </p>

              <p class="text-truncate mb-2 mt-1 w-auto pl-3 pr-3 card elevation-2 rounded-pill h-fit-content">
                {{user.email}}
              </p>
                            
              <div class="row justify-content-center m-0 mb-5 p-0 h-fit-content">
                <v-btn small rounded @click.prevent="viewProfile"
                  class="text-capitalize mt-2">{{ 'View Profile' }}
                </v-btn>
                <v-divider vertical class="mr-3 ml-2"/>
                <v-btn small rounded width="80" class="text-capitalize mt-2"
                  @click.prevent="$store.dispatch('logout', {id: user.id})">
                  {{'Sign out'}}
                </v-btn>
              </div>

              <v-chip color="green darken-3" outlined small class="text-capitalize user-select-none">
                {{user.type}} Account
                <v-icon size="13">mdi-account-outline</v-icon>
              </v-chip>
            </div>
          </v-card>
        </v-menu>
      </div>
    </v-app-bar>

    <v-main>
      <v-container fluid class="white h-100 m-0 p-0 border-0">
        <page-load :timeout="2000"/>

        <v-slide-x-transition hide-on-leave v-show="isMounted">
          <router-view/>
        </v-slide-x-transition>
      </v-container>

      <!-- App Notification -->
      <v-snackbar :value="$store.getters.alert" @input="$store.commit('alert', false)"
        color="secondary darken-3" :timeout="3000" left>
        {{ $store.getters.message }}
        <template v-slot:action="{ attrs }">
          <v-btn :color="color" icon v-bind="attrs" @click="$store.commit('alert', false)">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </template>
      </v-snackbar>
      <!-- App Notification -->
      
    </v-main>
  </v-app>

  <v-app v-else-if="user">
    <v-main>
      <router-view/>
    </v-main>
  </v-app>

  <!-- guest -->
  <v-app v-else>
    <v-app-bar v-if="isMounted" app class="grey lighten-4"
      style="z-index: 10" dense tile flat>
      <div class="d-flex align-stretch">
        <v-img src="/images/app-logo.png" max-width="50" max-height="50" />

        <span class="font-weight-bold textbase--text pt-1 text-uppercase">{{'kenmaxi'}}
				</span>
      </div>
    </v-app-bar>

    <v-main v-show="isMounted">
      <v-slide-x-transition hide-on-leave>
        <router-view/>
      </v-slide-x-transition>
    </v-main>
  </v-app>
</template>

<script>

export default {

  data() {
    return {
      user,

      notifyMenu: false,
      appMenu: false,
      rightDrawer: this.$store.getters.rightDrawer,
      isMounted: false,
      color: this.$store.getters.theme.color,

      apps: [
        { title:'Finance', icon:'mdi-finance', color:'red darken-3', url:'https://finance.kenmaxi.com'},
        { title:'Inventory', icon:'mdi-semantic-web', color:'green darken-3', url:'https://inventory.kenmaxi.com'},
        { title:'Purchase', icon:'mdi-cart-variant', color:'secondary darken-3', url:'https://purchase.kenmaxi.com' },
        { title:'Sales', icon:'mdi-sale', color:'green darken-3', url:'https://sales.kenmaxi.com' },
        { title:'Production', icon:'mdi-factory', color:'black darken-3', url:'https://production.kenmaxi.com' },
        { title:'CRM', icon:'mdi-account-question', color:'green darken-3', url:'https://crm.kenmaxi.com' },
      ],
    }
  },

  mounted()
  {
    setTimeout(() =>  this.isMounted = true , 1000)
  },

  methods: {

    getInitials()
    {
      if (!this.user) return

      if (this.user.first_name &&  this.user.last_name) {
        return (`${ (this.user.first_name).charAt(0) }${ (this.user.last_name).charAt(0) }`).toUpperCase()
      }
      else if (this.user.business_name) {
        return (this.user.business_name).charAt(0)
      }
    },

    getFullName()
    {
      if (!this.user) return
      
      if (this.user.first_name &&  this.user.last_name) {
        return (`${ this.user.first_name } ${ this.user.last_name }`).toUpperCase()
      }
      else {
        return this.user.business_name
      }
    },

    viewProfile()
    {
      if (this.$route.name === 'personal.detail') return
      this.$router.push({ name:'personal.detail' })
    },

    toggleRightdrawer()
    {
      this.rightDrawer = !this.rightDrawer
    }
    
  }

}
</script>

<style scoped>

>>>.v-input .v-input__control .v-input__slot {
  min-height: 0px !important;
  font-size: .8rem;
  padding: 1px 8px !important;
  margin-bottom: 2px !important;
  display: flex !important;
  align-items: center !important;
}

>>>.v-toolbar__content {
  padding: 0px 8px;
}
</style>
