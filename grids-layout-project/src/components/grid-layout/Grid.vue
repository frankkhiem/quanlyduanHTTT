<template>
  <div>
    <grid-layout
      :layout.sync="layout"
      :col-num="colNum"
      :row-height="60"
      :is-draggable="true"
      :is-resizable="true"
      :is-mirrored="false"
      :vertical-compact="true"
      :margin="[10, 10]"
      :use-css-transforms="true"
      :prevent-collision="false"
      :responsive="false"
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
        :is-draggable="item.isDraggable"
        :is-resizable="item.isResizable"
        drag-allow-from=".item-name"
        drag-ignore-from=".item-content"
      >
        <div class="item-action">
          <font-awesome-icon class="btn setting-btn" :icon="['fas', 'cog']" @click="settingItem(item)"/>
          <font-awesome-icon class="btn close-btn" :icon="['fas', 'times']" @click="removeItem(item.i)"/>
        </div>
        <div class="item-name">
          {{ item.name }}
        </div>
        <div class="item-content">
          <div class="size">
            [{{ item.w }} x {{ item.h }}]
          </div>
          <div class="mode">
            <span v-if="item.isDraggable == false && item.isResizable == false">(Static)</span>
            <span v-if="item.isDraggable == true && item.isResizable == false">(Not resize)</span>
            <span v-if="item.isDraggable == false && item.isResizable == true">(Not drag)</span>
          </div>
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
    colNum: Number,
  },

  data() {
    return {

    }
  },

  methods: {
    removeItem(itemId) {
      this.$emit('remove-item', itemId);
    },
    settingItem(item) {
      this.$emit('show-setting-modal', item);
    }
  }

}
</script>

<style scoped>
  .grid-item {
    border: 1px solid #aaa;
    position: relative;
    background-image: url('../../assets/images/background.jpg');
    background-size: cover;
    display: flex;
    flex-direction: column;
  }

  .remove {
    float: right;
    margin-right: 5px;
    cursor: pointer;
  }

  .item-action {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 1.2rem;
  }

  .item-action .btn {
    cursor: pointer;
    color: #fff;
    transition: transform 0.3s ease-in;
  }

  .item-action .btn:hover {
    transform: rotate(-90deg);
  }

  .btn.setting-btn {
    margin-right: 10px;
  }

  .item-name {
    text-align: center;
    padding: 6px;
    padding-right: 42px;
    font-weight: 600;
    font-size: 16px;
    border-bottom: 1px solid #aaa;
    background-color: #4eb352;
    color: #fff;
  }

  .item-content {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .item-content .size {
    font-size: 2rem;
    font-weight: 600;
    color: #fff;
    margin-bottom: 10px;
  }

  .item-content .mode {
    color: #fff;
    font-size: 20px;
  }
</style>