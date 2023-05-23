<template>

  <div class="text-center">
    <v-dialog v-model="loadingDialog" persistent eager content-class="elevation-0"
      overlay-color="transparent">
      <v-row no-gutters align-content="center" justify="center" dense style="height: 36px;">
        <v-progress-circular indeterminate :color="`${color} darken-4`" :size="25" :width="2"/>
      </v-row>
    </v-dialog>
  </div>

</template>

<script>
  export default {

    props: [ 'timeout' ],

    data() {
      return {
        loadingDialog: true,
        color: this.$store.getters.theme.color,
      }
    },

    mounted() {
      setTimeout(() => {
        this.loadingDialog = false
      }, this.timeout);
    }
  }
</script>
