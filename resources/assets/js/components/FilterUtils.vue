<template>
  <div class="level">
    <div class="level-left">
      <b-input placeholder="Filter"
        type="search"
        icon="magnify"
        class="level-item"
        value="filter"
        v-model="filter"
      ></b-input>
      <b-tag class="level-item"
        rounded
        :class="counterClass"
      >
        Showing {{ this.filteredItems.length }}/{{ this.items.length }}
      </b-tag>
    </div>
    <div class="level-right">
      <slot name="right"></slot>
    </div>
  </div>
</template>

<script>
import appliesFiltering from 'Mixins/appliesFiltering';

export default {
  mixins: [ appliesFiltering ]  
}  
</script>