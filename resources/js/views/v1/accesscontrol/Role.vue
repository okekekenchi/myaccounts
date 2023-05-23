<template>

  <v-row no-gutters>

    <v-col class="h-100 m-0 p-0">
      
      <v-card flat dense tile class="row m-0 p-0 pt-2 pr-4 pl-2 overflow-hidden" height="40">

        <v-select v-model="perPage" dense hide-selected :menu-props="{ contentClass:'custom-scroll'}"
          hide-details solo flat  color="secondary--text" :items="pageOptions" height="25" suffix="Per Page"
          style="max-width: 130px; font-size: 0.75rem;" background-color="transparent"/>
    
        <v-spacer />

        <div class="m-0 p-0 mb-2 mr-3">
          <v-tooltip v-if="!trashed" bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
            <template v-slot:activator="{ on, attrs }">
              <v-btn icon small color="secondary" dark v-bind="attrs" v-on="on" @click.stop="openDialog">
                <v-icon size="18" >mdi-plus</v-icon>
              </v-btn>
            </template>
            <span class="text-caption">Add Role</span>
          </v-tooltip>

          <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
            <template v-slot:activator="{ on, attrs }">
              <v-btn icon small color="secondary" dark v-bind="attrs" v-on="on" @click="refreshTable">
                <v-icon size="17">mdi-refresh</v-icon>
              </v-btn>
            </template>
            <span class="text-caption">Refresh Table</span>
          </v-tooltip>
          
          <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
            <template v-slot:activator="{ on, attrs }">
              <v-btn v-if="trashed" icon small color="secondary" dark v-bind="attrs" v-on="on" @click="toggleView">
                <v-icon >mdi-file-swap</v-icon>
              </v-btn>
              <v-btn v-else icon small color="secondary" dark v-bind="attrs" v-on="on" @click="toggleView">
                <v-icon  size="17">mdi-delete-restore</v-icon>
              </v-btn>
            </template>
            <span class="text-caption">{{ trashed ? 'Active Records' : 'Recycle Bin'}}</span>
          </v-tooltip>

          <v-tooltip v-if="trashed" bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
            <template v-slot:activator="{ on, attrs }">
              <v-btn icon small color="secondary" dark v-bind="attrs" v-on="on" @click="clearTrash">
                <v-icon size="17" >mdi-delete-empty</v-icon>
              </v-btn>
            </template>
            <span class="text-caption" >Clear trash</span>
          </v-tooltip>
          
          <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
            <template v-slot:activator="{ on, attrs }">
              <v-btn depressed small icon @click.stop="filterDialog=!filterDialog" class="m-0 p-0" v-bind="attrs" v-on="on">
                <v-icon size="17" color="primary lighten-2">mdi-filter-plus</v-icon>
              </v-btn>
            </template>
            <span class="text-caption">Filter</span>
          </v-tooltip> 
        </div>

        <p v-if="totalRows" class="text-secondary m-0 mt-1 ml-3 mr-3 d-none d-sm-block">
          {{ currentPage + 1 }} - {{ canGoNext ? currentPage + perPage : totalRows }} of {{ totalRows }}</p>

        <div class="m-0 p-0 mb-2" v-if="canGoNext || canGoPrev">
          <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
            <template v-slot:activator="{ on, attrs }">
              <v-btn icon small color="secondary" dark v-bind="attrs" v-on="on" @click="paginatePrev"
                :disabled="!canGoPrev">
                <v-icon >mdi-chevron-left</v-icon>
              </v-btn>
            </template>
            <span class="text-caption">previous</span>
          </v-tooltip>
          
          <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
            <template v-slot:activator="{ on, attrs }">
              <v-btn icon small color="secondary" dark v-bind="attrs" v-on="on" @click="paginateNext"
                :disabled="!canGoNext">
                <v-icon >mdi-chevron-right</v-icon>
              </v-btn>
            </template>
            <span class="text-caption">next</span>
          </v-tooltip>
        </div>
        
      </v-card>

      <v-card tile flat color="grey lighten-5">

        <v-card-text style="min-height:530px;">
          <b-table outlined sticky-header="500px" :responsive="true" primary-key="id"
            :items.sync="roleItemsProvider" small :busy.sync="isBusy" show-empty no-local-sorting
            ref="roleTableRef" id="role-table" :fields="header" no-border-collapse hover
            :current-page.sync="currentPage" :per-page.sync="perPage"  :sort-by.sync="sortBy"
            :filter.sync="filter" :filter-function="filterRoles" :sort-desc.sync="sortDesc">

            <template #table-busy>
              <b-skeleton-table :rows="12" :columns="header.length" :table-props="{ bordered: true }" 
                hide-header :animation="null"/>
            </template>
           
            <template #empty="scope">
              <div class="w-100 pt-10 pb-10 text-center" style="min-height:450px">
                <v-card flat class="m-auto pt-10" width="200">
                  <v-icon size="100"> {{trashed?'mdi-delete-empty-outline':'mdi-file-search'}} </v-icon>
                  <p class="text-caption text--secondary text--lighten-1 mt-5">{{ scope.emptyText }}</p>
                </v-card>
              </div>
            </template>

            <template #emptyfiltered="scope">
              <div class="w-100 pt-10 pb-10 text-center" style="min-height:450px">
                <v-card flat class="m-auto pt-10" width="200">
                  <v-icon size="100"> {{trashed?'mdi-delete-empty-outline':'mdi-file-search'}} </v-icon>
                  <p class="text-caption text--secondary text--lighten-1 mt-5">{{ scope.emptyFilteredText }}</p>
                </v-card>
              </div>
            </template>

            <template #cell(name)="data">
              <v-row no-gutters dense>
                <v-col cols="12">
                  <router-link :to=" {name: 'editRole', params: { 'id': data.item.id } } " 
                    class="primary--text v-list-item__title tdata text-decoration-none">
                    {{ data.item.name}}
                  </router-link>
                </v-col>
              </v-row>
            </template>

            <template #cell(actions)="row">
              <v-hover v-slot="{ hover }" >
                <v-card :flat="hover ? false:true" width="fit-content" class="p-0 pr-2 pl-2 overflow-hidden"
                  max-height="25">
                  
                  <v-tooltip v-if="!trashed" bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn icon small @click="editRole(row.item)" color="secondary"
                        dark v-bind="attrs" v-on="on" tile>
                        <v-icon dense small>mdi-application-edit-outline</v-icon>
                      </v-btn>
                    </template>
                    <span class="text-caption">edit role</span>
                  </v-tooltip>

                  <v-tooltip v-if="trashed" bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn icon small @click="restoreRole(row.item.id)" tile
                        dark v-bind="attrs" v-on="on" color="secondary">
                        <v-icon dense small>mdi-backup-restore</v-icon>
                      </v-btn>
                    </template>
                    <span class="text-caption">restore role</span>
                  </v-tooltip>
                  
                  <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn icon small @click="deleteRole(row.item.id, `${trashed?'hard':'soft'}`)"
                        dark v-bind="attrs" v-on="on" color="secondary" tile>
                        <v-icon dense small>mdi-delete-outline</v-icon>
                      </v-btn>
                    </template>
                    <span class="text-caption p-0">{{ trashed?'permanently delete role': 'delete role' }}</span>
                  </v-tooltip>
                </v-card>
              </v-hover>
            </template>
            
          </b-table>
        </v-card-text>
      </v-card>
    </v-col>
    
    <v-dialog v-model="filterDialog" origin="0,0" transition="dialog-bottom-transition" max-width="400px" hide-overlay>
      <v-toolbar color="teal darken-1" dense tile>
        <v-toolbar-title class="text-white">Filter</v-toolbar-title>
        <v-spacer/>
        <v-toolbar-items>
          <v-btn dark icon @click="filterDialog=false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-toolbar-items>
      </v-toolbar>
      <v-card flat tile height="400" max-height="450" class="custom-scroll p-3 grey lighten-5">
        <v-text-field v-model="filter" prepend-inner-icon="mdi-magnify" dense outlined class="rounded"
          clearable style="font-size:0.9rem;" color="grey"/>
        <v-divider class="mt-0"/>
      </v-card>
    </v-dialog>    

    
    <v-navigation-drawer v-model="roleDialog" app temporary right width="400">
      <v-card-title :class="`${color} lighten-5`" class="position-fixed w-100" style="height:50px; z-index:1;">
        <h6 class="text-black text-uppercase pt-1" v-text="'Role'"></h6>
        <v-spacer/>
        <v-btn small depressed icon @click.stop="roleDialog = false">
          <v-icon size="20">mdi-close</v-icon>
        </v-btn>
      </v-card-title>

      <role-form :role="role" @on-submit="formSubmitted($event)" :display.sync="roleDialog"/>      
    </v-navigation-drawer>

    <v-snackbar v-model="alert" :timeout="3000" left color="secondary darken-1"> {{ message }}
      <template v-slot:action="{ attrs }">
        <v-btn :color="color" icon v-bind="attrs" @click="alert = false" >
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </template>
    </v-snackbar>
  </v-row>
</template>

<script>

  export default {

    data() {
      return {
        filterDialog: false,
        alert: false,
        message: null,
        deleteLoading: false,
        restoreLoading: false,
        refreshLoading: false,
        trashed: 0,
        roleDialog: false,
        role: {},
        color: this.$store.getters.theme.color,
        showAdvancedFilter: true,
        filterOn: ['label'],
        sortBy: 'name',
        sortDesc: true,
        totalRows: 0,
        currentPage: 0,
        perPage: 10,
        pageOptions: [10, 15, 25, 50, 100],
        sortDirection: 'asc',
        isBusy: false,
        filter: '',
        searchableFields: [
          {
            name: 1,
            label: 'Searchable fields',
            children: [
              { name: 'name', label: 'Name' },
              { name: 'label', label: 'Label' },
              { name: 'created_by', label: 'Created by' }
            ],
          },
        ],
        canGoNext: false,
        canGoPrev: false,
        items: [],
        header: [
          { label: 'ROLES', key: 'name', sortable:true, thClass: 'theader', tdClass: 'tdata pl-4' },
          { label: 'USERS', key: 'users', sortable:true, thClass: 'theader', thStyle: { width: '120px'}, tdClass: 'tdata pl-4' },
          { label: 'CREATED BY', key: 'created_by', sortable:true, thClass: 'theader', tdClass: 'tdata pl-4', thStyle: { width: '80px' } },
          { label: 'UPDATED BY', key: 'updated_by', sortable:true, thClass: 'theader', tdClass: 'tdata pl-4', thStyle: { width: '80px' } },
          { label: 'ACTION', key: 'actions', thClass: 'theader pl-3', thStyle: { width: '120px' } },
        ],
        form: {},
        fields: []
      }
    },
    
    watch: {
      totalRows() {
        this.togglePaginateButtons()
      },

      currentPage() {
        this.togglePaginateButtons()
      },

      perPage() {
        this.currentPage = 0
        this.togglePaginateButtons()
      },

      trashed() {
        this.refreshTable()
      }
    },
    
    methods: {

      /**
       * Toggles between active and inactive records
       */
      toggleView() {
        this.currentPage = 0
        this.trashed = this.trashed ? 0 : 1
      },

      togglePaginateButtons()
      {
        this.canGoNext = (this.currentPage +  this.perPage) < this.totalRows
        this.canGoPrev = this.currentPage >= this.perPage
      },

      editRole(role)
      { 
        this.roleDialog = true
        this.role = role
      },

      showMessage(message) {
        this.message = message
        this.alert = true
      },

      formSubmitted(message)
      {
        this.showMessage(message)
        this.refreshTable()
      },

      deleteRole(id, type = 'soft')
      {
        this.$store
            .dispatch('Role/delete', { id, type })
            .then(data => this.refreshTable() )
            .catch(error => console.log(error) )
      },

      restoreRole(id)
      {
        this.$store
            .dispatch('Role/restore', { id })
            .then(data => this.refreshTable() )
            .catch( error => console.log(error) )
      },

      clearTrash()
      {
        this.$store
            .dispatch('Role/clearTrash')
            .then(data => this.refreshTable() )
            .catch( error => console.log(error) )
      },

      openDialog()
      {
        this.role = {}
        this.roleDialog = true
      },

      roleItemsProvider (ctx)
      {
        ctx.currentPage = this.currentPage
        let selectedColumns = [ 'id', 'name', 'users', 'scope', 'created_by', 'updated_by' ]

        return this.$store
            .dispatch('Role/dataTable', { trashed: this.trashed, payload: ctx, selectedColumns })
            .then(data => {
              this.items = data.records
              this.totalRows = data.recordsFiltered
              return this.items
            }).catch(error => {
              console.log(error)
              return []
            })
      },

      paginateNext() {
        if ( this.canGoNext ) this.currentPage += this.perPage
      },

      paginatePrev() {
        if ( this.canGoPrev) this.currentPage -= this.perPage
      },

      filterRoles ()
      {
        return true
      },

      refreshTable() {
        this.$refs.roleTableRef.refresh()
      }
    },

    mounted() {
      $('.v-data-table__wrapper').addClass('custom-scroll')
      $('.table-responsive').addClass('custom-scroll')
    }
  }
</script>

<style scoped>

  >>>.v-treeview--dense .v-treeview-node__root {
      min-height: 25px;
  }

  >>>.v-treeview .v-treeview-node .v-treeview-node__root button {
    font-size: 17px;
    max-width: fit-content;
  }

  >>>.v-treeview-node__content .v-treeview-node__label {
    font-size: 0.75em;

  }

  .v-application--is-ltr .v-treeview-node__checkbox {
    margin-left: 1px;
  }
  >>>.v-text-field .v-input__control .v-input__slot {
    min-height: 0 !important;
    padding: 1px 8px !important;
    margin-bottom: 2px !important;
    display: flex !important;
    align-items: center !important;
  }

  >>>.v-text-field .v-input__control .v-input__slot .v-input__append-inner {
    margin-top: 6px !important;
  }

  >>>.v-text-field .v-input__control .v-input__slot label {
    margin-top: -4px !important;
    font-size: 0.9em;
  }
  
  >>>.v-text-field .v-input__control .v-input__slot label.v-label--active {
    margin: 0 0 0 5px !important;
  }

  >>>.v-text-field__details {
    margin: 2px !important;
  }

  >>>.b-table tbody tr td {
    max-width: 100px !important;
  }

  >>>.tdata {
    font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;
    font-size: .75rem !important;
    letter-spacing: .5px !important;
    color: #595959 !important;
  }

  >>>.theader {
    background-color: #fafafa !important;
    min-width: 120px !important;
    line-height: 20px !important;
    font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;
    font-size: .65rem !important;
    letter-spacing: .3px !important;
    color: #1d1c1c !important;
  }

  >>>.theader div {
    margin-left: 10px !important;
  }
</style>