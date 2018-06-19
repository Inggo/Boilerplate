<template>
  <transition name="hide"
    @enter="enter"
    @leave="leave"
    @afterEnter="afterEnter"
    :css="false"
  >
    <slot></slot>
  </transition>
</template>

<script>
var Velocity = require('velocity-animate/velocity.min.js');

export default {
  methods: {
    enter: function (el, done) {
      Velocity(el, { height: 0, opacity: 0, paddingTop: 0, paddingBottom: 0, marginTop: 0, marginBottom: 0 }, { duration: 1, complete: () => {
        Velocity(el, 'reverse', { duration: 299, complete: done });
      }});
    },
    afterEnter: function (el, done) {
      el.style.height = null;
    },
    leave: function (el, done) {
      Velocity(el, { height: 0, opacity: 0, paddingTop: 0, paddingBottom: 0, marginTop: 0, marginBottom: 0 }, { duration: 300, complete: done });
    }
  }
};
</script>