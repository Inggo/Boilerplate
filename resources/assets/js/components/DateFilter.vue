<template>
  <div class="date-filter">
    <b-field :label="label">
      <b-datepicker
        placeholder="Start Date"
        icon="calendar-today"
        @input="setStartDate"
        v-model="startDate"
        :date-parser="parseDate"
        :date-formatter="formatDate"
        position="is-bottom-left"
      >
        <button class="button is-primary"
            @click="setStartDateToday">
            <b-icon icon="calendar-today"></b-icon>
            <span>Today</span>
        </button>

        <button class="button is-danger"
            @click="clearStartDate">
            <b-icon icon="close"></b-icon>
            <span>Clear</span>
        </button>
      </b-datepicker>
      <b-datepicker
        v-if="startDate"
        placeholder="End Date"
        icon="calendar"
        @input="setEndDate"
        v-model="endDate"
        :min-date="startDate"
        :date-parser="parseDate"
        :date-formatter="formatDate"
        position="is-bottom-left"
      >
        <button class="button is-primary"
            @click="setEndDateToday">
            <b-icon icon="calendar-today"></b-icon>
            <span>Today</span>
        </button>

        <button class="button is-danger"
            @click="clearEndDate">
            <b-icon icon="close"></b-icon>
            <span>Clear</span>
        </button>
      </b-datepicker>
    </b-field>
  </div>
</template>

<script>
import format from 'date-fns/format';

export default {
  data () {
    return {
      startDate: null,
      endDate: null
    };
  },
  props: {
    start: {
      type: Date,
      default: null
    },
    end: {
      type: Date,
      default: null
    },
    label: {
      type: String,
      default: ""
    }
  },
  watch: {
    start (newDate, oldDate) {
      this.startDate = newDate;
    },
    end (newDate, oldDate) {
      this.endDate = newDate;
    }
  },
  methods: {
    setStartDate (date) {
      this.$emit('set-start-date', date);
    },
    setEndDate (date) {
      this.$emit('set-end-date', date);
    },

    setStartDateToday () {
      this.$emit('set-start-date', new Date());
    },
    setEndDateToday () {
      this.$emit('set-end-date', new Date());
    },

    clearStartDate () {
      this.$emit('set-start-date', null);
    },
    clearEndDate () {
      this.$emit('set-end-date', null);
    },

    parseDate (date) {
      return new Date(date);
    },

    formatDate (date) {
      if (!date) {
        return null;
      }

      return format(date, 'YYYY-MM-DD');
    }
  }
}
</script>