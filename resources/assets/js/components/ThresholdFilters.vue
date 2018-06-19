<template>
  <section class="advanced-filters">
    <height-transition>
      <div class="box" v-if="active">
        <b-field label="Threshold Filters">
          <div class="control">
            <b-checkbox
              v-for="filter in thresholdFilterOptions"
              :key="filter.value"
              :native-value="filter.value"
              v-model="thresholdFilters"
            >{{ filter.label }}</b-checkbox>
          </div>
        </b-field>
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
      thresholdFilterOptions: [
        { label: 'Above Threshold', value: 'above-threshold' },
        { label: 'On Threshold', value: 'on-threshold' },
        { label: 'Below Threshold', value: 'below-threshold' },
        { label: 'No Stock', value: 'no-stock' },
        { label: 'Negative Stock' , value: 'negative-stock' }
      ],
      thresholdFilters: [
        'above-threshold',
        'on-threshold',
        'below-threshold',
        'no-stock',
        'negative-stock'
      ]
    };
  },
  props: {
    active: Boolean
  },
  mounted () {
    this.$emit('set-threshold-filters', this.thresholdFilters);
  },
  watch: {
    thresholdFilters (filters) {
      this.$emit('set-threshold-filters', filters);
    }
  }
};
</script>