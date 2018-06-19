<template>
  <main class="section">
    <div class="container">
      <transition :name="transitionName" mode="out-in">
        <router-view></router-view>
      </transition>
    </div>
  </main>
</template>

<script>
var Velocity = require('velocity-animate/velocity.min.js');

export default {
  data () {
    return {
      transitionName: 'slide-right'
    };
  },
  watch: {
    '$route' (to, from) {
      const toDepth = to.path.split('/').filter((item) => {
        return item != "";
      }).length;
      const fromDepth = from.path.split('/').filter((item) => {
        return item != "";
      }).length;
      this.transitionName = toDepth < fromDepth ? 'slide-right' : 'slide-left'
    }
  }
};
</script>