<template>
  <main>
    <t-header title="Models" icon="store">
      <template slot="left">
        <router-link to="/models/create" class="button level-item is-success has-icon">
          <b-icon icon="plus" size="is-small"></b-icon>
          <span>Create Model</span>
        </router-link>
      </template>
    </t-header>
    <models-table filterable></models-table>
  </main>
</template>

<script>
import { storeMixins, clearsNewModel } from '@/store';

import THeader from 'Components/Header';
import ModelsTable from 'Admin/Models/Table';

export default {
  components: { ModelsTable, THeader },
  mixins: [ storeMixins, clearsNewModel ]
};
</script>
