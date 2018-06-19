const parsesActivity = {
  data () {
    return {
      appNamespace: "Inggo\\Boilerplate\\",
      parsedActivity: [],
      models: [
        'Model',
      ]
    };
  },
  methods: {
    toUl (list) {
      if (list.length == 0) {
        return "";
      }

      return `<ul><li>${list.join('</li><li>')}</li></ul>`;
    },

    isUser (type) {
      return type == this.appNamespace + 'User';
    },

    isModel (type) {
      return _.indexOf(this.models, this.parseModelName(type)) > -1;
    },

    parseActivity (activity) {
      activity.user = this.parseCauser(activity);
      activity.activity = this.parseDetails(activity);
      activity.expanded_details = this.parseExpandedDetails(activity);
      return activity;
    },

    isSelfActivity(activity) {
      return activity.causer && activity.subject && activity.causer.id === activity.subject.id;
    },

    parseDetails (activity) {
      if (this.isUser(activity.subject_type)) {
        return this.isSelfActivity(activity)
          ? this.parseSelfActivity(activity)
          : this.parseUserActivity(activity)
        ;
      }

      if (this.isModel(activity.subject_type)) {
        return this.parseModelActivity(activity);
      }

      return _.upperFirst(activity.description);
    },

    parseModelName (model) {
      return _.replace(model, this.appNamespace, '');
    },

    parseModelActivity (activity) {
      let model = this.parseModelName(activity.subject_type);

      if (!activity.subject) {
        return `${model} activity`;
      }

      return `${_.upperFirst(activity.description)} ${model} #${activity.subject.id} (${activity.subject.name})`;
    },

    parseUserActivity (activity) {
      if (!activity.subject) {
        return "User activity";
      }

      return _.upperFirst(activity.description) + ' User #' + activity.subject.id
        + ' (' + activity.subject.name + ')';
    },

    parseSelfActivity (activity) {
      if (_.isEmpty(activity.details) || _.isEmpty(activity.details.attributes)) {
        return 'Logged in';
      }

      return _.upperFirst(activity.description) + ' Self';
    },

    parseCauser (activity) {
      if (this.isUser(activity.causer_type)) {
        return '#' + activity.causer.id + ' (' + activity.causer.name + ')';
      }

      return "System";
    },

    parseExpandedDetails (activity) {
      if (activity.description == 'deleted' || (this.isSelfActivity(activity) && activity.activity === 'Logged In')) {
        return 'No further details available.';
      }

      let details = [];
      let attributes = _.keys(activity.details.attributes);

      _.each(attributes, (attribute) => {
        let detail = '';
        if (_.has(activity.details.old, attribute)) {
          if (_.isNull(activity.details.old[attribute])) {
            if (_.isNull(activity.details.attributes[attribute])) {
              return;
            }
            detail += `Set ${this.parseAttributeLabel(attribute)}`;
          } else if (_.isArray(activity.details.old[attribute])) {
            detail += this.parseArrayDifference(activity.details.old[attribute], activity.details.attributes[attribute], attribute);
          } else {
            detail += `Changed ${this.parseAttributeLabel(attribute)}
              from <i>&ldquo;${this.parseAttributeValue(activity.details.old[attribute], attribute)}&rdquo;</i>`;
          }
        } else {
          detail += `Set ${this.parseAttributeLabel(attribute)}`;
        }

        if (_.isNull(activity.details.attributes[attribute])) {
          if (_.has(activity.details.old, attribute) && !_.isNull(activity.details.old[attribute])) {
            detail = `Unset ${this.parseAttributeLabel(attribute)} from <i>&ldquo;${activity.details.old[attribute]}&rdquo;</i>`;
          } else {
            return;
          }
        } else if (!_.has(activity.details.old, attribute) && _.isArray(activity.details.attributes[attribute])) {
          console.log('no old');
        } else if (_.isArray(activity.details.attributes[attribute])) {
          console.log('attribute');
        } else {
          detail += ` to <b>&ldquo;${this.parseAttributeValue(activity.details.attributes[attribute], attribute)}&rdquo;</b>`;
        }
        details.push(detail);
      });

      if (details.length === 0) {
        return 'No changes made.';
      }

      return this.toUl(details);
    },

    parseArrayDifference (oldArray, newArray, attribute) {
      let diff = _.difference(oldArray.map(item => item.id), newArray.map(item => item.id));

      if (!_.isEmpty(diff)) {
        return `Removed ${this.parseDifferenceValues(oldArray, diff, attribute)}`
      }

      diff = _.difference(newArray.map(item => item.id), oldArray.map(item => item.id));

      if (!_.isEmpty(diff)) {
        return `Added ${this.parseDifferenceValues(newArray, diff, attribute)}`;
      }

      return '';
    },

    parseDifferenceValues (array, diff, attribute) {
      return `${this.parseAttributeLabel(attribute)} ${array.filter(item => _.indexOf(diff, item.id) > -1)
        .map(item => `<b>${item.name} (#${item.id})</b>`).join(', ')}`;
    },

    parseAttributeLabel (attribute) {
      switch (attribute) {
        default:
          return attribute;
      }
    },

    parseAttributeValue (value, attribute) {
      switch (attribute) {
        case 'users':
          return this.parseUsers(value);
        case 'role':
          return this.parseRoleLabel(value);
        default:
          return value;
      }
    },

    parseUsers (users) {
      return users.map(user => {
        return `${user.name} (#${user.id})`;
      }).join(', ');
    },

    parseRoleLabel (role) {      
      switch (role) {
        case 'admin':
          return 'Administrator';
        default:
          return _.upperFirst(role);
      }
    }
  },
  watch: {
    activities (newActivities) {
      this.parsedActivity = this.activities.map(activity => {
        return this.parseActivity(activity);
      });
    }
  }
};

export default parsesActivity;