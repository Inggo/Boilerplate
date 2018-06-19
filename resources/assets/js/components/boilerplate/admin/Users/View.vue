<template>
  <main>
    <t-header :title="title" icon="account-search"></t-header>
    <activity-table
      :of-user="retrievedUser"
      filterable
    ></activity-table>
  </main>
</template>

<script>
import { storeMixins } from '@/store';
import THeader from 'Components/Header';
import ActivityTable from 'Admin/Activity/Table';

import retrievesUser from 'Mixins/retrieves/user';

export default {
  components: { ActivityTable, THeader },
  mixins: [ storeMixins, retrievesUser ],
  computed: {
    title () {
      if (!this.$route.params.id) {
        return 'Your Activity';
      }

      let title = 'Activity of User #' + this.$route.params.id;

      if (!this.retrievedUser) {
        return title;
      }

      return `${title} (${this.retrievedUser.name})`;
    }
  },
  mounted () {
    this.load();
    this.retrieveUser(this.$route.params.id ? this.$route.params.id : this.user.id)
      .then(() => {
        this.unload();
      })
  }
};
</script>
