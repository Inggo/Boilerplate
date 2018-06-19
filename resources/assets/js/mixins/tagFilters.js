const tagFilters = {
  data () {
    return {
      activeTags: [],
      tagFiltering: null
    };
  },
  computed: {
    allTags () {
      return _.uniq(_.map(_.flatten(_.map(this.items, 'tags')), 'name'));
    },
    tagFilteredItems () {
      if (!this.tagFiltering) {
        return this.items;
      }

      let items = this.items;
      let tags = this.activeTags;

      const op_table = {
        'has' (a) { return a > 0; },
        'has-all' (a) { return a == tags.length; },
        'without' (a) { return a == 0; }
      };

      return items.filter(item => {
        return op_table[this.tagFiltering](item.tags.filter(tag => _.indexOf(tags, tag.name) > -1).length);
      });
    }
  },
  methods: {
    setTagFiltering (to) {
      this.tagFiltering = to;
    },
    setActiveTags (to) {
      this.activeTags = to;
    }
  }
};

export default tagFilters;