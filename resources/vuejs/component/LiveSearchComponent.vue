<template>
  <div>
    <form>
      <div class="input-group">
        <input
          type="text"
          class="form-control dropdown-toggle"
          placeholder="Zoeken..."
          id="top-search"
          v-on:keyup="autoComplete"
          v-model="search"
          autocomplete="off"
        />
        <span class="mdi mdi-magnify search-icon"></span>
      </div>
    </form>

    <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0" id="search-dropdown">
      <!-- item-->
      <div class="dropdown-header noti-title" v-if="results.products && results.orders">
        <h5 class="text-overflow mb-0 ">
          Gevonden
          <span class="text-danger">{{ results.products.length + results.orders.length }}</span>
          resultaten
        </h5>
      </div>
      <div class="dropdown-header noti-title" v-else>
        <h5 class="text-overflow mb-0 ">
          Gevonden
          <span class="text-danger">0</span>
          resultaten
        </h5>
      </div>
      <!-- item-->
      <div class="dropdown-header noti-title pb-0 mt-1" v-if="results.products !='' && (results.products)">
        <h6 class="text-overflow text-uppercase font-bold">Artikelen</h6>
      </div>
      <!-- item-->
      <div v-for="row in results.products" class="product">
        <a class="dropdown-item notify-item font-12" :href="row.id | producturl(row.id)">
          <i class="uil-notes font-16 mr-1"></i>
          <span><strong>{{row.sku}}-{{row.id}}</strong> {{ row.title }}</span>
        </a>
      </div>
      <!-- item-->
      <div class="dropdown-header noti-title pb-0 mt-1" v-if="results.orders !='' && (results.orders)">
        <h6 class="text-overflow text-uppercase">Orders</h6>
      </div>
      <!-- item-->
      <div v-for="row in results.orders" class="order">
        <a :href="row.id | orderurl(row.id)" class="dropdown-item notify-item font-12">
          <i class="uil-shopping-trolley font-16 mr-1"></i>
          <span>{{ row.id }} - {{ row.firstname }} {{ row.lastname }} ({{ row.title }})</span>
        </a>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      search: "",
      results: [],
    };
  },
  methods: {
    autoComplete: function() {
      if (this.search.length > 1) {
        axios
          .get("/general/livesearch-main", {
            params: {
              search: this.search,
            },
          })
          .then(
            (response) => {
              console.log(response);
              if (response.status == 200) {
                this.results = response.data;
              }
            },
            (error) => {
              this.errors = error.response.data;
              console.log(this.errors);
            }
          );
      } else this.results = [];
    },
  },
  filters: {
    producturl: function(value) {
      if (!value) return "";
      var url = "/product/" + value;
      return url;
    },
    orderurl: function(value) {
      if (!value) return "";
      var url = "/orders/order/" + value;
      return url;
    },
  },
};
$(document).ready(function() {
  // Serach Toggle
  $("#top-search").on("click", function(e) {
    e.preventDefault();
    var navDropdowns = $('.navbar-custom .dropdown:not(.app-search)');
    navDropdowns.children(".dropdown-menu.show").removeClass("show");
    $("#search-dropdown").addClass("d-block");
    return false;
  });
});
</script>
