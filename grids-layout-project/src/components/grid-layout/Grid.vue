<template>
  <div>
    <grid-layout
      :layout.sync="layout"
      :col-num="12"
      :row-height="60"
      :is-draggable="true"
      :is-resizable="true"
      :is-mirrored="false"
      :vertical-compact="true"
      :margin="[10, 10]"
      :use-css-transforms="true"
      :prevent-collision="false"
    >

      <grid-item  
        class="grid-item"
        v-for="item in layout"
        :x="item.x"
        :y="item.y"
        :w="item.w"
        :maxW="item.maxW"
        :h="item.h"
        :i="item.i"
        :key="item.i"
      >
        <div class="remove" @click="removeItem(item.i)">X</div>
        <div>
          {{item.name}}
        </div>
      </grid-item>
    </grid-layout>
  </div>
</template>

<script>
import VueGridLayout from 'vue-grid-layout';
export default {
  components: {
    GridLayout: VueGridLayout.GridLayout,
    GridItem: VueGridLayout.GridItem
  },

  props: {
    layout: Array,
  },

  data() {
    return {

    }
  },

  methods: {
    removeItem(itemId) {
      this.$emit('remove-item', itemId);
    }
  }

}
</script>

<style scoped>
  .grid-item {
    border: 1px solid #aaa;
  }

  .remove {
    float: right;
    margin-right: 5px;
    cursor: pointer;
  }
</style>