const filtering = {
  data () {
    return {
      filter: null,
      filterableKeys: [],
      advancedFiltersActive: false,
      filterId: 'id'
    };
  },
  props: {
    showSelection: {
      type: Boolean,
      default: false
    },
    filterable: {
      type: Boolean,
      default: false
    },
    paginated: {
      type: Boolean,
      default: true
    },
    itemsPerPage: {
      type: Number,
      default: 20
    }
  },
  computed: {
    filteredItems () {
      let items = this.items;

      if (this.dateFilteredItems) {
        items = _.intersectionBy(items, this.dateFilteredItems, this.filterId);
      }

      if (this.tagFilteredItems) {
        items = _.intersectionBy(items, this.tagFilteredItems, this.filterId);
      }

      if (this.thresholdFilteredItems) {
        items = _.intersectionBy(items, this.thresholdFilteredItems, this.filterId);
      }

      return this.filterCollection(items);
    },
  },
  methods: {
    filterCollection (collection) {
      if (!this.filter) {
        return collection;
      }

      var filter = this.filter;
      var keys = this.filterableKeys;
      var pattern = new RegExp(_.escapeRegExp(filter), 'ig');

      return collection.filter((item) => {
        for (var i = 0; i < keys.length; i++) {
          var key = keys[i];
          if (item[key] && item[key].toString().match(pattern)) {
            return true;
          }
        }
        return false;
      });
    },
    updateFilter (filter) {
      this.filter = filter;
    }
  }
};

export default filtering;
