<template>
  <main>
    <t-header title="Activity Log" icon="filter-variant"></t-header>
    <activity-table filterable></activity-table>
  </main>
</template>

<script>
import { storeMixins } from '@/store';

import THeader from 'Components/Header';
import ActivityTable from 'Admin/Activity/Table';

export default {
  components: { THeader, ActivityTable },
  mixins: [ storeMixins ]
};
</script>
