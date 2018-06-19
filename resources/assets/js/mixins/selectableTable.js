const selectableTable = {
  data () {
    return {
      selected: [],
      initSelectSet: false
    };
  },
  props: {
    initialSelected: {
      type: Array,
      default () {
        return [];
      }
    },
    showSelection: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    setInitial (selection) {
      if (this.initSelectSet) {
        return;
      }

      if (this.items.length > 0) {
        this.selected = this.items.filter((item) => {
          if (_.indexOf(selection, item.id) > -1) {
            return true;
          }
        });
        this.initSelectSet = true;
      }
    }
  },
  computed: {
    isNarrowed () {
      return this.showSelection;
    }
  },
  watch: {
    initialSelected (newSelected, oldSelected) {
      this.$nextTick(() => {
        this.setInitial(newSelected);
      });
    },
    items (newItems, oldItems) {
      this.$nextTick(() => {
        this.setInitial(this.initialSelected);
      });
    },
    initialSelected (newSelected, oldSelected) {
      this.$nextTick(() => {
        this.selected = this.items.filter((item) => {
          if (_.indexOf(newSelected, item.id) > -1) {
            return true;
          }
        });
      });
    },
    selected (newSelected, oldSelected) {
      this.$emit('update-selection', newSelected);
    }
  }
};

export default selectableTable;