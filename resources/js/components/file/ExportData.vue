<template>
  <v-dialog
    persistent v-model="exportDialog"
    width="700" transition="dialog-bottom-transition"
    content-class="custom-scroll grey lighten-5">

    <template v-slot:activator="{ on, attrs }">
      <slot
        name="activator"
        :attrs="attrs"
        :on="on">

        <!-- DEFAULT SCOPE -->
        <v-btn
          :ripple="false"
          small
          rounded
          v-bind="attrs"
          v-on="on"
          style="text-transform:none; font-size: small;">{{'Export data'}}

          <v-icon dense right color="green darken-2">mdi-export</v-icon>
        </v-btn>
      </slot>
    </template>
    

    <validation-observer ref="formValidator" v-slot="{ invalid }">    
      <v-form class="p-0 m-0">

        <v-card tile flat min-height="500px">
          <v-card-title
            class="grey lighten-5 p-0 pl-3"
            style="height:40px;">
            <h6 class="text-black text-uppercase pt-1"
              v-text="'data export'"></h6>
            <v-spacer/>
          </v-card-title>

          <v-card-text class="p-0 pl-3 pr-3" style="height: 365px">
            <v-row no-gutters class="h-100">
              <v-col>
                <v-row no-gutters>
                  <v-col cols="8">
                    <v-card tile flat class="pt-3">                  
                      <v-card-title class="grey lighten-5 p-0 pl-3" style="height:27px;">
                        <h5>Select specific fields for export</h5>
                      </v-card-title>
                      
                      <!-- Field Selection Begins -->
                      <v-card-text class="p-0  pr-2 pt-3 pb-1 custom-scroll"
                        style="height:160px; overflow-y:scroll;">
                        <v-row no-gutters>
                          <v-col cols="12" class="m-0 p-0" v-for="item, index in fields" :key="index">
                            <v-checkbox  class="m-0 p-0" v-model="selectedFields" color="green darken-2"
                              hide-details dense :ripple="false" :value="item">
                              <template v-slot:label>
                                <h6 class=" text-secondary">{{item.name}}</h6>
                              </template>
                            </v-checkbox>
                          </v-col>
                        </v-row>
                      </v-card-text>
                      <!-- Field Selection Ends -->
                    </v-card>
                  </v-col>

                  <v-divider vertical class="mr-3 ml-3 elevation-5"/>
                  
                  <v-col>
                    <v-row no-gutters class="mb-4 mt-3">                                        
                      <v-col class="grey lighten-5">
                        <h5 class="green--text text--darken-4">Export options</h5>
                      </v-col>
                    </v-row>

                    <v-row no-gutters>
                      <v-col cols="12" class="custom--select ">
                        <validation-provider v-slot="{ errors }" name="file_format" rules="required">
                          <v-select v-model="fileFormat" color="grey" height="25"  dense
                            item-color="grey" hide-selected :items="fileFormats" outlined
                            :menu-props="{contentClass:'custom-scroll white', maxHeight:'160px'}"
                            :error-messages="errors" error-count="1" label="File Format"/>
                        </validation-provider>
                      </v-col>

                      <v-col cols="12">
                        <validation-provider v-slot="{ errors }" name="offset"
                          rules="required|min_value:0|max:12">
                          <v-text-field v-model="payload.offset" color="grey" height="25" outlined dense
                            item-color="grey" hide-selected min="0" type="number"
                            :error-messages="errors" error-count="1" label="Start From"/>
                        </validation-provider>
                      </v-col>

                      <v-col cols="12">
                        <validation-provider v-slot="{ errors }" name="limit"
                          rules="required|min_value:10|max_value:1000">
                          <v-text-field v-model="payload.limit" color="grey" height="25" outlined
                            item-color="grey" hide-selected max="1000" min="10" type="number"
                            :error-messages="errors" error-count="1" dense label="Record Limit"/>
                        </validation-provider>
                      </v-col>
                    </v-row>
                  </v-col>
                </v-row>
                
                <v-hover v-slot="{ hover }">
                  <v-card class="m-0 p-2 pl-3" rounded outlined>

                    <!-- FILTER HEADER -->
                    <v-row no-gutters class="mb-1" style="min-height: 30px;">
                      <v-btn plain x-small tile @click.prevent="addFilter"
                        style="margin-left: -6px;">
                        <v-icon color="success" dense left>mdi-filter-plus</v-icon>

                        <v-slide-x-transition>
                          <p class="m-0 ml-1 mr-3 p-0 text-capitalize" v-show="hover"
                            style="font-size: medium">Add filter</p>
                        </v-slide-x-transition>
                      </v-btn>
                    </v-row>
                    <!-- FILTER HEADER ENDS-->

                    <div class="m-0 p-0 custom-scroll" style="height:150px; overflow-y:scroll;">
                      <v-row no-gutters v-for="filter, index in filters" :key="index">
                        <v-col cols="auto" class="mr-2">
                          <v-btn icon x-small @click.prevent="removeFilter(index)">
                            <v-icon color="red" dense>mdi-minus-circle</v-icon>
                          </v-btn>
                        </v-col>
                        
                        <v-col class="custom--select mr-2">
                          <validation-provider v-slot="{ errors }" :name="`field${index}`"
                            rules="required">
                            <v-select v-model="filter.field" color="grey" height="25"  dense
                              hide-details item-color="grey" hide-selected outlined
                              :items="selectedFields.filter( ({not_filterable}) => !not_filterable)"
                              item-text="name" item-value="id"
                              :menu-props="{contentClass:'custom-scroll white', maxHeight:'160px'}"
                              :error-messages="errors" error-count="1"/>
                          </validation-provider>
                        </v-col>

                        <v-col class="mr-2">
                          <validation-provider v-slot="{ errors }" :name="`operator${index}`"
                            rules="required">
                            <v-select v-model="filter.operator" color="grey" height="25" solo flat
                              dense item-text="name" item-value="id"
                              hide-details item-color="grey" hide-selected :items="operators"
                              :menu-props="{contentClass:'custom-scroll white', maxHeight:'160px'}"
                              :error-messages="errors" error-count="1"/>
                          </validation-provider>
                        </v-col>

                        <v-col class="pr-2">
                          <validation-provider v-slot="{ errors }" :name="`value${index}`"
                            rules="required">
                            <v-text-field v-model="filter.value" color="grey" height="25" outlined dense
                              hide-details item-color="grey" hide-selected max="100"
                              :error-messages="errors" error-count="1"/>
                          </validation-provider>
                        </v-col>
                      </v-row>
                    </div>

                  </v-card>
                </v-hover>

              </v-col>
            </v-row>
          </v-card-text>
          
          <v-card-actions class="w-100 grey lighten-5 position-absolute" style="bottom:0; left:0;">
            <v-spacer/>
            <v-btn depressed v-text="'Cancel'" max-height="32" outlined plain class="font-weight-bold"
              width="100" @click.prevent="closeExportDialog()" :style="{textTransform: 'none'}"/>

            <v-btn depressed v-text="'Export your data'" max-height="32" width="130"
              :style="{background: color, textTransform: 'none'}" class="text-white"
              :disabled="invalid || !canexport()" @click.prevent="fetchData()"/>
          </v-card-actions>

        </v-card>
      </v-form>
    </validation-observer>
  </v-dialog>
</template>

<script>
  import QueryOperators from '../../services/QueryOperators.js'
  import FileService from '../../services/File.js'

  export default {

    props: {
      file_name: { type: String, required: true },
      sheet_name: { type: String, required: true },
      model: { type: String, required: true },
      fields: { type: Array, required: true}
    },

    data() {
      return {
        color: this.$store.getters.theme.color,
        exportDialog: false,
        data_to_export: [],
        selectedFields: [],
        filters: [],
        payload: { offset: 0, limit: 1000 },
        fileFormat: 'csv',
        fileFormats: [ 'csv', 'xlsx', 'txt'],
        operators: new QueryOperators().get()
      }
    },

    watch: {
      exportDialog(val) {
        if (val) {
          this.selectedFields = this.fields
        }
      },

      async data_to_export( data) {
        if (!data) return

        if (!data.length) {
          this.$emit("on-export", 'No record to export')
          return
        }

        await (new FileService()).export(this.file_name, this.sheet_name, data, this.fileFormat)
        this.$emit("on-export", 'data exported')
        this.closeExportDialog()
      },
    },

    methods: {

      removeFilter(index) {
        this.filters.splice(index, 1)
      },
      
      addFilter()
      {
        this.filters.push( {field: '', operator: '', value: ''} )
      },

      closeExportDialog()
      {
        this.exportDialog = false
        this.resetForm()
      },

      async fetchData()
      {
        let selectedColumns = this.selectedFields.map( ({id}) => id),
            filters = this.filters || {},
            payload = this.payload ||  { offset: 0, limit: 1000}
        
        await this.$store
                  .dispatch( `${this.model}/export`, { payload, selectedColumns, filters } )
                  .then(data => this.data_to_export = data)
                  .catch( error => {
                    this.$emit("on-export", 'Error exporting file')
                    this.$refs.formValidator
                              .setErrors( error.response.status === 422 ? error.response.data.errors : [] )
                  })
      },

      canexport()
      {
        return true
      },

      resetForm() {
        if (this.$refs.formValidator) this.$refs.formValidator.reset()
        this.filters = []
      }
    },
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
  
  >>> .custom--select .v-select__slot label {
    padding-top: 4px !important;
  }

  >>> .custom--select .v-text-field .v-input__control .v-input__slot .v-input__append-inner {
    margin-top: 8px !important;
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

  >>>.b-table tbody tr td {
    max-width: 100px !important;
  }

  >>>.tdata {
    font-family: calibri;
    font-size: .85rem ;
    color: #595959 !important;
  }

  >>>.theader {
    background-color: #fafafa !important;
    min-width: 100px !important;
    font-family: calibri;
    font-size: .7rem;
    color: #1d1c1c !important;
  }
  
  >>>.theader-select {
    width: 11px;
    max-width: 20px !important;
    min-width: 10px !important;
  }


  >>>.theader div {
    margin-left: 10px !important;
  }

  >>> .v-input__control .v-input__slot .v-input--selection-controls__input {
    margin-top: -10px;
  }
</style>

