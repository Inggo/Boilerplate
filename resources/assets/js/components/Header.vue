<template>
  <header class="level">
    <div class="level-left">
      <h1 class="level-item">
        <span class="title">
          <b-icon :icon="icon" size="is-medium" v-if="icon"></b-icon>
          {{ title }}
        </span>
      </h1>
      <slot name="left"></slot>
    </div>
    <div class="level-right">
      <slot name="right"></slot>
      <button class="button level-item is-secondary has-icon" @click="$router.go(-1)" v-if="showBack">
        <b-icon icon="arrow-left" size="is-small"></b-icon>
        <span>Back</span>
      </button>
    </div>
  </header>
</template>

<script>
export default {
  props: {
    icon: {
      type: String,
      default: null
    },
    title: String,
    showBack: {
      type: Boolean,
      default: true
    }
  }
}
</script>