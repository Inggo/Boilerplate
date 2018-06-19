import axios from 'Mixins/axios';

const retrieveModel = {
  data () {
    return {
      dataLoading: false,
      retrievedModel: null
    }
  },
  methods: {
    retrieveModel (id) {
      this.dataLoading = true;

      return axios.get('/api/models/' + id)
        .then((response) => {
          this.retrievedModel = response.data.data;
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

export default retrieveModel;