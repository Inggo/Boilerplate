const thresholdFilters = {
  data () {
    return {
      thresholdFilters: []
    };
  },
  computed: {
    thresholdFilteredItems () {
      return this.items.filter(item => {
        if (_.indexOf(this.thresholdFilters, 'above-threshold') > -1 && item.stock > item.threshold) {
          return true;
        }

        if (_.indexOf(this.thresholdFilters, 'on-threshold') > -1 && item.stock == item.threshold) {
          return true;
        }

        if (_.indexOf(this.thresholdFilters, 'below-threshold') > -1 && item.stock < item.threshold) {
          return true;
        }

        if (_.indexOf(this.thresholdFilters, 'no-stock') > -1 && item.stock == 0) {
          return true;
        }

        if (_.indexOf(this.thresholdFilters, 'negative-stock') > -1 && item.stock < 0) {
          return true;
        }

        return false;
      });
    }
  },
  methods: {
    setThresholdFilters (to) {
      this.thresholdFilters = to;
    }
  }
};

export default thresholdFilters;