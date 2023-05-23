<template>
  <div class="m-0 p-0">
    <v-card>
      <v-toolbar outlined dense flat  class="pt-3 pb-4 pr-3">
        <p class="text-h5 text-secondary text-dark pl-1">{{ title }}</p>
        <v-spacer/>
        <v-btn @click="closeDialog" icon small depressed style="margin-top:-20px">
          <v-icon id="close-button">mdi-close</v-icon>
        </v-btn>
      </v-toolbar>

      <v-card-text class="mt-2 pt-5">
        <validation-observer ref="linkValidator" v-slot="{ invalid }">
          <v-form @submit.prevent="addLink">
            
            <div class="rounded d-flex flex-row text-info m-0 p-0">
              <v-icon left size="25" dense color="secondary" style="margin-top:-10px">mdi-information-outline</v-icon>
              <p class="text-caption text-secondary">
                Ensure that the text you wish to make a link is highlighted.</p>
            </div>
            

            <validation-provider v-slot="{ errors }" rules="min:2|max:100" name="href">
              <v-text-field v-model.trim="href" label="Link" outlined dense color="grey"
                :error-messages="errors" error-count="1" prepend-inner-icon="mdi-link"/>
            </validation-provider>

            <div class="col-12 row m-0 p-0">
              <v-spacer/>
              <v-btn class="col-4 text-capitalize" type="submit" :disabled="invalid || !href">
                {{ 'add' }}
              </v-btn>
            </div>
            
          </v-form>
        </validation-observer>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
  export default {

    props: {
      display: { type: Boolean, required: true },
      title: { type: String, default: "Add a link" },
      params: { type: Object, default: null},
    },

    data() {
      return {
        color: this.$store.getters.theme.color,
        href: null
      }
    },

    methods: {

      closeDialog() {
        this.href = null
        this.$emit("update:display", false)
      },

      addLink() {
        this.$emit("linked",  { 'href': this.href, 'params': this.params })
        this.closeDialog()
      },
    },
  }
</script>