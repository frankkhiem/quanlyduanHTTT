<template>
  <div class="overlay" @click="closeModal">
    <div class="modal-container" @click.stop>
      <font-awesome-icon class="close-modal" :icon="['fas', 'times']" @click="closeModal"/>
      <h2>Setting grid item: {{ gridItem.name }}</h2>
      <div class="modal-content">
        <div class="option">
          <label for="name-input" class="key">
            Item name:
          </label>
          <input type="text" id="name-input" class="value" v-model="gridItem.name">
        </div>
        <div class="option">
          <label for="" class="key">
            Item size:
          </label>
          <input 
            type="number" 
            id="cols-input" 
            class="value"
            v-model.number="gridItem.w" 
            :disabled="!gridItem.isResizable" 
            placeholder="Columns" 
            min="1"
            @blur="checkRequireValue">
          <span class="multiplication">x</span>
          <input 
            type="number" 
            id="rows-input" 
            class="value" 
            v-model.number="gridItem.h" 
            :disabled="!gridItem.isResizable" 
            placeholder="Rows" 
            min="1"
            @blur="checkRequireValue">
        </div>
        <div class="option">
          <label for="drag-mode" class="key">
            Item is draggable:
          </label>
          <input type="checkbox" v-model="gridItem.isDraggable">
        </div>
        <div class="option">
          <label for="resize-mode" class="key">
            Item is resizable:
          </label>
          <input type="checkbox" v-model="gridItem.isResizable">
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    gridItem: Object
  },

  data() {
    return {

    }
  },

  methods: {
    closeModal() {
      this.$emit('close-modal');
    },

    checkRequireValue() {
      if (this.gridItem.w == '') {
          this.gridItem.w = 1;
      }
      if (this.gridItem.h == '') {
        this.gridItem.h = 1;
      }
    }
  },
}
</script>

<style scoped>
  .overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.1);
  }

  .modal-container {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 350px;
    background-color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .modal-container .close-modal {
    position: absolute;
    top: 20px;
    right: 100px;
    font-size: 1.8rem;
    cursor: pointer;
  }

  .modal-container h2 {
    margin: 0 0 2rem;
  }

  .option {
    margin-bottom: 15px;
  }

  .option .key {
    min-width: 140px;
    display: inline-block;
  }

  .option .value {
    border: 1px solid #aaa;
    border-radius: 4px;
    outline: none;
    padding: 0 10px;
    box-sizing: border-box;
    text-align: center;
    height: 35px;
  }

  .option .value:focus {
    border-color: #75b7ff;
    box-shadow: 0 0 0 0.2rem #007bff1c;
  }

  .option #name-input {
    width: 280px;
    font-size: 1rem;
  }

  .option #cols-input,
  .option #rows-input {
    width: 100px;
    font-size: 1rem;
  }

  .option .multiplication {
    margin: 0 20px;
  }

  select#drag-mode, 
  select#resize-mode {
    width: 100px;
    font-size: 1rem;
  }

  select option {
    text-align: center;
    line-height: 1.4;
    font-size: 14px;
  }

</style>