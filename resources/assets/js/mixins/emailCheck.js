import axios from 'Mixins/axios';
import validEmail from 'Mixins/validEmail';

const emailCheck = {
  data () {
    return {
      checkingEmail: false,
      emailAvailable: true
    };
  },
  mixins: [ validEmail ],
  watch: {
    email (oldEmail, newEmail) {
      this.emailAvailable = true;
    }
  },
  methods: {
    checkEmail () {
      if (!this.validEmail(this.email)) {
        return;
      }

      this.checkingEmail = true;

      axios.post('/api/check-email', {
          email: this.email,
          editing: this.editing,
          user: this.$route.params.id
        })
        .then(response => {
          if (!response.data.available) {
            this.emailAvailable = false;
          }
        })
        .catch((error) => {
          this.setErrors(error);
        })
        .finally(() => {
          this.checkingEmail = false;
        });
    },
  }
};

export default emailCheck;