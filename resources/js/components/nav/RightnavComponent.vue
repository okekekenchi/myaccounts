<template>
  <v-navigation-drawer v-model="rightDrawer" app width="250" >

    <v-row class="p-3 pl-5 pb-7" no-gutters>
      <div class="row">
        <v-img src="/images/app-logo.png" alt="logo" max-width="44px" max-height="44px" />

        <span class="font-weight-bold textbase--text pt-1 text-uppercase">{{'kenmaxi'}}
				</span>
      </div>
      <v-spacer/>

      <v-menu id="creation-menu" transition="slide-x-transition"
        offset-x offset-y :nudge-right="17" :nudge-top="1" :nudge-width="250"
        bottom right content-class="white custom-scroll lighten-5">

        <template  v-slot:activator="{ on, attrs }">
          <v-btn v-bind="attrs" v-on="on" rounded
            :color="`${color} lighten-5`" class="white text-black darken-1 mt-2" elevation="4" >
            <v-icon size="30" left class="p-2 pl-4">mdi-plus</v-icon>
            <b>Create</b>
          </v-btn>
        </template>

        <v-card class="grey lighten-5 user-select-none">
          <v-row no-gutters>
            <div role="button" v-for="(item, i) in items" :key="i" class="m-1 p-1 mr-0"
              style="height:90px;  width:78px;">
              
              <v-hover v-slot="{ hover }">
                <a :href="item.url" class="text-decoration-none">
                  <v-card :flat="hover ? false:true" :color="hover ? color:''"
                    class="h-100 p-0 pt-1 pb-2 lighten-5 row m-0 text-center">

                    <v-icon size="40" class="col-12 p-0" :color="hover ? 'white' : item.color">
                      {{ item.icon }}
                    </v-icon>
                    <small class="col-12 p-0 pt-1" :class="hover?'text-white font-weight-bold':''">
                      {{ item.title }}
                    </small>
                  </v-card>
                </a>
              </v-hover>
            </div>
          </v-row>
        </v-card>
      </v-menu>
    </v-row>

    <v-list nav dense shaped height="calc(100vh - 90px)"
      class="overflow-y-auto custom-scroll" >

      <v-list-item link :to="{name:'home'}" class="text-decoration-none" dense
        exact exact-active-class="green lighten-5">
        <v-list-item-icon class="mr-2">
          <v-icon color="green darken-4">mdi-account-box-multiple-outline</v-icon>
        </v-list-item-icon>
        <v-list-item-title class="list-title">{{ 'Home' }}</v-list-item-title>
      </v-list-item>

      <v-list-item link :to="{name:'personal.detail'}" class="text-decoration-none" dense
        exact exact-active-class="green lighten-5">
        <v-list-item-icon class="mr-2">
          <v-icon color="green darken-4">mdi-account-details</v-icon>
        </v-list-item-icon>
        <v-list-item-title class="list-title">Personal Detail</v-list-item-title>
      </v-list-item>

      <v-list-item link :to="{name:'signature'}" class="text-decoration-none" dense
        exact exact-active-class="green lighten-5">
        <v-list-item-icon class="mt-1 mr-2">
          <v-icon color="green darken-4">mdi-draw</v-icon>
        </v-list-item-icon>
        <v-list-item-title class="list-title">Signature</v-list-item-title>
      </v-list-item>

      <v-list-item link :to="{name:'user'}" class="text-decoration-none" dense
        exact exact-active-class="green lighten-5">
        <v-list-item-icon class="mr-2">
          <v-icon color="green darken-4">mdi-account-multiple</v-icon>
        </v-list-item-icon>
        <v-list-item-title class="list-title">Users</v-list-item-title>
      </v-list-item>

      <v-list-item link :to="{name:'security'}" class="text-decoration-none" dense
        exact exact-active-class="green lighten-5">
        <v-list-item-icon class="mr-2">
          <v-icon color="green darken-4">mdi-security</v-icon>
        </v-list-item-icon>
        <v-list-item-title class="list-title">Security</v-list-item-title>
      </v-list-item>

      <v-list-item link :to="{name:'api.key'}" class="text-decoration-none" dense
        exact exact-active-class="green lighten-5">
        <v-list-item-icon class="mr-2">
          <v-icon  color="green darken-4">mdi-api</v-icon>
        </v-list-item-icon>
        <v-list-item-title class="list-title">API Key</v-list-item-title>
      </v-list-item>

      <v-list-item link :to="{name:'data.privacy'}" class="text-decoration-none" dense
        exact exact-active-class="green lighten-5">
        <v-list-item-icon class="mr-2">
          <v-icon color="green darken-4">mdi-file-hidden</v-icon>
        </v-list-item-icon>
        <v-list-item-title class="list-title">Data & Privacy</v-list-item-title>
      </v-list-item>

      <v-list-item link :to="{name:'role'}" class="text-decoration-none" dense
        exact exact-active-class="green lighten-5">
        <v-list-item-icon class="mr-2">
          <v-icon  color="green darken-4">mdi-account-key</v-icon>
        </v-list-item-icon>
        <v-list-item-title class="list-title">Roles</v-list-item-title>
      </v-list-item>

      <v-list-item link :to="{name:'activity'}" class="text-decoration-none" dense
        exact exact-active-class="green lighten-5">
        <v-list-item-icon class="mr-2">
          <v-icon  color="green darken-4">mdi-history</v-icon>
        </v-list-item-icon>
        <v-list-item-title class="list-title">Activity Log</v-list-item-title>
      </v-list-item>

      <v-list-item link :to="{name:'payment.subscription'}" class="text-decoration-none" dense
        exact exact-active-class="green lighten-5">
        <v-list-item-icon class="mr-2">
          <v-icon  color="green darken-4">mdi-cash-check</v-icon>
        </v-list-item-icon>
        <v-list-item-title class="list-title">Payment & Subscription</v-list-item-title>
      </v-list-item>

      <v-list-item link :to="{name:'about'}" class="text-decoration-none" dense
        exact exact-active-class="green lighten-5" >
        <v-list-item-icon class="mr-2">
          <v-icon  color="green darken-4">mdi-information-variant</v-icon>
        </v-list-item-icon>
        <v-list-item-title class="list-title">{{'About'}}</v-list-item-title>
      </v-list-item>
    </v-list>

  </v-navigation-drawer>
</template>

<script>

  export default {

    props: {
      display: { type: Boolean, default: false},
      isMounted: { type: Boolean, default: false }
    },

    data() {
      return {
        rightDrawer: this.display,
        color: this.$store.getters.theme.color,
        user: {},
        items: [
          { title: 'User', icon: 'mdi-account', color:'secondary darken-3', url: 'users?form=1' },
          { title: 'Signature', icon: 'mdi-draw', color:'secondary darken-3', url: 'signature?form=1' },
          { title: 'Role', icon: 'mdi-key', color:'secondary darken-3', url: 'roles?form=1' },
        ],
      }
    },

    watch: {
      display(val) {
        this.rightDrawer = val
      },

      rightDrawer(drawn)
      {
        this.$emit('update:display', drawn)
				this.$store.commit('setRightDrawer', drawn)
      }
    },

    methods: {

    },

    mounted() {
      $('.v-navigation-drawer__content').addClass('custom-scroll')
    }

  }
</script>

<style scoped>

  .list-title {
    font-family: calibri,serif;
    font-size: 0.9rem !important;
    font-weight: 500 !important;
  }
  >>> .v-navigation-drawer__border {
    background-color: white !important;
  }

</style>
