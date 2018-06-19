import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'Mixins/axios';

var _ = require ('lodash');

Vue.use(Vuex);

export const store = new Vuex.Store({
  state: {
    name: "",
    loading: false,
    user: window.user ? window.user : null,
    token: null,
    error: null,

    // Newly created models
    newModel: null
  },

  mutations: {
    // name mutations
    setName (state, payload) {
      state.name = payload.name;
    },

    // loading mutations
    load (state) {
      state.loading = true;
    },
    unload (state) {
      state.loading = false;
    },

    // user mutations
    setUser (state, payload) {
      state.user = payload.user;
    },
    clearUser (state) {
      state.user = null;
    },

    // token mutations
    setToken (state, payload) {
      state.token = payload;
    },
    clearToken (state) {
      state.token = null;
    },

    // error mutations
    setErrorMessage (state, payload) {
      state.error = payload;
    },
    clearError (state) {
      state.error = null;
    },

    // newModel mutations
    setNewModel (state, payload) {
      state.newModel = payload;
    },
    clearNewModel (state) {
      state.newModel = null;
    }
  }
});

export const storeMixins = {
  computed: {
    allowedRolesToAssign() {
      return this.user ? this.user.allowed_roles_to_assign.map((role) => {
        if (role == 'admin') {
          return { value: role, label: 'Administrator' };
        }

        return { value: role, label: _.upperFirst(role) }
      }) : [];
    },
    appName () {
      return this.$store.state.name;
    },
    isLoggedIn () {
      return !_.isEmpty(this.$store.state.user)
    },
    isLoading () {
      return this.$store.state.loading;
    },
    user () {
      return this.$store.state.user;
    },
    token () {
      return this.$store.state.token;
    },
    hasError () {
      return Boolean(this.$store.state.error);
    },
    errorMessage () {
      if (this.hasError) {
        return this.$store.state.error;
      }
    },
    newModel () {
      return this.$store.state.newModel;
    }
  },

  methods: {
    load () {
      this.$store.commit('load');
    },
    unload () {
      this.$store.commit('unload');
    },

    setUser (user) {
      this.$store.commit('setUser', {
        user: user
      });
    },
    clearUser () {
      this.$store.commit('clearUser');
    },

    setToken (token) {
      this.$store.commit('setToken', token);
    },
    clearToken () {
      this.$store.commit('clearToken');
    },

    clearNewModel () {
      this.$store.commit('clearNewModel');
    }
  }
};

export const clearsNewModel = {
  beforeRouteLeave (to, from, next) {
    this.clearNewModel();
    next();
  }
};
