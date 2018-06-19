<template>
  <p class="control">
    <button
      class="button is-text"
      :class="size"
    >
      <b-icon :icon="icon" :size="size"></b-icon>
      <span>{{ label }}</span>
    </button>
  </p>
</template>

<script>
export default {
  props: {
    icon: {
      type: String,
      default: "pencil"
    },
    size: {
      type: String,
      default: "is-small"
    },
    label: {
      type: String,
      default: "Set as Price"
    }
  }
};
</script>
