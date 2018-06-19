<template>
  <div class="activity-table">
    <filter-utils 
      v-if="filterable && !showSelection"
      :items="items"
      :filtered-items="filteredItems"
      @update-filter="updateFilter"
    >
      <template slot="right">
        <date-filter
          :start="startDateFilter"
          :end="endDateFilter"
          @set-start-date="setStartDate"
          @set-end-date="setEndDate"
        ></date-filter>
      </template>
    </filter-utils>
    <b-table
      :data="filteredItems"
      striped
      hoverable
      mobile-cards
      paginated
      per-page="100"
      :loading="dataLoading"
      default-sort="id"
      default-sort-direction="desc"
      detailed
      detail-key="id"
      :row-class="(row, index) => !row.expanded_details && 'no-details'"
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
          field="created_at"
          label="Date/Time"
          sortable
          centered
          v-html="applyHighlight(props.row.created_at)"
        ></b-table-column>

        <b-table-column
          field="user"
          label="User"
          sortable
          centered
          v-html="applyHighlight(props.row.user)"
        ></b-table-column>

        <b-table-column
          field="activity"
          label="Activity"
          sortable
          centered
          v-html="applyHighlight(props.row.activity)"
        ></b-table-column>
      </template>
      <template slot="detail" slot-scope="props">
        <div class="content" v-html="applyHighlight(props.row.expanded_details)"></div>
      </template>
      <default-empty slot="empty" v-if="!dataLoading"></default-empty>
    </b-table>
  </div>
</template>

<script>
import { storeMixins } from '@/store';
import { axios, messages, highlighting, filtering, dateFilters, parsesActivity, retrievesActivity } from 'Mixins/mixins';

import FilterUtils from 'Components/FilterUtils';
import DateFilter from 'Components/DateFilter';
import DefaultEmpty from 'Components/DefaultEmpty';

export default {
  data () {
    return {
      filterableKeys: [
        'id', 'date', 'user', 'activity', 'expanded_details'
      ]
    }
  },
  props: {
    ofUser: {
      type: Object,
      default: null
    }
  },
  components: { DefaultEmpty, FilterUtils, DateFilter },
  mixins: [ storeMixins, messages, highlighting, filtering, dateFilters, parsesActivity, retrievesActivity ],
  computed: {
    items () {
      return this.parsedActivity;
    }
  },
  mounted () {
    if (!this.$route.params.id && !this.ofUser) {
      this.retrieveActivities();
    }
  },
  watch: {
    ofUser (user) {
      this.activities = user.actions;
    }
  }
};
</script>
