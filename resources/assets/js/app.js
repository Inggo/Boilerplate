import Vue from 'vue';
import Buefy from 'buefy';
import VueRouter from 'vue-router';

import { store, storeMixins } from './store';
import messages from 'Mixins/messages';

// Load Vendors
Vue.use(Buefy);
Vue.use(VueRouter);

// Load Components
Vue.component('default-transition', require('Components/Transition'));
Vue.component('boilerplate-login', require('Boilerplate/Login'));
Vue.component('boilerplate-admin', require('Boilerplate/Admin'));
Vue.component('boilerplate-nav', require('Boilerplate/Navigation'));

import { router } from './router';

var app = new Vue({
  el: '#boilerplate',
  store,
  router,
  mixins: [storeMixins, messages],
  beforeMount () {
    var name = this.$el.attributes['data-app-name'].value;;
    store.commit('setName', {
      name: name
    });
  }
});
