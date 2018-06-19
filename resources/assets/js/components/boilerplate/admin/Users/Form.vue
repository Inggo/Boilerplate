<template>
  <section class="columns">
    <div class="column is-three-fifths has-password-confirmation">
      <b-field
        v-if="!changingPassword"
        horizontal
        label="Name"
        :type="nameStatus.type"
        :message="nameStatus.message"
      >
        <b-input 
          type="input" 
          v-model="name" 
          @input="clearErrors('name')"
        ></b-input>
      </b-field>

      <b-field
        v-if="!changingPassword"
        horizontal
        label="Username"
        :type="usernameStatus.type"
        :message="usernameStatus.message"
        @keypress.native.space.prevent
      >
        <b-input 
          type="input" 
          v-model="username" 
          @blur="checkUsername"
          @input="clearErrors('username')"
        ></b-input>
      </b-field>

      <b-field
        v-if="!changingPassword"
        horizontal
        label="Email"
        :type="emailStatus.type"
        :message="emailStatus.message"
      >
        <b-input 
          type="email" 
          v-model="email" 
          @blur="checkEmail" 
          @input="clearErrors('email')" 
          :loading="checkingEmail"
        ></b-input>
      </b-field>
      
      <b-field
        v-if="changingOwnPassword"
        horizontal
        label="Current Password"
        :type="currentPasswordStatus.type"
        :message="currentPasswordStatus.message"
      >
        <b-input
          type="password"
          v-model="currentPassword"
          password-reveal
          @input="clearErrors('current_password')"
        ></b-input>
      </b-field>

      <b-field
        v-if="changingPassword || !editing"
        horizontal
        label="Password"
        :type="passwordStatus.type"
        :message="passwordStatus.message"
      >
        <b-input
          type="password"
          v-model="password"
          password-reveal
          @input="clearErrors('password')"
        ></b-input>
      </b-field>
        
      <b-field
        v-if="changingPassword || !editing"
        horizontal
        label="Repeat Password"
        :type="repeatPasswordStatus.type"
        :message="repeatPasswordStatus.message"
      >
        <b-input type="password" v-model="confirmPassword" password-reveal></b-input>
      </b-field>

      <b-field
        v-if="!changingPassword && roleChangeAllowed"
        horizontal
        label="Role"
        :type="roleStatus.type"
        :message="roleStatus.message"
      >
        <b-select v-model="role" @change="clearErrors('role')">
          <option v-for="role in allowedRolesToAssign"
            :value="role.value"
            :key="role.value"
          >{{ role.label }}</option>
        </b-select>
      </b-field>

      <b-field horizontal>
        <button
          class="button is-primary has-icon"
          @click="submit"
          :disabled="invalid"
          :loading="isLoading"
        >
          <span v-if="changingPassword">
            <b-icon icon="pencil-lock" size="is-small"></b-icon>
            <span>Change Password</span>
          </span>
          <span v-else-if="editing">
            <b-icon icon="account-edit" size="is-small"></b-icon>
            <span>Update User</span>
          </span>
          <span v-else>
            <b-icon icon="account-plus" size="is-small"></b-icon>
            <span>Create User</span>
          </span>
        </button>
      </b-field>
    </div>
  </section>
</template>

<script>
import { storeMixins } from '@/store';

import { axios, messages, emailCheck, usernameCheck, submit } from 'Mixins/mixins';

export default {
  data () {
    return {
      name: "",
      username: "",
      email: "",
      currentPassword: "",
      password: "",
      confirmPassword: "",
      role: 'user',

      checkingEmail: false,
      emailAvailable: true,

      modelSlug: 'users'
    };
  },
  props: {
    changingPassword: {
      type: Boolean,
      default: false
    },
    editing: {
      type: Boolean,
      default: false
    },
    editingUser: {
      type: Object,
      default: null
    }
  },
  mixins: [ storeMixins, messages, emailCheck, usernameCheck, submit ],
  watch: {
    email () {
      this.emailAvailable = true;
    },

    editingUser (newUser) {
      this.name = newUser.name;
      this.username = newUser.username;
      this.email = newUser.email;
      this.role = newUser.role;

      this.initialSelectedStorageIds = newUser.storages.map(storage => {
        return storage.id;
      });

      this.initialSelectedBranchIds = newUser.branches.map(branch => {
        return branch.id;
      });
    }
  },
  computed: {
    changingOwnPassword () {
      return this.changingPassword && !this.editingUser;
    },

    invalid () {
      let passwordInvalid = this.password == "" || this.invalidPassword || this.confirmPassword != this.password;
      let dataInvalid = this.checkingEmail || this.checkingUsername || this.name == "" || this.invalidName || this.username == "" || this.invalidUsername || !this.validEmail(this.email);

      if (this.changingOwnPassword) {
        return this.currentPassword == "" || passwordInvalid;
      }

      if (this.changingPassword) {
        return passwordInvalid;
      }

      if (this.editing) {
        return dataInvalid;
      }

      return dataInvalid || passwordInvalid;
    },

    invalidName () {
      return this.name.length < 4 && this.name.length > 0;
    },

    invalidUsername () {
      return this.username.length < 4 && this.username.length > 0;
    },

    invalidPassword () {
      return this.password.length < 6 && this.password.length > 0;
    },

    nameStatus () {
      if (this.invalidName) {
        return {
          type: "is-danger",
          message: "Length must be at least 4 characters."
        };
      }

      return { type: this.errors.name ? "is-danger" : null, message: this.errors.name };
    },

    usernameStatus () {
      if (!this.usernameAvailable) {
        return {
          type: "is-danger",
          message: "Username is unavailable."
        }
      }

      if (this.invalidUsername) {
        return {
          type: "is-danger",
          message: "Length must be at least 4 characters."
        };
      }

      return this.getErrors('username');
    },

    emailStatus () {
      if (!this.emailAvailable) {
        return {
          type: "is-danger",
          message: "Email is unavailable."
        }
      }

      return this.getErrors('email');
    },

    currentPasswordStatus () {
      return this.getErrors('current_password');
    },

    passwordStatus () {
      if (this.invalidPassword) {
        return {
          type: "is-danger",
          message: "Password must be at least 6 characters."
        }
      }

      return this.getErrors('password');
    },

    repeatPasswordStatus () {
      if (this.confirmPassword.length > 0 && this.confirmPassword != this.password) {
        return {
          type: "is-danger",
          message: "Passwords do not match."
        };
      }

      return this.getErrors('password_confirmation');
    },

    roleStatus () {
      if (this.role == 'owner') {
        return {
          type: "is-warning",
          message: "Assigning Owner role will give global permissions and cannot be edited or deleted."
        };
      }

      return { type: this.errors.role ? "is-danger" : null, message: this.errors.role };
    },

    roleChangeAllowed () {
      if (!this.editing) {
        return true;
      }

      if (!this.editingUser) {
        return false;
      }

      return !this.editingUser.role === 'owner';
    },

    formData () {
      if (this.changingPassword) {
        let formData = {
          password: this.password,
          password_confirmation: this.confirmPassword
        }

        if (this.changingOwnPassword) {
          formData.current_password = this.currentPassword;
        }

        if (this.editingUser) {
          formData.id = this.editingUser.id;
        }

        return formData;
      }

      let formData = {
        name: this.name,
        username: this.username,
        email: this.email,
        storages: this.selectedStorageIds,
        branches: this.selectedBranchIds
      }

      if (this.roleChangeAllowed) {
        formData.role = this.role;
      }

      if (!this.editing) {
        formData.password = this.password;
        formData.password_confirmation = this.confirmPassword;
      }

      return formData;
    },

    formMethod () {
      if (this.changingPassword) {
        return 'patch';
      }
      
      return this.editing
        ? 'patch'
        : 'post'
      ;
    },

    formUrl () {
      if (this.changingPassword) {
        return '/api/change-password/';
      }

      return this.editing
        ? '/api/users/' + this.$route.params.id
        : '/api/users'
      ;
    },

    action () {
      if (this.changingPassword) {
        return 'changed password of';
      }

      return this.editing
        ? 'updated'
        : 'created'
      ;
    }
  },
  methods: {
    handleResponse (response) {
      let user = response.data.data;

      if (this.changingOwnPassword) {
        this.success('Successfully changed password');
        this.$router.go(-1);
        return;
      }

      this.success(`Successfully ${this.action} ${user.role_label}
        account for ${user.name} (#${user.id})`
      );
      this.setNewModel(user);
      this.$router.push('/users/');
    }
  }
};
</script>