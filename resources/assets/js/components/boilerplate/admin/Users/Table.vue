<template>
  <div>
    <filter-utils 
      v-if="filterable && !showSelection"
      :items="items"
      :filtered-items="filteredItems"
      @update-filter="updateFilter"
    ></filter-utils>
    <b-table
      :data="filteredItems"
      striped
      hoverable
      mobile-cards
      :row-class="(row, index) => rowClass(row)"
      :checkable="showSelection"
      :checked-rows.sync="selected"
      :paginated="paginated"
      :per-page="itemsPerPage"
      :loading="dataLoading"
      :narrowed="isNarrowed"
    >
      <template slot-scope="props">
        <b-table-column
          field="id"
          label="ID"
          width="40"
          numeric sortable
          v-html="applyHighlight(props.row.id)"
        ></b-table-column>

        <b-table-column
          field="name"
          label="Name"
          sortable
          v-html="applyHighlight(props.row.name)"
        ></b-table-column>

        <b-table-column
          field="username"
          label="Username"
          sortable
          v-html="applyHighlight(props.row.username)"
        ></b-table-column>

        <b-table-column
          field="email"
          label="Email"
          sortable
          v-html="applyHighlight(props.row.email)"
        ></b-table-column>

        <b-table-column
          field="role_level"
          label="Role"
          sortable
          centered
        >
          <b-tag
            :type="roleClass(props.row.role)"
            v-html="applyHighlight(props.row.role_label)"
            rounded
            size="is-medium"
          ></b-tag>
        </b-table-column>

        <b-table-column label="Actions" numeric v-if="showActions">
          <b-dropdown position="is-bottom-left" class="has-text-left">
            <button class="button is-primary" slot="trigger">
              <span>Actions</span>
                <b-icon icon="menu-down"></b-icon>
            </button>

            <b-dropdown-item
              v-for="(action, index) in allowedActionsFor(props.row)"
              :key="index"
              :has-link="action.link ? true : false"
              v-on=" action.click ? { click: action.click } : null "
            >
              <router-link :to="action.link" v-if="action.link">
                <icon-label :icon="action.icon" :label="action.label"></icon-label>
              </router-link>
              <span v-else>
                <icon-label :icon="action.icon" :label="action.label"></icon-label>
              </span>
            </b-dropdown-item>
          </b-dropdown>
        </b-table-column>

      </template>
      <default-empty slot="empty" v-if="!dataLoading"></default-empty>
      <table-utils
        slot="bottom-left"
        :selected="selected"
        :items="items"
        :filteredItems="filteredItems"
        :showSelection="showSelection"
        :filterable="filterable"
        @select-all="selected = items"
        @clear-all="selected = []"
        @update-filter="updateFilter"
      ></table-utils>
    </b-table>
  </div>
</template>

<script>
import { storeMixins } from '@/store';

import { axios, messages, highlighting, filtering, selectableTable, retrievesUsers, deleteModel } from 'Mixins/mixins';

import DefaultEmpty from 'Components/DefaultEmpty';
import IconLabel from 'Components/IconLabel';
import FilterUtils from 'Components/FilterUtils';
import TableUtils from 'Components/TableUtils';

export default {
  data () {
    return {
      users: [],
      modelName: 'User Account',
      modelSlug: 'users',
      filterableKeys: [
        'id', 'name', 'username', 'email', 'role', 'role_label'
      ]
    }
  },
  components: { FilterUtils, TableUtils, DefaultEmpty, IconLabel },
  props: {
    selectedUsers: {
      type: Array,
      default () {
        return [];
      }
    },
    showLastActivity: {
      type: Boolean,
      default: true
    },
    showActions: {
      type: Boolean,
      default: true
    },
  },
  mixins: [ storeMixins, messages, highlighting, filtering, selectableTable, retrievesUsers, deleteModel ],
  computed: {
    items () {
      return this.users;
    }
  },
  mounted () {
    this.selected = this.selectedUsers;
    this.retrieveUsers();
  },
  methods: {
    roleClass (role) {
      if (role == 'owner') {
        return 'is-danger';
      }

      if (role == 'admin') {
        return 'is-warning';
      }

      if (role == 'manager') {
        return 'is-info';
      }

      return 'is-primary';
    },

    allowedActionsFor (user) {
      var actions = [];

      if (this.isSelf(user) || this.canViewActivity(user.role)) {
        actions.push({
          link: '/users/' + user.id,
          label: 'View user activity',
          icon: 'account-search'
        });
      }

      if (this.isSelf(user) || this.canEdit(user.role)) {
        actions.push({
          link: '/users/' + user.id + '/edit',
          label: 'Edit user details',
          icon: 'account-edit'
        });

        actions.push({
          link: '/change-password/' + user.id,
          label: 'Change user password',
          icon: 'pencil-lock'
        });
      }

      if (this.canDelete(user.role)) {
        actions.push({
          label: 'Delete user',
          click: () => {
            this.confirmDelete(user)
          },
          icon: 'account-remove'
        });
      }

      return actions;
    },

    canDelete (role) {
      if (role == 'owner') {
        return false;
      }

      if (role == 'admin' && user.role != 'owner') {
        return false;
      }

      return true;
    },

    canEdit (role) {
      if (role == 'owner') {
        return false;
      }

      if (role == 'admin' && user.role != 'owner') {
        return false;
      }

      return true;
    },

    canViewActivity (role) {
      if (user.role == 'owner') {
        return true;
      }

      if (role == 'owner') {
        return false;
      }

      return true;
    },

    isSelf (user) {
      return this.user.id === user.id;
    },

    retrieveItems  () {
      return this.retrieveUsers();
    }
  }
};
</script>
