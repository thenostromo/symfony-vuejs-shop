<template>
  <nav>
    <ul class="pagination align-items-center justify-content-center">
      <li class="page-item">
        <a
          v-if="showPrevPageBtn"
          class="btn-nav btn-nav-prev"
          href="#"
          @click="prevPage"
        >
          <i class="fas fa-angle-left"></i> Prev
        </a>
      </li>
      <li v-for="existPage in pagesCount" :key="existPage" :class="getClassOfPageBlock(existPage)">
        <a class="btn-nav" href="#" @click="changePage(existPage)">
          {{ existPage }}
        </a>
      </li>
      <li class="page-item">
        <a
          v-if="page < pagesCount"
          class="btn-nav btn-nav-next"
          href="#"
          @click="nextPage"
        >
          Next <i class="fas fa-angle-right"></i>
        </a>
      </li>
    </ul>
  </nav>
</template>

<script>
import { mapActions, mapGetters, mapMutations, mapState } from "vuex";

export default {
  computed: {
    ...mapState('category', ['page']),
    ...mapGetters("category", ["pagesCount"]),
    showPrevPageBtn() {
      return this.page !== 1 && (this.page <= this.pagesCount);
    }
  },
  methods: {
    ...mapActions("category", ["getProductsByCategory"]),
    ...mapMutations("category", ["setPage"]),
    getClassOfPageBlock(page) {
      return page === this.page ? "page-item active" : "page-item";
    },
    changePage(newPage) {
      this.setPage(newPage);
      this.getProductsByCategory();
    },
    prevPage() {
      const newPage = this.page - 1;
      this.changePage(newPage);
    },
    nextPage() {
      const newPage = this.page + 1;
      this.changePage(newPage);
    }
  }
};
</script>
