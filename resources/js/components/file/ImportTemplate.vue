<template>
  <div class="m-0 p-0">

    <slot name="activator" :on="exportData">
      <v-btn
        :ripple="false" small rounded
        @click.prevent="exportData"
        style="text-transform:none; font-size: small;">
        
        {{'Get template'}}

        <v-icon dense right color="green darken-2">mdi-download</v-icon>
      </v-btn>
    </slot>
  </div>
</template>

<script lang="js">

  import FileService from '../../services/File.js'
  
  export default {

    props: {
      file_name: { type: String, required: true },
      fields: { type: Array, required: true}
    },

    methods: {
      async exportData()
      {
        await (new FileService()).export(this.file_name, this.file_name, this.fields, 'csv')        
      }
    }

  }
</script>
