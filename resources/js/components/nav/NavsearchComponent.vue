<template>
  <v-col cols="4" class="p-0 pt-2" style="max-height:30px">
    <v-autocomplete v-model="model" color="grey darken-5"
      :items="items" :loading="isLoading" :search-input.sync="search"
      hide-details hide-selected item-text="name" item-value="symbol" flat
      solo height="30" placeholder=" Search" background-color="grey lighten-3"
      :menu-props="{contentClass:'custom-scroll white' }">

      <template v-slot:prepend-inner>
        <v-icon dense size="18">mdi-magnify</v-icon>
      </template>

      <template v-slot:selection="{ attr, on, item, selected }">
        <v-chip v-bind="attr" :input-value="selected" color="blue-grey" class="white--text" v-on="on">
          <v-icon left>
            mdi-bitcoin
          </v-icon>
          <span v-text="item.name"></span>
        </v-chip>
      </template>

      <template v-slot:item="{ item }">
        <v-list-item-avatar color="indigo" class="text-h5 font-weight-light white--text">
          {{ item.name.charAt(0) }}
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{'item.name'}}</v-list-item-title>
          <v-list-item-subtitle>{{'item.symbol'}}</v-list-item-subtitle>
        </v-list-item-content>
        <v-list-item-action>
          <v-icon>mdi-bitcoin</v-icon>
        </v-list-item-action>
      </template>
    </v-autocomplete>
  </v-col>
</template>

<script>

  export default {
    data: () => ({
      isLoading: false,
      items: [],
      model: null,
      search: null,
      tab: null,
    }),

    watch: {
      model (val) {
        if (val != null) this.tab = 0
        else this.tab = null
      },
      search (val) {
        // Items have already been loaded
        if (this.items.length > 0) return

        this.isLoading = true

        // Lazily load input items
        fetch('https://api.coingecko.com/api/v3/coins/list')
          .then(res => res.clone().json())
          .then(res => {
            this.items = res
          })
          .catch(err => {
            console.log(err)
          })
          .finally(() => (this.isLoading = false))
      },
    },
  }
</script>
