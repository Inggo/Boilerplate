<template>
  <main>
    <t-header title="Create User" icon="account-plus"></t-header>
    <user-form></user-form>
  </main>
</template>

<script>
import THeader from 'Components/Header';
import UserForm from 'Admin/Users/Form';

export default {
  components: { UserForm, THeader }
};
</script>
