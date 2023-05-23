<template>
  <v-row no-gutters class="h-100">
    <v-col cols="12">
      <v-tabs v-model="tabs" height="40" :color="color">
        <v-tab href="#tabs-activity-timeline" class="text-decoration-none font-weight-bold"
          active-class="text--secondary font-weight-bold">
          <p class="m-0 text-caption text-capitalize">Activity Timeline</p>
        </v-tab>

        <v-tab href="#tabs-notes" class="text-decoration-none font-weight-bold"
          active-class="text--secondary">
          <p class="m-0 text-caption text-capitalize">Notes</p>
        </v-tab>

        <v-tab href="#tabs-task"  class="text-decoration-none font-weight-bold"
          active-class="text--secondary">
          <p class="m-0 text-caption text-capitalize">Tasks</p>
        </v-tab>
      </v-tabs>
      <v-divider class="m-0"/>
      
      <v-tabs-items v-model="tabs" >
        <v-tab-item value="tabs-activity-timeline">
          <v-card tile flat height="calc(100vh - 125px)" class="grey lighten-5 overflow-auto custom-scroll">

            <v-timeline dense class="mt-3">
              <v-timeline-item color="transparent" icon="mdi-crown-outline" icon-color="secondary" fill-dot>
                <v-card min-height="70" class="mr-5 p-3">
                  <p>Account updated</p>
                </v-card>
              </v-timeline-item>
              
              <v-timeline-item color="transparent" icon="mdi-crown-outline" icon-color="secondary" fill-dot>
                <v-card min-height="70" class="mr-5 p-3">
                  <p>Account updated</p>
                </v-card>
              </v-timeline-item>
              
              <v-timeline-item color="transparent" icon="mdi-crown-outline" icon-color="secondary" fill-dot>
                <v-card min-height="70" class="mr-5 p-3">
                  <p>Account updated</p>
                </v-card>
              </v-timeline-item>
              
              <v-timeline-item color="transparent" icon="mdi-crown-outline" icon-color="secondary" fill-dot>
                <v-card min-height="70" class="mr-5 p-3">
                  <p>Account updated</p>
                </v-card>
              </v-timeline-item>

            </v-timeline>
          </v-card>            
        </v-tab-item>
        
        <v-tab-item value="tabs-notes">
          <v-card tile flat color="">            
            <h1>activity-timeline</h1>
          </v-card>            
        </v-tab-item>

        <v-tab-item value="tabs-task">
          <v-card tile flat color="">
            <h1>activity-timeline</h1>
          </v-card>            
        </v-tab-item>
      </v-tabs-items>
    </v-col>

    <v-snackbar v-model="alert" :timeout="3000" left color="secondary darken-3"> {{ message }}
      <template v-slot:action="{ attrs }">
        <v-btn :color="color" icon v-bind="attrs" @click="alert = false" >
          <v-icon dense>mdi-close</v-icon>
        </v-btn>
      </template>
    </v-snackbar>
  </v-row>
</template>

<script>

  export default {

    data() {
      return {
        tabs: null,
        alert: false,
        message: null,
        color: this.$store.getters.theme.color,
      }
    },
    
    watch: {
      
    },

    methods: {

    },

    mounted() {
      
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
    margin-top: 4px !important;
  }

  >>>.v-text-field .v-input__control .v-input__slot .v-input__prepend-inner {
    margin-top: 4px !important;
  }

  >>>.v-text-field .v-input__control .v-input__slot label {
    margin-top: -4px !important;
    font-size: 1em;
  }
  
  >>>.v-text-field .v-input__control .v-input__slot label.v-label--active {
    margin: 0 0 0 5px !important;
  }

  >>>.v-text-field__details {
    margin: 2px !important;
  }

  >>>.v-timeline-item__body .theme--light .v-sheet {
    border-color: red !important;
  }

</style>