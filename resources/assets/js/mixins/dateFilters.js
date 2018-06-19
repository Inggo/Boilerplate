import startOfDay from 'date-fns/start_of_day';
import endOfDay from 'date-fns/end_of_day';
import isAfter from 'date-fns/is_after';
import isBefore from 'date-fns/is_before';

const dateFilters = {
  data () {
    return {    
      startDateFilter: null,
      endDateFilter: null,
      dateKey: 'created_at'
    };
  },
  computed: {
    dateFilteredItems () {
      if (!this.startDateFilter) {
        return this.items;
      }

      return this.items.filter((item) => {
        let date = new Date(item[this.dateKey]);

        if (this.endDateFilter && isBefore(date, endOf(this.endDateFilter, 'day'))) {
          return false;
        }

        if (isAfter(date, startOf(this.startDateFilter, 'day'))) {
          return false;
        }

        return true;
      });
    }
  },
  methods: {
    setStartDate (date) {
      this.startDateFilter = date;
    },
    setEndDate (date) {
      this.endDateFilter = date;
    }
  }
};

export default dateFilters;