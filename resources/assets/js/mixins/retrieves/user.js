import axios from 'Mixins/axios';

const retrieveUser = {
  data () {
    return {
      dataLoading: false,
      retrievedUser: null
    }
  },
  methods: {
    retrieveUser (id) {
      this.dataLoading = true;

      return axios.get('/api/users/' + id)
        .then((response) => {
          this.retrievedUser = response.data.data;
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

export default retrieveUser;