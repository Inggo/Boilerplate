const messages = {
  data () {
    return {
      errors: {},
      shouldRefresh: false
    }
  },
  computed: {
    hasError () {
      return !_.isEmpty(this.errors) && this.error && this.error.length > 0;
    },
  },
  methods: {
    clearAllErrors () {
      this.errors = {};
    },

    clearErrors (prop) {
      this.$delete(this.errors, prop);
    },

    getErrors (prop) {
      return {
        type: _.has(this.errors, prop) ? 'is-danger' : null,
        message: _.has(this.errors, prop) ? this.errors[prop] : null
      }
    },

    snackbar (options) {
      this.$snackbar.open(options);
    },

    toast (message, type = 'is-info', opts = { duration: 5000, queue: false }) {
      var options = _.assign({
        message: message,
        type: type,
        onAction: () => {
          if (this.shouldRefresh) {
            window.location.reload();
          }
        },
        indefinite: this.shouldRefresh
      }, opts);
      
      this.$snackbar.open(options);
    },

    success (message, opts = { duration: 5000, queue: false }) {
      this.toast(message, 'is-success', opts);
    },

    warning (message, opts = { duration: 5000, queue: false }) {
      this.toast(message, 'is-warning', opts);
    },

    danger (message, opts = { duration: 5000, queue: false }) {
      this.toast(message, 'is-danger', opts);
    },

    setErrors (error) {
      if (error.response) {
        if (error.response.status == 401) {
          this.shouldRefresh = true;
        }

        this.danger(this.getMessageFromResponse(error.response));
        this.errors = error.response.data.errors;
        return;
      }

      this.danger(error.message);
      console.log('Error', error.message);
    },

    getMessageFromResponse (response) {
      if (response.data && !_.isEmpty(response.data.message)) {
        return response.data.message;
      }

      return this.getErrorMessageFromStatus(response.status);
    },

    getErrorMessageFromStatus (status) {
      console.log(status);
      switch (status) {
        case 401:
          this.shouldRefresh = true;
          return "Unauthenticated. Please sign-in.";
        case 404:
          return "The requested resource was not found.";
        default:
          this.shouldRefresh = true;
          return "Unknown error. Please try refreshing your browser and try again.";
      }
    },

    catchUnauthorized (error) {
      if (error.response.status) {
        this.danger(error.response.data.message, {
          indefinite: true,
          onAction: () => {
            this.$router.go(-1);
            this.unload();
          }
        });
      } else {
        this.setErrors(error);
        this.unload();
      }
    }
  }
};

export default messages;