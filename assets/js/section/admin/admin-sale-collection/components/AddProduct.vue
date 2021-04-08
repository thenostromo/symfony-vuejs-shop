<template>
  <div class="row mb-2">
    <div class="col-md-3">
      <select
          v-model="category"
          @change="changedSelectedCategory()"
          name="add_product_category_select"
          class="form-control"
      >
        <option value="" disabled>- choose option -</option>
        <option
            v-for="category in categories"
            :value="category.id"
            :key="category.id"
        >
          {{ category.title }}
        </option>
      </select>
    </div>
    <div class="col-md-4" v-if="category">
      <select
          v-model="product"
          name="add_product_product_select"
          class="form-control"
      >
        <option value="" disabled>- choose option -</option>
        <option
            v-for="product in freeCategoryProducts"
            :value="product.id"
            :key="product.id"
        >
          {{ product.title }}
        </option>
      </select>
    </div>
    <div class="col-md-2" v-if="product">
      <input
        type="number"
        class="form-control"
        placeholder="discount amount"
        step="0.01"
        v-model="discountAmount"
      />
    </div>
    <div class="col-md-3">
      <button
          v-if="product"
          @click="viewDetails"
          class="btn btn-outline-info"
      >
        Product Details
      </button>
      <button
          v-if="product"
          @click="add"
          class="btn btn-outline-success"
      >
        Add
      </button>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions, mapMutations } from 'vuex'

export default {
  name: "AddProduct",
  computed: {
    ...mapState('products', ['selectedCategory', 'selectedProduct', 'newProductDiscountAmount']),
    ...mapGetters('products', ['categories', 'freeCategoryProducts', 'urlProductView']),
    category: {
      get() {
        return this.selectedCategory
      },
      set(value) {
        this.setSelectedCategory(value)
      }
    },
    product: {
      get() {
        return this.selectedProduct
      },
      set(value) {
        this.setSelectedProduct(value)
      }
    },
    discountAmount: {
      get() {
        return this.newProductDiscountAmount
      },
      set(value) {
        this.setNewProductDiscountAmount(value)
      }
    }
  },
  methods: {
    ...mapMutations('products', ['setSelectedCategory', 'setSelectedProduct', 'setNewProductDiscountAmount']),
    ...mapActions('products', ['changedSelectedCategory', 'addNewProduct', 'getProductsBySaleCollection']),
    add(event) {
      event.preventDefault()
      this.addNewProduct()
      this.getProductsBySaleCollection()
    },
    viewDetails(event) {
      event.preventDefault();

      const url = window.location.protocol + "//" + window.location.host + this.urlProductView + '/' + this.selectedProduct;
      window.open(url, '_blank').focus();
    },
  }
}
</script>

<style scoped>

</style>
