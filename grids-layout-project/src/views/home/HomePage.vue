<template>
  <div>
    <div class="intro">
      <h1>Vue Gridster</h1>
    </div>
    <div class="action-btn">
      <button class="add-item" @click="addItem">
        Add Item
      </button>
      <button class="clear-all" @click="clearAll">
        Clear All
      </button><button class="save" @click="saveLayout">
        Save Layout
      </button>
    </div>
    <div class="grid-container">
      <grid :layout="layout" @remove-item="removeItem"></grid>
    </div>
  </div>
</template>
<script>
import Grid from '@/components/grid-layout/Grid.vue';

export default {
  components: {
    Grid,
  },

  data() {
    return {
      layout: [],
      index: 0,
    }
  },

  created() {
    let data = sessionStorage.getItem('layout');
    this.layout = JSON.parse(data) || [];
    console.log(this.layout);
  },
  
  mounted() {
    this.index = this.layout.length;
  },

  methods: {
    addItem() {
      let count = this.layout.length;
      this.layout.push({
        x: count * 2 % 12,
        y: count + 12,
        w: 2,
        h: 2,
        i: ++this.index,
        name: `Item ${this.index}`
        }
      );
    },
    clearAll() {
      this.layout = [];
    },
    removeItem(itemId) {
      const indexRemove = this.layout.map(item => item.i).indexOf(itemId);
      this.layout.splice(indexRemove, 1);
    },
    async saveLayout() {
      let data = JSON.stringify(this.layout);
      console.log(data);
      sessionStorage.setItem('layout', data);
      alert('Saved layout')
    }
  },
}
</script>

<style>
  h1 {
    text-align: center;
    margin-top: 50px;
    margin-bottom: 20px;
  }

  .action-btn {
    text-align: center;
    margin-bottom: 50px;

  }

  .save,
  .clear-all,
  .add-item {
    padding: 10px 25px;
    font-size: 1rem;
    color: #fff;
    border: none;
    border-radius: 4px;
    margin-right: 20px;
    cursor: pointer;
  }

  .add-item {
    background-color: #2477bd;
  }

  .clear-all {
    background-color: #d28536;
  }

  .save {
    background-color: grey;
  }

  .grid-container {
    width: 80%;
    padding: 10px;
    margin: 20px auto;
    border: 1px solid #777;
  }
</style>