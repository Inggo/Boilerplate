<template>
  <section class="advanced-filters">
    <height-transition>
      <div class="content" v-if="active">
        <div class="box">
          <b-field label="Tag Filters">
            <div class="control">
              <b-radio v-model="tagFiltering" @input="$emit('set-tag-filtering', null)" :native-value="null">Disabled</b-radio>
              <b-radio v-model="tagFiltering" @input="$emit('set-tag-filtering', 'has')" native-value="has">Has Selected Tags</b-radio>
              <b-radio v-model="tagFiltering" @input="$emit('set-tag-filtering', 'has-all')" native-value="has-all">Has All Selected Tags</b-radio>
              <b-radio v-model="tagFiltering" @input="$emit('set-tag-filtering', 'without')" native-value="without">Without Selected Tags</b-radio>
            </div>
          </b-field>
          <b-field>
            <b-taginput
              v-model="activeTags"
              :data="filteredAllTags" 
              autocomplete
              @typing="getFilteredTags"
              icon="label"
              :disabled="!tagFiltering"
              placeholder="Select Tags Here..."
            ></b-taginput>
          </b-field>
        </div>
      </div>
    </height-transition>
  </section>
</template>

<script>
import HeightTransition from 'Components/HeightTransition';

export default {
  components: { HeightTransition },
  data () {
    return {
      activeTags: [],
      filteredAllTags: [],
      tagFiltering: null
    };
  },
  props: {
    active: Boolean,
    tags: {
      type: Array,
      default () {
        return [];
      }
    }
  },
  watch: {
    activeTags (tags) {
      this.$emit('set-active-tags', tags);
    }
  },
  methods: {
    getFilteredTags (text) {
      this.filteredAllTags = this.tags.filter(tag => {
        return tag.indexOf(text.toUpperCase()) > -1;
      });
    }
  }
};
</script>