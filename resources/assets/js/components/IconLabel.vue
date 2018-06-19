<template>
  <span>
    <b-icon :icon="icon" v-if="icon" size="is-small"></b-icon>
    {{ label }}
  </span>
</template>

<script>
export default {
  props: {
    icon: String,
    label: String
  }
}
</script>