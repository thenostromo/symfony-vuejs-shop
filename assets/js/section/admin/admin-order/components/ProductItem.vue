<template>
  <div class="row mb-1">
    <div class="col-md-1 text-center">
      {{ rowNumber }}
    </div>
    <div class="col-md-1">
      {{ category }}
    </div>
    <div class="col-md-3">
      {{ product.product.title }}
    </div>
    <div class="col-md-2">
      {{ product.quantity }}
    </div>
    <div class="col-md-2">
      {{ product.pricePerOne }}$
    </div>
    <div class="col-md-3">
      <button
          @click="viewDetails"
          class="btn btn-outline-info"
      >
        Details
      </button>
      <button
          @click="remove"
          class="btn btn-outline-success"
      >
        Remove
      </button>
    </div>
  </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

const axios = require('axios');

export default {
  name: "ProductItem",
  props: {
    product: {
      type: Object
    },
    index: {
      type: Number
    }
  },
  computed: {
    ...mapGetters('products', ['categories', 'urlProductView']),
    rowNumber() {
      return this.index + 1
    },
    category() {
      const $this = this
      let category = null
      this.categories.forEach((item, index) => {
        if (item.id === $this.product.category.id) {
          category = item.title
        }
      })
      return category
    }
  },
  methods: {
    ...mapActions('products', ['removeProduct', 'getProductsByOrder']),
    viewDetails(event) {
      event.preventDefault();

      const url = window.location.protocol + "//" + window.location.host + this.urlProductView + '/' + this.product.product.id;
      window.open(url, '_blank').focus();
    },
    remove(event) {
      event.preventDefault()
      this.removeProduct(this.product.id)
    }
  }
};
</script>

