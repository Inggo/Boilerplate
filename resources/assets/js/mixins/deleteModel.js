import axios from './axios';

const deleteModel = {
  data () {
    return {
      deleting: null,
      modelName: "Model",
      modelSlug: "model",
    }
  },
  computed: {
    deleteTitle () {
      return `Deleting ${this.modelName}`;
    },
  },
  methods: {
    deleteMessage (model) {
      return `Are you sure you want to delete the ${this.modelName} <b>${model.name}</b> (#${model.id})?`;
    },

    rowClass (row) {
      if (this.deleting && row.id == this.deleting.id) {
        return 'is-danger';
      }

      if (this.newModel && row.id === this.newModel.id) {
        return 'is-success';
      }

      return null;
    },

    confirmDelete (model) {
      this.$dialog.confirm({
        title: this.deleteTitle,
        message: this.deleteMessage(model),
        confirmText: `Delete ${this.modelName}`,
        type: 'is-danger',
        hasIcon: true,
        onConfirm: () => this.delete(model)
      });
    },

    delete (model) {
      this.load();

      this.deleting = model;

      axios.delete(this.deleteUrl(model))
        .then((response) => {
          this.success(`Successfully deleted ${this.modelName} #${model.id}`);
          this.retrieveItems();
        })
        .catch((error) => {
          this.catchUnauthorized(error);
        })
        .finally(() => {
          this.deleting = null;
          this.unload();
        })
    },

    deleteUrl (model) {
      return `/api/${this.modelSlug}/${model.id}`;
    }
  }
};

export default deleteModel;