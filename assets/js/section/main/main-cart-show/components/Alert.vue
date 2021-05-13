<template>
  <div v-if="alert" :class="getAlertClass(alert.type)">
    {{ alert.message }}
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "Alert",
  computed: {
    ...mapState("cart", ["alert"])
  },
  methods: {
    getAlertClass(type) {
      return "alert alert-" + type;
    }
  }
};
</script>
