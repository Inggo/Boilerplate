<template>
  <main class="section">
    <section class="container">
      <div class="columns">
        <div class="column is-half is-offset-one-quarter">
          <b-field label="Username" :type="usernameType" :message="usernameMessage">
            <b-input v-model="username" type="username" @input="clearErrors('username')" @keyup.native.enter="submitIfValid" @keypress.native.space.prevent></b-input>
          </b-field>

          <b-field label="Password" :type="passwordType" :message="passwordMessage">
            <b-input v-model="password" type="password" :password-reveal="true" @input="clearErrors('password')" @keyup.native.enter="submitIfValid"></b-input>
          </b-field>

          <p class="has-text-right">
            <button class="button is-primary is-right" @click="signIn" :disabled="invalid">Sign In</button>
          </p>
        </div>
      </div>
    </section>
  </main>
</template>

<script>
import { storeMixins } from '@/store';
import axios from 'Mixins/axios';
import messages from 'Mixins/messages';

export default {
  mixins: [storeMixins, messages],
  data () {
    return {
      username: "",
      password: ""
    }
  },
  computed: {
    invalid () {
      return this.username.length == 0 || this.password.length == 0;
    },

    usernameType () {
      if (this.errors.username) {
        return "is-danger";
      }

      return null;
    },

    usernameMessage () {
      return this.errors.username;
    },

    passwordType () {
      if (this.errors.password) {
        return "is-danger";
      }

      return null;
    },
    passwordMessage () {
      return this.errors.password;
    }
  },
  methods: {
    submitIfValid () {
      if (!this.invalid) {
        this.signIn();
      }
    },

    signIn () {
      this.clearAllErrors();
      this.load();

      axios.post('/login', {
          email: this.username,
          username: this.username,
          password: this.password
        })
        .then((response) => {
          window.location.reload();
        })
        .catch((error) => {
          this.setErrors(error);
          this.unload();
        });
    }
  }
};
</script>
