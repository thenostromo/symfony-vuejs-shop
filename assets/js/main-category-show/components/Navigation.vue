<template>
  <nav>
    <ul class="pagination align-items-center justify-content-center">
      <li class="page-item">
        <a
            class="btn-nav btn-nav-prev"
            href="#"
            v-if="currentPage < countOfPages"
            @click="prevPage"
        >
          <i class="fas fa-angle-left"></i> Prev
        </a>
      </li>
      <li
          v-for="page in countOfPages"
          :class="getClassOfPageBlock(page)"
      >
        <a
          class="btn-nav"
          href="#"
          @click="setPage(page)"
        >
          {{ page }}
        </a>
      </li>
      <li class="page-item-total">...</li>
      <li class="page-item">
        <a
            class="btn-nav btn-nav-next"
            href="#"
            v-if="currentPage < countOfPages"
            @click="nextPage"
        >
          Next <i class="fas fa-angle-right"></i>
        </a>
      </li>
    </ul>
  </nav>
</template>

<script>
import {mapActions, mapGetters, mapState, mapMutations} from "vuex";

export default {
  computed: {
    ...mapState('category', ['offset', 'limit', 'countOfProducts']),
    ...mapGetters('category', ['currentCount', 'currentPage']),
    countOfPages() {
      return Math.floor(this.countOfProducts / this.limit)
    }
  },
  methods: {
    ...mapActions('category', ['getProductsByCategory']),
    ...mapMutations('category', ['setOffset']),
    getClassOfPageBlock(page) {
      return page === this.currentPage ? "page-item active" : "page-item"
    },
    setPage(page) {
      const newOffset = (page - 1) * this.limit
      this.setOffset(newOffset)
      this.getProductsByCategory()
    },
    prevPage() {
      const newOffset = this.offset - this.limit
      this.setOffset(newOffset)
      this.getProductsByCategory()
    },
    nextPage() {
      const newOffset = this.offset + this.limit
      this.setOffset(newOffset)
      this.getProductsByCategory()
    }
  }
};
</script>
