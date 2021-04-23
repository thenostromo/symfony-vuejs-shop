<template>
  <div class="row mb-2">
    <div class="col-md-2">
      <select
          v-model="category"
          @change="getProductsByCategory()"
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
    <div class="col-md-3" v-if="category">
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
          {{ productTitleFormatted(product) }}
        </option>
      </select>
    </div>
    <div class="col-md-2" v-if="product">
      <input
          type="number"
          class="form-control"
          placeholder="quantity"
          v-model="quantity"
          min="0"
          :max="productQuantityMax(product)"
      />
    </div>
    <div class="col-md-2" v-if="product">
      <input
        type="number"
        class="form-control"
        placeholder="price per one"
        step="0.01"
        min="0"
        :max="productPriceMax(product)"
        v-model="pricePerOne"
      />
    </div>
    <div class="col-md-3">
      <button
          v-if="product"
          @click="viewDetails"
          class="btn btn-outline-info"
      >
        Details
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
    ...mapState('products', ['selectedCategory', 'selectedProduct', 'newProductQuantity', 'newProductPricePerOne']),
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
    quantity: {
      get() {
        return this.newProductQuantity
      },
      set(value) {
        this.setNewProductQuantity(value)
      }
    },
    pricePerOne: {
      get() {
        return this.newProductPricePerOne
      },
      set(value) {
        this.setNewProductPricePerOne(value)
      }
    }
  },
  methods: {
    ...mapMutations('products', ['setSelectedCategory', 'setSelectedProduct', 'setNewProductQuantity', 'setNewProductPricePerOne']),
    ...mapActions('products', ['getProductsByCategory', 'addNewProduct', 'getProductsByOrder']),
    add(event) {
      event.preventDefault()
      this.addNewProduct()
      this.getProductsByOrder()
    },
    viewDetails(event) {
      event.preventDefault();

      const url = window.location.protocol + "//" + window.location.host + this.urlProductView + '/' + this.selectedProduct;
      window.open(url, '_blank').focus();
    },
    productTitleFormatted(product) {
      return '#' + product.id +
          ' ' + product.title +
          ' / P: ' + product.price + '$ ' +
          '/ Q: ' + product.quantity
    },
    productPriceMax(productId) {
      const productData = this.freeCategoryProducts.find(product => product.id === productId)
      return parseInt(productData.price)
    },
    productQuantityMax(productId) {
      const productData = this.freeCategoryProducts.find(product => product.id === productId)
      return parseInt(productData.quantity)
    }
  }
}
</script>

<style scoped>

</style>
