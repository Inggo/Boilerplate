import axios from 'Mixins/axios';

const retrievesActivities = {
  data () {
    return {
      dataLoading: false,
      activities: []
    }
  },
  methods: {
    retrieveActivities () {
      this.dataLoading = true;
  
      axios.get('/api/activity/')
        .then(response => {
          this.activities = response.data.data;
        })
        .catch(error => {
          this.catchUnauthorized(error);
        })
        .finally(() => {
          this.dataLoading = false;
        });
    }
  }
};

export default retrievesActivities;