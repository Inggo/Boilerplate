const appliesFiltering = {
  data () {
    return {
      filter: ""
    };
  },
  props: {
    items: Array,
    filteredItems: Array,
  },
  watch: {
    filter (newFilter, oldFilter) {
      this.$emit('update-filter', newFilter);
    }
  },
  computed: {
    counterClass () {
      if (this.filteredItems.length === this.items.length) {
        return "is-success";
      }

      if (this.filteredItems.length > 1) {
        return "is-info";
      }

      if (this.filteredItems.length === 1) {
        return "is-warning";
      }

      return "is-danger";
    },
  }
};

export default appliesFiltering;