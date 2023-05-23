<template>
  <v-hover v-slot="{ hover }" close-delay="1200">
    <div class="d-flex m-0 p-0 transparent rounded-circle overflow-visible" :style="`max-width:${size}px`">
      <v-card class="p-0 rounded-circle" :width="size" :height="size" :min-width="size" :min-height="size"
        elevation="3" >
        <input type="file" accept="image/*" class="d-none" ref="file" @change="change" @click="clearCache"/>
        <v-img :src="src || defaultSrc" class="rounded-circle" eager contain
          :width="size" :height="size" :min-width="size" :min-height="size"/>
      </v-card>
      
      <v-fade-transition v-if="editable">
        <v-col v-if="hover" class="position-relative"
          style="width:fit-content; top: -30px; right: 0px;">
          <v-btn icon depressed @click="browse()">
            <v-icon size="18" color="secondary">mdi-camera</v-icon>
          </v-btn>
          <v-btn v-if="src" icon depressed @click="clear()">
            <v-icon size="18" color="secondary">mdi-close</v-icon>
          </v-btn>
        </v-col>
      </v-fade-transition>
      
      <v-snackbar v-model="alert" :timeout="3000" left color="secondary darken-1 text-white"> {{ message }}
        <template v-slot:action="{ attrs }">
          <v-btn :color="color" icon v-bind="attrs" @click="alert = false" >
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </template>
      </v-snackbar>
    </div>
  </v-hover>
</template>

<script>

  export default {
    props: {
      value: File,
      defaultSrc: String,
      src: String,
      editable: { type: Boolean, default: true },
      size: { type: Number, default: 90 },
      user_id: { required: true }
    },

    data() {
      return {
        file: null,
        color: this.$store.getters.theme.color,
        alert: false,
        message: null,
      }
    },

    methods: {

      clearCache(e) {
        e.target.value = null
      },

      browse() {
        this.$refs.file.click()
      },

      async change(e) {
        this.file = e.target.files[0]

        if (!this.file || !this.user_id ) return

        this.$emit('input', this.file)

        let formData = new FormData()
        formData.append('user_id', this.user_id)
        formData.append('profile_picture', this.file)
          
        await this.$store.dispatch('saveProfilePicture', formData)
                  .then(res => {
                    this.$emit('set-src', res.data.url)
                    this.showMessage(res.data.message)
                  })
                  .catch(error =>  console.log(error) )
      },

      clear() {
        if ( !this.user_id ) return

        return this.$store
            .dispatch('removeProfilePicture', { id: this.user_id })
            .then(res => {
              this.file = null
              this.$emit('set-src', null)
              this.$emit('input', this.file)
              this.showMessage(res.data.message)
            })
            .catch(error =>  console.log(error) )
      },

      showMessage(message) {
        this.message = message
        this.alert = true
      }
    }
    
  }
</script>
