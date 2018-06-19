<template>
  <transition name="default"
    @beforeEnter="beforeEnter"
    @enter="enter"
    @leave="leave"
    :css="false"
  >
    <slot></slot>
  </transition>
</template>

<script>
var Velocity = require('velocity-animate/velocity.min.js');

export default {
  methods: {
    beforeEnter: function (el) {
      el.style.opacity = 0;
    },
    enter: function (el, done) {
      Velocity(el, { scale: 0 }, { duration: 1, complete: () => {
        Velocity(el, { opacity: 1, scale: 1 }, { duration: 299, complete: done });
      }});
    },
    leave: function (el, done) {
      Velocity(el, { opacity: 0, scale: 2 }, { duration: 300, complete: done });
    }
  }
};
</script>