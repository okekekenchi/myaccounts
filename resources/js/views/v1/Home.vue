<template>
  <div class="col col-12" style="height: calc(100vh - 45px);"
    v-if="$store.getters.access_token && user">

    <div class="row justify-content-center mt-5">
      <div class="p-3 rounded-circle"
        style="border: 3px solid #424242; width:fit-content;">
        <!-- <avatar-profile v-model="profilePicture" :size="120"
          :src.sync="profile_picture"
          :defaultSrc="'/images/default-profile.png'"
          @set-src="setSrc($event)"
          :user_id.sync="user.id"
          :editable="user.id !== $route.params.id"/> -->

        <avatar-profile :size="120"
          :src.sync="src"
          :defaultSrc="'/images/default-profile.png'"
          @set-src="setSrc($event)"
          :user_id.sync="user.id"
          :editable="user.id !== $route.params.id"/>
        <v-spacer/>
      </div>
    </div>

    <div class="text-center mt-5">
      <p class="h2 grey--text text--darken-3" v-if="user.first_name">
        {{ 'Welcome' }}, {{ user.first_name }} {{ user.last_name || '' }}.</p>
      <p class="h2 grey--text text--darken-3" v-else>
        {{ 'Welcome' }}, {{ user.business_name || '' }}</p>
    </div>
    
    <div class="w-100 overflow-y-auto custom-scroll mt-4" >
      <v-card flat rounded class="grey lighten-5 m-auto" max-width="800">
        
        
      </v-card>
    </div>
    
  </div>
</template>

<script>

  export default {

    data() {
      return {
        color: this.$store.getters.theme.color,
        src: null,
        user        
      }
    },

    mounted() {
      this.$store.commit('user', this.user)
      this.setSrc(this.user.profile_picture)
    },

    methods: {

      setSrc(src) {
        this.src = src
        this.$store.commit('saveProfilePicture', src)
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

  >>>.v-text-field .v-input__control .v-input__slot .v-input__prepend-inner {
    margin-top: 3px !important;
  }

  >>>.v-text-field .v-input__control .v-input__slot .v-select__slot label {
    margin-top: 0px !important;
    font-size: 0.9em;
  }

  >>>.v-text-field .v-input__control .v-input__slot label {
    margin-top: -4px !important;
    font-size: .95rem;
  }
  
  >>>.v-text-field .v-input__control .v-input__slot label.v-label--active {
    margin: 0 0 0 5px !important;
  }

  >>>.v-text-field__details {
    margin: 2px !important;
  }
</style>