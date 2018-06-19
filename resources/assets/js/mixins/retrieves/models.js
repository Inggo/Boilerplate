import axios from 'Mixins/axios';

const retrievesModels = {
  data () {
    return {
      dataLoading: false,
      models: []
    }
  },
  methods: {
    retrieveModels () {
      this.dataLoading = true;

      axios.get('/api/models')
        .then((response) => {
          let models = response.data.data;
          this.models = models.map((model) => {
            model.deleting = false;
            return model;
          });
        })
        .catch((error) => {
          this.setErrors(error);
        })
        .finally(() => {
          this.dataLoading = false;
        });
    }
  }
};

export default retrievesModels;