<template>
  <main>
    <t-header :title="title" icon="pencil"></t-header>
    <model-form
      :editing="true"
      :editing-model="retrievedModel"
    ></model-form>
  </main>
</template>

<script>
import { storeMixins } from '@/store';
import THeader from 'Components/Header';
import ModelForm from 'Admin/Models/Form';

import retrievesModel from 'Mixins/retrieves/model';

export default {
  components: { ModelForm, THeader },
  mixins: [ storeMixins, retrievesModel ],
  computed: {
    title () {
      let title = 'Editing Model #' + this.$route.params.id;
      if (!this.retrievedModel) {
        return title;
      }

      return `${title} (${this.retrievedModel.name})`;
    }
  },
  mounted () {
    this.load();
    this.retrieveModel(this.$route.params.id)
      .then(() => {
        this.unload();
      })
  }
};
</script>
