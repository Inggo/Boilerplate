<template>
  <main>
    <t-header title="Users" icon="account-multiple">
      <template slot="left">
        <router-link to="/users/create" class="button level-item is-success has-icon">
          <b-icon icon="account-plus" size="is-small"></b-icon>
          <span>Create User</span>
        </router-link>
      </template>
    </t-header>
    <users-table filterable></users-table>
  </main>
</template>

<script>
import { storeMixins, clearsNewModel } from '@/store';

import THeader from 'Components/Header';
import UsersTable from 'Admin/Users/Table';

export default {
  components: { UsersTable, THeader },
  mixins: [ storeMixins, clearsNewModel ]
};
</script>
