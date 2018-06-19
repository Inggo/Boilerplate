<template>
  <main>
    <t-header :title="title" icon="account-edit"></t-header>
    <user-form
      :editing="true"
      :editing-user="retrievedUser"
    ></user-form>
  </main>
</template>

<script>
import { storeMixins } from '@/store';
import THeader from 'Components/Header';
import UserForm from 'Admin/Users/Form';

import retrievesUser from 'Mixins/retrieves/user';

export default {
  components: { UserForm, THeader },
  mixins: [ storeMixins, retrievesUser ],
  computed: {
    title () {
      let title = 'Editing User #' + this.$route.params.id;
      if (!this.retrievedUser) {
        return title;
      }

      return `${title} (${this.retrievedUser.name})`;
    }
  },
  mounted () {
    this.load();
    this.retrieveUser(this.$route.params.id)
      .then(() => {
        this.unload();
      })
  }
};
</script>
