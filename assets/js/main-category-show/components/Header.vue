<template>
  <div class="row">
    <div class="col-lg-12 mb-2" style="display: flex; align-items: center; justify-content: center; text-align: center">
      <div class="products-count">
        Showing <span>{{ currentCount }} of {{ countOfProducts }}</span> Products
      </div>
      <div class="page-title2">
        <a class="page-title">{{ category.title }}</a>
      </div>
      <div class="products-sort">
        <label for="sortby" style="margin: 0 .5rem 0 0;">Sort by:</label>
        <select name="sortby" id="sortby" class="form-control" style="width: 200px;">
          <option value="popularity" selected="selected">Most Popular</option>
          <option value="rating">Most Rated</option>
          <option value="date">Date</option>
        </select>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters, mapState} from "vuex";

export default {
  computed: {
    ...mapState('category', ['countOfProducts']),
    ...mapGetters('category', ['category', 'currentCount']),
  }
};
</script>
