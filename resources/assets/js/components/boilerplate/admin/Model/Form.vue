<template>
  <section class="columns">
    <div class="column is-four-fifths">
      <b-field horizontal label="Name" :type="nameStatus.type" :message="nameStatus.message">
          <b-input 
            type="text" 
            v-model="name" 
            @input="clearErrors('name')"
          ></b-input>
      </b-field>

      <b-field horizontal label="Users" :type="usersStatus.type" :message="usersStatus.message">
        <users-table
          :selected-users="selectedUsers"
          :initial-selected="initialSelectedUserIds"
          :show-last-activity="false"
          :show-actions="false"
          show-selection
          filterable
          paginated
          @update-selection="updateSelectedUsers"
        ></users-table>
      </b-field>

      <b-field horizontal>
        <button
          class="button is-primary has-icon"
          @click="submit"
          :disabled="invalid"
          :loading="isLoading"
        >
          <b-icon :icon="editing ? 'pencil' : 'plus'" size="is-small"></b-icon>
          <span v-if="editing">Update Model</span>
          <span v-else>Create Model</span>
        </button>
      </b-field>
    </div>
  </section>
</template>

<script>
import { storeMixins } from '@/store';

import { axios, messages, selectsUsers, submit } from 'Mixins/mixins';

import UsersTable from 'Admin/Users/Table';

export default {
  data () {
    return {
      name: "",
      initialSelectedUserIds: [],
      modelSlug: 'models'
    }
  },
  props: {
    editing: {
      type: Boolean,
      default: false
    },
    editingModel: {
      type: Object,
      default: null
    }
  },
  watch: {
    editingModel (newModel) {
      this.name = newModel.name;

      this.initialSelectedUserIds = newModel.users.map(user => {
        return user.id;
      });
    }
  },
  mixins: [ storeMixins, messages, selectsUsers, submit ],
  components: { UsersTable },
  computed: {
    invalid () {
      return this.name == "" || this.invalidName;
    },

    invalidName () {
      return this.name !== "" && this.name.length === 0;
    },

    nameStatus () {
      if (this.invalidName) {
        return {
          type: "is-danger",
          message: "Model name is required."
        };
      }

      return this.getErrors('name');
    },

    formData () {
      let formData = {
        name: this.name,
        users: this.selectedUserIds
      };

      return formData;
    }
  },
  methods: {
    handleResponse (response) {
      let model = response.data.data;

      this.success(`Successfully ${this.action} ${model.name} (#${model.id})`);

      this.setNewModel(model);
      this.$router.push('/models/');
    }
  }
}
</script>