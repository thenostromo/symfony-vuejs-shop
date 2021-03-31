<template>
  <div>
    <div class="lds-dual-ring" v-if="isLoading"></div>
    <div class="text-center" v-else>
      <div class="container mt-3 mb-2">
        <Header/>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <ProductList/>

            <Navigation/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import Navigation from "./components/Navigation";
import ProductList from "./components/ProductList";
import Header from "./components/Header";

export default {
  components: {Header, ProductList, Navigation},
  computed: {
    ...mapState('category', ['isLoading'])
  },
  mounted() {
    this.getCountOfProductsByCategory()
    this.getProductsByCategory()
  },
  methods: {
    ...mapActions('category', ['getCountOfProductsByCategory', 'getProductsByCategory'])
  }
};
</script>
