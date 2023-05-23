<template>

  <div class="grey lighten-5 p-0 pl-1 rounded" v-if="permissions">

    <div v-for="permission in permissions" :key="permission.id" style="height:30px;"
      class="p-0 d-flex col-12 mb-1">
      
      <p class="v-list-item__title permission-title text-secondary mt-4">{{ permission.name.toLowerCase() }}</p>

      <v-select v-model="permission.scope" :menu-props="{ contentClass:'custom-scroll', maxHeight: '200px' }"
        background-color="transparent" dense solo flat :items="scopes" hide-selected color="grey"
        style="font-size:0.75rem; min-width:90px; max-width:110px; margin-top:-5px" />

      <v-spacer/>

      <v-sheet class="m-0 p-0 pt-1" min-width="170" max-width="370" height="30">

        <v-slide-group v-model="permission.can" multiple show-arrows="always">
          <v-slide-item v-for="can in cans" :key="can" v-slot="{ active, toggle }" :value="can">
          
            <v-btn class="text-caption text-lowercase mx-1 px-1 pl-2" depressed rounded
              @click="toggle" icon x-small height="20" width="auto"> {{ can }}
                <v-icon right x-large dense :color="active?'success':'grey lighten-3'">
                  mdi-circle-small
                </v-icon>
            </v-btn>
          </v-slide-item>
        </v-slide-group>
      </v-sheet>

    </div>
  </div>
</template>

<script>
  export default {

    props: [ 'role_id', 'permissions' ],

    data() {
      return {
        model: {},
        scopes: ['global', 'territory', 'restricted'],
        cans: ['create', 'edit', 'view', 'delete', 'restore', 'forget', 'import', 'export'],
        // view log, clear log
        color: this.$store.getters.theme.color,
      }
    },

    methods: {
      
      
    }

  }

</script>

<style>

  >>>.v-text-field .v-input__control .v-input__slot {
    min-height: 0 !important;
    padding: 1px 8px !important;
    margin-bottom: 2px !important;
    display: flex !important;
    align-items: center !important;
  }

  .permission-title {
    font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;
    font-size: .8rem !important;
    letter-spacing: .2px;
    text-decoration:none;
    min-width: 90px;
    max-width: 100px;
  }
</style>