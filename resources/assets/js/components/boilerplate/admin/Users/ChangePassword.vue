<template>
  <main>
    <t-header :title="title" icon="pencil-lock"></t-header>
    <user-form
      v-if="authorized"
      :changing-password="true"
      :editing-user="retrievedUser"
    ></user-form>
  </main>
</template>

<script>
import { storeMixins } from '@/store';
import THeader from 'Components/Header';
import UserForm from 'Admin/Users/Form';

import axios from 'Mixins/axios';

export default {
  data () {
    return {
      authorized: false,
      retrievedUser: null
    }
  },
  components: { UserForm, THeader },
  mixins: [ storeMixins ],
  computed: {
    title () {
      if (!this.$route.params.id || this.$route.params.id == this.user.id) {
        return 'Change Password';
      }

      let title = `Change Password for User #${this.$route.params.id}`;

      if (!this.retrievedUser) {
        return title;
      }

      return `${title} (${this.retrievedUser.name})`;
    },

    id () {
      return this.$route.params.id || this.user.id;
    }
  },
  mounted () {
    this.authorizeRequest();
  },
  methods: {
    authorizeRequest () {
      this.load();

      return axios.get('/api/change-password/' + this.id)
        .then(response => {
          let data = response.data;

          if (data.allowed) {
            this.authorized = true;
          }

          if (data.user.id == this.$route.params.id) {
            this.retrievedUser = data.user;
          }
        })
        .finally(() => {
          this.unload();
        })

    }
  }
};
</script>
