<template>
  <p class="control">
    <button class="button" disabled>
      {{ currency_unit }}
    </button>
  </p>
</template>

<script>
export default {
  data () {
    return {
      currency_unit: "₱"
    }
  }
}
</script>
