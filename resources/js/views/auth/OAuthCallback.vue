<template>
  <div>

  </div>
</template>


<script>

  function token_payload(code) {
    return {
      grant_type: 'authorization_code',
      client_id: process.env.MIX_CLIENT_ID,
      redirect_uri: process.env.MIX_CLIENT_REDIRECT,
      code,
      code_verifier
    }
  }

  import axios from 'axios'

  export default {
    created() {
      this.getToken()
    },

    methods: {
      
      async getToken()
      {
        await axios.post(process.env.MIX_AUTH_SERVER_TOKEN_ENDPOINT,
                          token_payload(this.$route.query.code))
                    .then( ({data}) => {
                      this.$store.commit('authorized', data)
                      window.location.reload()
                    })
                    .catch( error => window.location.replace(process.env.MIX_BASE_URL))
      }
    },
  }
</script>
