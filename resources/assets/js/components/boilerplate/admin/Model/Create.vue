<template>
  <main>
    <t-header title="Create Model" icon="plus"></t-header>
    <model-form></model-form>
  </main>
</template>

<script>
import THeader from 'Components/Header';
import ModelForm from 'Admin/Models/Form';

export default {
  components: { ModelForm, THeader }
};
</script>
