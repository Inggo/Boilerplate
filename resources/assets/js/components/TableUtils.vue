<template slot="bottom-left">
  <div class="level-item" v-if="showSelection">
    <div class="table-utils">
      <b-field grouped>
        <b-input placeholder="Filter"
          type="search"
          icon="magnify"
          v-model="filter"
          size="is-small"
        ></b-input>
        <div>
          <b-tag rounded :class="{
              'is-success': filteredItems.length === items.length,
              'is-info' : filteredItems.length < items.length && filteredItems.length >= items.length/2,
              'is-warning' : filteredItems.length < items.length/2 && filteredItems.length > 0,
              'is-danger' : filteredItems.length === 0
            }">
              <span>
                <span>{{ filteredItems.length }}/{{ items.length }}</span>
                <span>Filtered</span>
              </span>
          </b-tag>
        </div>
      </b-field>
      <b-field grouped>
        <div class="control">
          <button class="button is-success has-icon is-small" @click="$emit('select-all')">
            <b-icon icon="plus" size="is-small"></b-icon>
            <span>Select All</span>
          </button>
          <button class="button is-danger has-icon is-small" @click="$emit('clear-all')">
            <b-icon icon="minus" size="is-small"></b-icon>
            <span>Clear All</span>
          </button>
        </div>
        <b-tag rounded :class="{
          'is-success': selected.length === items.length,
          'is-info' : selected.length < items.length && selected.length >= items.length/2,
          'is-warning' : selected.length < items.length/2 && selected.length > 0,
          'is-danger' : selected.length === 0
        }">
          <span>
            <span>{{ selected.length }}/{{ items.length }}</span>
            <span>Selected</span>
          </span>
        </b-tag>
      </b-field>
    </div>
  </div>
</template>

<script>
import appliesFiltering from 'Mixins/appliesFiltering';

export default {
  mixins: [ appliesFiltering ],
  props: {
    selected: Array,
    showSelection: Boolean,
    filterable: Boolean,
  }
};
</script>
