import VueRouter from 'vue-router';
import Dashboard from 'Admin/Dashboard';

import Users from 'Admin/Users';
import UserCreate from 'Admin/Users/Create';
import UserView from 'Admin/Users/View';
import UserEdit from 'Admin/Users/Edit';
 
import ChangePassword from 'Admin/Users/ChangePassword';
import ActivityLog from 'Admin/ActivityLog';
 
const routes = [
  { path: '/', component: Dashboard },

  { path: '/users', component: Users },
  { path: '/users/create', component: UserCreate },
  { path: '/users/:id', component: UserView },
  { path: '/users/:id/edit', component: UserEdit },
  { path: '/change-password/:id?', component: ChangePassword },

  { path: '/activity', component: ActivityLog },
  { path: '/user/activity', component: UserView },
];

export const router = new VueRouter({
  routes,
  mode: "history",
  linkExactActiveClass: "is-active"
});
