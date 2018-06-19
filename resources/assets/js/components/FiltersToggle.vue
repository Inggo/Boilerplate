<template>
  <b-field>
    <button
      class="button"
      :class="{ 'is-info' : !active, 'is-warning': active }"
      @click="$emit('toggle')"
    >
      <b-icon icon="filter" v-if="!active"></b-icon>
      <span>Filter</span>
      <b-icon icon="close" v-if="active"></b-icon>
    </button>
  </b-field>
</template>

<script>
export default {
  props: {
    active: Boolean
  }
};
</script>