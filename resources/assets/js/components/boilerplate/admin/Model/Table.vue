<template>
  <div>
    <filter-utils 
      v-if="filterable && !showSelection"
      :items="items"
      :filtered-items="filteredItems"
      @update-filter="updateFilter"
    ></filter-utils>
    <b-table
      :data="filteredItems"
      striped
      hoverable
      mobile-cards
      :loading="dataLoading"
      :row-class="(row, index) => rowClass(row)"
      :checkable="showSelection"
      :checked-rows.sync="selected"
      :paginated="paginated"
      :per-page="itemsPerPage"
      :narrowed="isNarrowed"
    >
      <template slot-scope="props">
        <b-table-column
          field="id"
          label="ID"
          width="40"
          numeric sortable
          v-html="applyHighlight(props.row.id)"
        ></b-table-column>

        <b-table-column
          field="name"
          label="Name"
          sortable
          v-html="applyHighlight(props.row.name)"
        ></b-table-column>

        <b-table-column
          field="user_count"
          label="Users"
          sortable
          v-html="applyHighlight(props.row.user_count)"
        ></b-table-column>

        <b-table-column
          label="Actions"
          numeric
          v-if="showActions"
        >
          <b-dropdown position="is-bottom-left" class="has-text-left">
            <button class="button is-primary" slot="trigger">
              <span>Actions</span>
                <b-icon icon="menu-down"></b-icon>
            </button>

            <b-dropdown-item>
              <router-link :to="'/models/' + props.row.id + '/edit'">
                <icon-label icon="pencil" label="Edit Model"></icon-label>
              </router-link>
            </b-dropdown-item>
            <b-dropdown-item @click="confirmDelete(props.row)">
              <icon-label icon="delete" label="Delete Model"></icon-label>
            </b-dropdown-item>
          </b-dropdown>
        </b-table-column>

      </template>
      <default-empty slot="empty" v-if="!dataLoading"></default-empty>
      <table-utils
        slot="bottom-left"
        :selected="selected"
        :items="items"
        :filteredItems="filteredItems"
        :showSelection="showSelection"
        :filterable="filterable"
        @select-all="selected = items"
        @clear-all="selected = []"
        @update-filter="updateFilter"
      ></table-utils>
    </b-table>
  </div>
</template>

<script>
import { storeMixins } from '@/store';

import { axios, messages, highlighting, filtering, selectableTable, retrievesModels, deleteModel } from 'Mixins/mixins';

import DefaultEmpty from 'Components/DefaultEmpty';
import IconLabel from 'Components/IconLabel';
import FilterUtils from 'Components/FilterUtils';
import TableUtils from 'Components/TableUtils';

export default {
  data () {
    return {
      models: [],
      modelName: 'Model',
      modelSlug: 'models',
      filterableKeys: [
        'id', 'name', 'user_count'
      ]
    }
  },
  components: { FilterUtils, TableUtils, DefaultEmpty, IconLabel },
  props: {
    selectedModels: {
      type: Array,
      default () {
        return [];
      }
    },
    showActions: {
      type: Boolean,
      default: true
    },
  },
  mixins: [ storeMixins, messages, highlighting, filtering, selectableTable, retrievesModels, deleteModel ],
  computed: {
    items () {
      return this.models;
    }
  },
  mounted () {
    this.retrieveModels();
  },
  methods: {
    retrieveItems  () {
      return this.retrieveModels();
    }
  }
};
</script>
