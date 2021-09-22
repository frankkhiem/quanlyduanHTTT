<template>
  <div>
    <div class="intro">
      <h1>Vue Gridster</h1>
    </div>
    
    <div class="grid-container">
      <grid></grid>
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
      
    }
  }
}
</script>

<style>
  h1 {
    text-align: center;
    margin-bottom: 50px;
  }
  .grid-container {
    width: 80%;
    margin: 20px auto;
    border: 1px solid #777;
  }
</style>