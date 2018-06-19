<template>
  <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
    <div class="container">
      <div class="navbar-brand">
        <router-link class="navbar-item" to="/">
          <b>{{ appName }}</b>
        </router-link>

        <a v-if="isLoggedIn" :class="{ 'is-active': active }" role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" @click="toggleMenu">
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>
      <div v-if="isLoggedIn" class="navbar-menu" :class="{ 'is-active': active }">
        <div class="navbar-start">
          <div
            v-for="(item, index) in navItems"
            :class="{
              'has-dropdown': hasChildren(item),
              'is-hoverable': hasChildren(item),
            }"
            class="navbar-item"
            :key="index"
          >
            <a class="navbar-link" v-if="hasChildren(item)">
              {{ item.label }}
            </a>
            <div class="navbar-dropdown" v-if="hasChildren(item)">
              <hr 
                v-for="(child, childIndex) in item.children"
                v-if="child.hr"
                class="navbar-divider"
                :key="childIndex"
              >
              <router-link
                v-else
                @click.native="toggleMenu"
                class="navbar-item"
                :to="child.link"
                :key="childIndex"
              >{{ child.label }}</router-link>
            </div>
            <router-link
              v-else
              @click.native="toggleMenu"
              :to="item.link"
            >{{ item.label }}</router-link>
          </div>
        </div>

        <div class="navbar-end">
          <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">
              <b>{{ user.name }}</b>
            </a>
            <div class="navbar-dropdown is-right">
              <router-link class="navbar-item" to="/change-password">
                Change Password
              </router-link>
              <router-link class="navbar-item" to="/user/activity">
                Activity
              </router-link>
              <a class="navbar-item" @click="logout">Logout</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import { storeMixins } from '@/store';
import axios from 'Mixins/axios';
import messages from 'Mixins/messages';

export default {
  data () {
    return {
      active: false,
      navItems: [
        {
          label: 'Administration',
          children: [
            { label: 'Users', link: '/users' },
            { label: 'Storages', link: '/storages' },
            { label: 'Branches', link: '/branches' },
            { label: 'Activity', link: '/activity' },
          ]
        },
        {
          label: 'Costing',
          children: [
            { label: 'Ingredients', link: '/ingredients' },
            { label: 'Recipes', link: '/recipes' },
            { label: 'Prices', link: '/prices' },
            { hr: true },
            { label: 'Unit Conversions', link: '/unit-conversions' },
          ]
        },
        {
          label: 'Inventory',
          children: [
            { label: 'Inventory Management', link: '/inventory' },
            { label: 'Check-In', link: '/inventory/check-in' },
            { label: 'Check-Out', link: '/inventory/check-out' },
            { hr: true },
            { label: 'Weekly Report', link: '/inventory/reports/weekly' },
          ]
        }
      ]
    }
  },
  mixins: [storeMixins, messages],
  methods: {
    hasChildren (item) {
      return _.has(item, 'children');
    },

    logout () {
      this.load();
      axios.get('/logout')
        .then((response) => {
          this.$router.push('/');
          window.location.reload();
        })
        .catch((error) => {
          this.setErrors(error);
          this.unload();
        });
    },

    toggleMenu () {
      this.active = !this.active;
    }
  }
};
</script>
