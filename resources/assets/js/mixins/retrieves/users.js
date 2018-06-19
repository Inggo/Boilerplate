import axios from 'Mixins/axios';

const retrievesUsers = {
  data () {
    return {
      dataLoading: false,
      users: []
    }
  },
  methods: {
    retrieveUsers () {
      this.dataLoading = true;

      axios.get('/api/users')
        .then((response) => {
          let users = response.data.data;
          this.users = users.map((user) => {
            user.deleting = false;
            return user;
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

export default retrievesUsers;