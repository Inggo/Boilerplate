import axios from './axios';

const submit = {
  data () {
    return {
      modelSlug: 'model'
    }
  },
  computed: {
    formMethod () {
      return this.editing
        ? 'patch'
        : 'post'
      ;
    },

    formUrl () {
      let url = `/api/${this.modelSlug}/`;

      return this.editing
        ? url + this.$route.params.id
        : url
      ;
    },

    action () {
      return this.editing
        ? 'updated'
        : 'created'
      ;
    }
  },
  methods: {
    submit () {
      this.load();

      axios[this.formMethod](this.formUrl, this.formData)
        .then((response) => {
          this.handleResponse(response);
        })
        .catch((error) => {
          this.setErrors(error);
        })
        .finally(() => {
          this.unload();
        });
    }
  }
};

export default submit;