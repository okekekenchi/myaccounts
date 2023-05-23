<template>
  <div  class="col col-12" style="height: calc(100vh - 45px);">

    <v-card flat class="row m-0 p-0 pr-2" height="34px">
      <v-select v-model="perPage" dense hide-selected
        suffix="Per Page" hide-details solo flat :items="pageOptions"
        background-color="transparent" height="23" color="grey"
        style="max-width: 110px;" :menu-props="{width: '100px'}"/>

      <v-spacer/>

      <div class="m-0 p-0 mb-2 mr-3">
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

      <v-slide-x-transition>
        <p v-if="totalRows" class="text-secondary m-0 mt-1 ml-3 mr-3 d-none d-sm-block">
          {{ currentPage + 1 }} - {{ canGoNext ? currentPage + perPage : totalRows }} of {{ totalRows }}</p>
      </v-slide-x-transition>

      <v-slide-x-transition>
        <div class="m-0 p-0" v-if="canGoNext || canGoPrev">
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
      </v-slide-x-transition>

      <v-menu bottom transition="slide-y-transition"
        right :nudge-right="-15" :nudge-top="-10"
        offset-y origin="right" content-class="">
        <template v-slot:activator="{ on, attrs }">
          <v-btn v-bind="attrs" v-on="on" min-width="5" width="25" icon small :ripple="false" plain>
            <v-icon size="23" color="secondary">mdi-dots-vertical</v-icon>
          </v-btn>
        </template>

        <v-list dense width="130">
          <export-data :model="model"
            :sheet_name="model_spaced"
            :fields="fieldsToExport"
            :file_name="model_spaced"
            @on-export="dataExported($event)" >

            <template v-slot:activator="{ on, attrs }">
              <v-list-item class="mh-25" v-bind="attrs" v-on="on">
                <v-icon dense small left color="green darken-2">mdi-export</v-icon>

                <v-list-item-title>Export data</v-list-item-title>
              </v-list-item>
            </template>
          </export-data>
        </v-list>
      </v-menu>

      <v-btn color="green darken-4" small dark right depressed height="25"
        style="font-size:13px; text-transform:none;" @click.stop="openDialog" class="ml-2">
        <v-icon size="16" left dense>mdi-square-edit-outline</v-icon>New {{ model_spaced }}
      </v-btn>
    </v-card>

    <!-- ROW SELECTION BEGINS -->
    <v-fade-transition leave-absolute>
      <v-row v-if="selectedItems.length" no-gutters class="position-absolute white m-0 p-0"
        style="z-index:4; top:34px; width: 100%;" justify="space-around">

        <v-col cols="12" class="text-center red lighten-5" style="font-size:small; height:18px;">
          <p class="font-italic">{{selectedItems.length}} of {{totalRows}} records selected</p>
        </v-col>

        <v-col cols="auto" style="margin-left:10px;">
          <v-checkbox v-model="selectAll" color="secondary" dense class="mt-0"
            hide-details :ripple="false"/>
        </v-col>

        <v-col cols="8">
          <v-sheet tile min-width="300" max-width="700">
            <v-chip-group class=" pl-3 pr-5">

              <v-btn :ripple="false" small rounded @click="deleteConfirmation" class="mr-2"
                style="text-transform:none; font-size: small;">{{'Bulk Delete'}}
                <v-icon dense right color="red">mdi-delete-outline</v-icon>
              </v-btn>

              <v-btn :ripple="false" small rounded @click="deleteConfirmation" class="mr-2"
                style="text-transform:none; font-size: small;">{{'Bulk Update'}}
                <v-icon dense right color="success">mdi-pencil-outline</v-icon>
              </v-btn>
            </v-chip-group>
          </v-sheet>
        </v-col>
        <v-spacer/>
        <v-divider class="cols col-12 m-0"/>
      </v-row>
    </v-fade-transition>
    <!-- ROW SELECTION ENDS -->

    <!-- TABLE BEGINS overflow-auto custom-scroll -->
    <b-table sticky-header="calc(100vh - 92px)" primary-key="id" hover small
      :items.sync="dataItemsProvider" :per-page.sync="perPage"
      ref="dataTableRef" :id="model" outlined show-empty
      :busy.sync="isBusy" no-local-sorting :sort-by.sync="sortBy" :fields="header"
      no-border-collapse :responsive="true" :current-page.sync="currentPage"
      :sort-desc.sync="sortDesc" :filter.sync="filter" :filter-function="filterFunction">

      <template #table-busy>
        <b-skeleton-table :rows="12" :columns="header.length" :table-props="{ bordered: true }"
          hide-header :animation="null"/>
      </template>

      <template #empty="scope">
        <div class="w-100 pt-10 pb-10 text-center">
          <v-card flat class="m-auto pt-10 pb-10" width="200">
            <v-icon size="100"> {{trashed?'mdi-delete-empty-outline':'mdi-file-search'}} </v-icon>
            <p class="text-caption text--secondary text--lighten-1 mt-5">{{ scope.emptyText }}</p>
            <v-btn color="green darken-4" small dark height="27"
              style="font-size:13px;" @click.stop="openDialog" class="ml-2 text-capitalize">
              <v-icon size="16" left dense>mdi-square-edit-outline</v-icon>
              Create New {{ model_spaced }}
            </v-btn>
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

      <template #head(id)>
        <v-icon dense style="margin-left: 10px">mdi-cog</v-icon>
      </template>

      <template #cell(id)="data">
        <v-checkbox v-model="selectedItems" dense hide-details :value="data.item.id"
          :ripple="false" color="secondary"/>
      </template>      

      <template #cell(first_name)="data">
        <div class="col-12 m-0 p-0">
          <div class="col-12 m-0 p-0 d-flex">            
            <router-link :to="{ name:'user.detail', params:{user_id: data.item.id} }"
              class="primary--text text-decoration-none d-flex" :title="data.item.name">
              <v-btn v-if="!data.item.active" class="red p-0 mr-2 mt-2" rounded depressed
                height="7" max-width="7" min-width="7"/>

              <p class="m-0 text-truncate" style="width:100%"> {{ data.item.name}} </p>
            </router-link>
          </div>
          <div class="col-12 m-0 p-0 d-flex">
            <a class="text-decoration-none" :href="`mailto:${data.item.email}`">
              <v-icon color="green" size="13" left>mdi-at</v-icon>
              {{ data.item.email }}
            </a>
          </div>
        </div>
      </template>

      <template #cell(mobile_number)="data">
        <a class="text-decoration-none" :href="`tel:${data.item.mobile_number}`">
          {{ data.item.mobile_number }}
        </a>
      </template>

      <template #cell(created_at)="data">
        <div class="col-12 m-0 p-0">
          <p class="m-0 text-truncate" style="width:100%"> {{ data.item.creator_name }} </p>
          <p class="m-0 secondary--text user-select-none" style="font-size: x-small">
            {{ (new Date(data.item.created_at)).toLocaleString()}}</p>
        </div>
      </template>

      <template #cell(updated_at)="data">
        <div class="col-12 m-0 p-0">
          <p class="m-0 text-truncate" style="width:100%"> {{ data.item.updator_name}} </p>
          <p class="m-0 secodary--text user-select-none" style="font-size: x-small">
            {{ (new Date(data.item.updated_at)).toLocaleString()}}</p>
        </div>
      </template>

      <template #cell(actions)="row">
        <v-menu bottom right nudge-right="14" origin="left" transition="slide-x-transition">
          <template v-slot:activator="{ on, attrs }">
            <v-btn v-if="!['bank', 'cash', 'tax', 'receivable', 'payable'].includes(row.item.type)"
              dark icon v-bind="attrs" v-on="on" tile max-height="30">
              <v-icon small :color="'secondary'">mdi-dots-vertical</v-icon>
            </v-btn>
          </template>

          <v-list dense class="m-0 p-0">
            <v-list-item class="m-0 p-0 mr-2 ml-2 overflow-hidden" dense style="min-height:30px">

              <v-tooltip v-if="!trashed" bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon small @click="editData(JSON.stringify(row.item))" dark tile max-height="30"
                    v-bind="attrs" v-on="on" color="secondary" class="m-0 p-0">
                    <v-icon dense small>mdi-pencil-outline</v-icon>
                  </v-btn>
                </template>
                <span class="text-caption">edit user</span>
              </v-tooltip>

              <v-tooltip v-if="!trashed" bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon small @click="resendVerificationLink(row.item.id)" dark tile
                    v-bind="attrs" v-on="on" color="secondary" class="m-0 p-0" max-height="30">
                    <v-icon dense small>mdi-link</v-icon>
                  </v-btn>
                </template>
                <span class="text-caption">send verification link</span>
              </v-tooltip>

              <v-tooltip v-if="!trashed" bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon small @click="forceLogout(row.item.id)" dark tile
                    v-bind="attrs" v-on="on" color="secondary" class="m-0 p-0" max-height="30">
                    <v-icon dense small>mdi-logout</v-icon>
                  </v-btn>
                </template>
                <span class="text-caption">logout</span>
              </v-tooltip>

              <v-tooltip v-if="trashed" bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon small @click="restoreData(row.item.id)" dark tile max-height="30"
                    v-bind="attrs" v-on="on" color="secondary" class="m-0 p-0">
                    <v-icon dense small>mdi-backup-restore</v-icon>
                  </v-btn>
                </template>
                <span class="text-caption">restore user</span>
              </v-tooltip>

              <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon small @click="deleteData(row.item.id, 'soft')"
                    dark v-bind="attrs" v-on="on" color="secondary" tile max-height="30" class="m-0 p-0">
                    <v-icon dense small>mdi-delete-outline</v-icon>
                  </v-btn>
                </template>
                <span class="text-caption p-0" color="red">delete record</span>
              </v-tooltip>
            </v-list-item>
          </v-list>
        </v-menu>
      </template>
    </b-table>

    <v-navigation-drawer v-model="formDialog" app right width="400"
      :permanent="formDialog" temporary>
      <v-card-title class="grey lighten-5 position-fixed w-100 pt-2 pb-0"
        style="height:40px; z-index:1;">
        <h6 class="text-black text-uppercase user-select-none pt-1" v-text="model_spaced"></h6>
        <v-spacer/>
        <v-btn small depressed plain icon :ripple="false" class="m-0 p-0"
          @click.stop="formDialog = false">
          <v-icon size="20" color="black">mdi-close</v-icon>
        </v-btn>
      </v-card-title>

      <user-form :user="user" :display.sync="formDialog"
        @on-submit="formSubmitted($event)"/>
    </v-navigation-drawer>
    
    <!-- Multiple Deletion Dialog -->
    <v-dialog v-model="deletionDialog" transition="dialog-top-transition" max-width="500" persistent>
      <v-card flat tile class="pt-3">
        <h5 class="text-secondary pl-4 pt-1 font-weight-bold">Confirm</h5>
        <p class="m-0 text-secondary pl-4 pt-2" style="font-size:small">{{selectedItems.length}}
           Record(s) will be deleted, along with all related data.</p>
        <p class="m-0 pl-4" style="font-size:small">Delete {{selectedItems.length}} {{model_spaced}}(s)?</p>
        <v-form @submit.prevent="deleteAccountGroup(selectedItems)">
          <v-card-text class="pb-0 pt-1">
            <p class="m-0" style="font-size:small">Type "010" to confirm</p>
            <v-text-field v-model="confirm_code" dense color="grey" outlined height="27"
              style="font-size: 0.9em;"/>
          </v-card-text>
          <v-divider class="m-0"/>

          <v-card-actions class="p-0">
            <v-row no-gutters>
              <v-col class="text-center">
                <v-btn class="black--text pl-5 pr-5 w-100" depressed text ile
                  @click="deletionDialog = false">Cancel</v-btn>
              </v-col>
              <v-divider vertical class="m-0"/>
              <v-col class="text-center">
                <v-btn class="black--text pl-5 pr-5 w-100" :disabled="confirm_code != '010'"
                  :loading="deleteLoading" depressed text type="submit" tile>confirm
                </v-btn>
              </v-col>
            </v-row>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
    <!-- Multiple Deletion Dialog Ends -->

    <v-snackbar v-model="alert" :timeout="3000" left color="secondary darken-1"> {{ message }}
      <template v-slot:action="{ attrs }">
        <v-btn :color="color" icon v-bind="attrs" @click="alert = false" >
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>

  import  { User }  from "./User.js";

  export default {

    data() {
      return {
        model: 'User',
        model_spaced: 'User',
        confirm_code: null,
        deletionDialog: false,
        selectAll: false,
        deleteLoading: false,
        selectedItems: [],
        branchDialog: false,
        filterDialog: false,
        alert: false,
        message: null,
        deleteLoading: false,
        restoreLoading: false,
        refreshLoading: false,
        trashed: 0,
        formDialog: false,
        user: {},
        color: this.$store.getters.theme.color,
        showAdvancedFilter: true,
        filterOn: ['name'],
        sortBy: 'created_at',
        sortDesc: true,
        totalRows: 0,
        currentPage: 0,
        perPage: 10,
        pageOptions: [10, 15, 25, 50 ],
        sortDirection: 'asc',
        isBusy: false,
        filter: '',
        canGoNext: false,
        canGoPrev: false,
        items: [],
        header: [
          { label: '', key: 'id', thClass: 'theader theader-select pl-0 ml-0',
            tdClass:'tdata pl-0 ml-0 border-bottom-left-radius-20' },
          { label: 'NAME', key: 'first_name', stickyColumn: true, thStyle: {minWidth: '200px !important'}, sortable:true, thClass: 'theader sticky-header', tdClass: 'tdata p-0 pt-1 pl-4' },
          { label: 'MOBILE NUMBER', key: 'mobile_number', thClass: 'theader', thStyle: {minWidth: '120px !important'}, tdClass: 'tdata p-0 pl-4 pt-3' },
          { label: 'DESIGNATION', key: 'designation', thClass: 'theader', thStyle: {minWidth: '140px !important'}, tdClass: 'tdata p-0 pl-4 pt-3' },
          { label: 'CREATED BY', key: 'created_at', sortable:true, thClass: 'theader', thStyle: { minWidth: '125px !important' }, tdClass: 'tdata p-0 pt-1 pl-4' },
          { label: 'UPDATED BY', key: 'updated_at', sortable:true, thClass: 'theader', thStyle: { minWidth: '125px !important' }, tdClass: 'tdata p-0 pt-1 pl-4' },
          { label: 'ACTION', key: 'actions', stickyColumn: true, thClass: 'theader sticky-header',
            tdClass: ' border-bottom-right-radius-20', thStyle: { minWidth:'65px !important', right:'-8px' } },
        ],

        fieldsToExport: User().getExportFields(),
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
      },

      selectAll(all) {
        this.clearSelected()
        if (all) this.selectAllRows()
      }
    },

    methods: {
      selectAllRows() {
        this.items.map( item => this.selectedItems.push( item.id ))
      },

      clearSelected() {
        this.selectedItems = []
      },

      dataExported(msg)
      {
        this.showMessage(msg)
      },

      dataImported(msg)
      {
        this.showMessage(msg)
        this.refreshTable()
      },

      templateDownloaded(msg)
      {
        this.showMessage(msg)
      },

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

      showMessage(message) {
        this.message = message
        this.alert = true
      },

      formSubmitted(message)
      {
        this.showMessage(message)
        this.refreshTable()
      },

      deleteConfirmation() {
        this.deletionDialog = true
      },

      openDialog()
      {
        this.clearSelected()
        this.user = { }
        this.formDialog = true
      },

      editData(data)
      {
        this.clearSelected()
        this.user = JSON.parse(data)
        this.formDialog = true
      },

      async resendVerificationLink(user_id)
      {
        await this.$store
                  .dispatch(`sendCreatePasswordLink`, { user_id })
                  .then(data => this.showMessage(data.message))
                  .catch(error => this.showMessage(error.response.data.message))
      },

      async forceLogout(user_id)
      {
        await this.$store
                  .dispatch(`forceLogout`, { id: user_id })
                  .then(data => this.showMessage(data.message))
                  .catch(error => this.showMessage(error.response.data.message))
      },

      deleteData(id, type = 'soft')
      {
        this.deleteLoading = true
        this.$store
            .dispatch(`delete`, { id, type })
            .then(data => {
              this.showMessage(data.message)
              this.refreshTable()
            })
            .catch(error => this.showMessage(error.response.data.message))
            .finally(res => {
              this.selectedItems = []
              this.selectAll = this.deleteLoading = this.deletionDialog = false
            })
      },

      restoreData(id)
      {
        this.$store
            .dispatch( `restore`, { id })
            .then(data => this.refreshTable() )
            .catch( error => console.log(error) )
      },

      clearTrash()
      {
        this.$store
            .dispatch( `clearTrash`)
            .then(data => this.refreshTable() )
            .catch( error => console.log(error) )
      },

      async dataItemsProvider (ctx)
      {
        ctx.currentPage = this.currentPage

        let selectedColumns = User().getSelectedColumns(),
            filter = { type: 'personal'}

        return await this.$store
                          .dispatch(`dataTable`,
                                  { trashed: this.trashed, payload: ctx, selectedColumns, filter})
                          .then(data => {
                            this.items = data.records
                            this.totalRows = data.recordsFiltered
                            return this.items
                          })
                          .catch( error => [] )
      },

      paginateNext() {
        if ( this.canGoNext ) this.currentPage += this.perPage
      },

      paginatePrev() {
        if ( this.canGoPrev) this.currentPage -= this.perPage
      },

      filterFunction ()
      {
        return true
      },

      refreshTable() {
        this.$refs.dataTableRef.refresh()
      },
    },

    mounted() {
      if (this.$route.query.form) {
        this.openDialog()
      }

      $('.v-data-table__wrapper').addClass('custom-scroll')
      $('.table-responsive').addClass('custom-scroll')


      $('.table-responsive').scroll(function(e){
        if (!$(this).scrollLeft()) {
          $('.td--right-0').removeClass('elevation-2')
        }
        else $('.td--right-0').addClass('elevation-2')
      })
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
    font-size: .9em;
  }

  >>>.v-text-field .v-input__control .v-input__slot label.v-label--active {
    margin: 0 0 0 5px !important;
    font-size: .95em;
  }

  >>>.v-text-field__details {
    margin: 2px !important;
  }

  >>>.tdata {
    font-family: calibri;
    font-size: .85rem ;
    color: #595959 !important;
    max-width: 28px;
  }

  >>>.theader {
    background-color: #fafafa !important;
    min-width: 110px !important;
    font-family: calibri;
    font-size: .7rem;
    color: #1d1c1c !important;
  }

  >>>.sticky-header {
    z-index: 3 !important;
  }

  >>>.theader-select {
    width: 11px;
    max-width: 20px !important;
    min-width: 0px !important;
  }

  >>>.theader div {
    margin-left: 10px !important;
  }

  /* Fix an issue in bootstrap v2.22.0: */
  >>>.b-table-sticky-header > .table.b-table > thead > tr > th {
    position: sticky !important;
  }

  >>>.td--right-0 {
    right: 0 !important;
  }

  >>>table {
    border-radius: 0 0 20px 20px;
  }

  >>> .border-bottom-right-radius-20 {
    border-radius: 0 0 20px 0px;
  }

  >>> .border-bottom-left-radius-20 {
    border-radius: 0 0 0px 20px;
  }
</style>
