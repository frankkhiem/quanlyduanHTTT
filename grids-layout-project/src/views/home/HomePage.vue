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
    <div class="num-cols">
      <p>
        Number of columns per row:
        <br>
        (Press Enter to confirm)
      </p>
      <input type="number" min="1" v-model="userInputColNum" @keyup.enter="changeColNum" @blur="resetColNum">
    </div>
    <div class="grid-container">
      <grid 
        :layout="layout" 
        :col-num="colNum" 
        @remove-item="removeItem" 
        @show-setting-modal="showItemSettingModal"
      ></grid>
    </div>

    <!-- modal cai dat cau hinh grid item -->
    <setting-item-modal 
      v-if="showSettingModal"  
      @close-modal="showSettingModal = false"
      :grid-item="modalEditData"
    ></setting-item-modal>
  </div>
</template>
<script>
import Grid from '@/components/grid-layout/Grid.vue';
import SettingItemModal from '@/components/grid-layout/SettingGridItemModal.vue';

export default {
  components: {
    Grid,
    SettingItemModal
  },

  data() {
    return {
      layout: [],
      index: 0,
      colNum: 12,
      userInputColNum: 12,
      showSettingModal: false,
      modalEditData: {}
    }
  },

  created() {
    let data = sessionStorage.getItem('layout');
    this.layout = JSON.parse(data) || [];
  },

  mounted() {
    this.layout.at(-1) ? this.index = this.layout.at(-1).i + 1 : this.index = 0;
    // console.log(this.index);
  },

  methods: {
    addItem() {
      let count = this.layout.length;
      this.layout.push({
        x: count * 2 % this.colNum,
        y: count + this.colNum,
        w: 2,
        h: 2,
        i: this.index++,
        name: `Item ${this.index}`,
        isDraggable: true,
        isResizable: true,
      });
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
    },
    resetColNum() {
      this.userInputColNum = this.colNum;
    },
    changeColNum() {
      this.colNum = parseInt(this.userInputColNum);
      alert(`You changed the number of columns per row to ${this.colNum}`)
    },

    showItemSettingModal(item) {
      this.modalEditData = item
      this.showSettingModal = true;
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
    margin-bottom: 10px;

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

  .num-cols {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .num-cols p {
    font-weight: 600;
    margin-right: 20px;
    text-align: center;
  }

  .num-cols input {
    width: 100px;
    height: 35px;
    line-height: 30px;
    font-size: 20px;
    border: 1px solid #aaa;
    border-radius: 4px;
    outline: none;
    box-sizing: border-box;
    text-align: center;
  }

  .num-cols input:focus-visible {
    border: 2px solid #333;
    box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
  }

  input[type=number]::-webkit-inner-spin-button,
  input[type=number]::-webkit-outer-spin-button
  {
    -webkit-appearance: none;
    margin: 0;
  }

  .grid-container {
    width: 80%;
    padding: 10px;
    margin: 50px auto;
    border: 1px solid #777;
  }
</style>