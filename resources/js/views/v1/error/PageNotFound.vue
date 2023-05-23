<template>
  <div class="container pl-3 pr-3">
    <div class="d-flex">
      <div class="mr-2" style="color: grey; font-size: 60px;">
        404
      </div>
      
      <div class="text-capitalize pt-9"
        style="font-size: 15px; letter-spacing:1px; font-family: calibri">
        Page not found
      </div>
    </div>

    <h6>  
      The page you requested cannot be found. The page might have been removed, had its name changed, or is temporarily unavailable.
    </h6><br>
      
      <b>Quick Fix:</b><br>

      If you manually entered the URL in the address bar, verify that it is spelt correctly.<br>
      Open the <a class="text-decoration-none" href="/">home page </a> and select the link to the information you need.<br>
      Or you will be redirected to {{ home_page }} homepage automatically in {{ duration }} seconds.<br>
    
  </div>
</template>


<script>

  export default {

    data() {
      return {
        duration: 100,
        home_page: null
      }
    },

    mounted()
    {
      this.home_page = window.location.hostname
      setInterval( () => {
        if (--this.duration == 0) this.$router.push({name: 'home'})          
      }, 1000)
    },

  }

</script>