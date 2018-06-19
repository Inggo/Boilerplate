const highlighting = {
  props: {
    highlight: {
      type: String,
      default: ""
    }
  },
  methods: {
    applyHighlight (str) {
      let highlight = this.highlight || this.filter;
      if (!str || !highlight) {
        return str;
      }

      var pattern = new RegExp(_.escapeRegExp(highlight), 'ig');
      return str.toString().replace(pattern, "<span class=\"has-text-highlighted has-text-weight-semibold\">$&</span>");
    }
  }
};

export default highlighting;