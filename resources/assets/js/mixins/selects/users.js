import retrievesUsers from 'Mixins/retrieves/users';

const selectsUsers = {
  data () {
    return {
      selectedUsers: []
    }
  },
  mixins: [ retrievesUsers ],
  computed: {
    usersStatus () {
      return this.getErrors('users');
    },
    selectedUserIds () {
      return this.selectedUsers.map((user) => {
        return user.id;
      });
    }
  },
  mounted () {
    this.retrieveUsers();
  },
  methods: {
    updateSelectedUsers (users) {
      this.selectedUsers = users;
    }
  }
};

export default selectsUsers;
