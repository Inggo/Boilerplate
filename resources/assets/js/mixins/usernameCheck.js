import axios from 'Mixins/axios';

const usernameCheck = {
  data () {
    return {
      checkingUsername: false,
      usernameAvailable: true
    }
  },
  watch: {
    username (oldUsername, newUsername) {
      this.usernameAvailable = true;
    }
  },
  methods: {
    checkUsername () {
      if (this.invalidUsername) {
        return;
      }

      this.checkingUsername = true;

      axios.post('/api/check-username', {
          username: this.username,
          editing: this.editing,
          user: this.$route.params.id
        })
        .then((response) => {
          if (!response.data.available) {
            this.usernameAvailable = false;
          }
        })
        .catch((error) => {
          this.setErrors(error);
        })
        .finally(() => {
          this.checkingUsername = false;
        });
    },
  }
};

export default usernameCheck;