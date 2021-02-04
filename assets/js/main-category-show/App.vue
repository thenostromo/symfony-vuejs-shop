<template>
  <div class="table-additional-selection">
    <div class="text-center">
      <div class="container mt-3 mb-2">
        <div class="row">
          <div class="col-lg-12 mb-2" style="display: flex; align-items: center; justify-content: center; text-align: center">
            <div class="products-count">
              Showing <span>9 of 56</span> Products
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
      </div>
    </div>
  </div>
</template>

<script>
import ProductItem from "./components/ProductItem";
import AddProduct from "./components/AddProduct";
import {mapActions, mapGetters, mapState} from "vuex";

export default {
  mounted() {
    console.log(window.staticStore)
  },
  computed: {

  },
  methods: {
    ...mapGetters('category', ['category']),
  }
};
</script>
