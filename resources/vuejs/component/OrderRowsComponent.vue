<template>
  <div>
    <form method="POST" action="/orders/order/order_items/update" id="orderitems_form">
      <input type="hidden" name="order_id" :value="order_id " />
      <input type="hidden" name="shop_id" :value="shop_id" />
      <input type="hidden" name="_METHOD" value="PUT" />
      <div class="row equal">
        <div class="col-xl-9 col-lg-8 col-md-12 d-flex">
          <div class="card card-block">
            <div class="card-body">
              <h4 class="header-title mb-3">ITEMS VAN BESTELLING</h4>
              <div class="table-responsive">
                <table class="table mb-0 order_items_vue font-12">
                  <thead class="thead-light">
                    <tr>
                      <th style="width: 90px;">Aantal</th>
                      <th>Sku</th>
                      <th>Naam</th>
                      <th style="width: 150px;">Kleur</th>
                      <th style="width: 150px;">Maat</th>
                      <th style="width: 130px;">Prijs</th>
                      <th style="width: 130px;">Totaal</th>
                      <th style="width: 30px;"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(row, index) in rows" class="order-items-list">
                      <td>
                        <input
                          type="number"
                          v-model="row.count"
                          name="count[]"
                          :id="'count-' + index"
                          class="form-control form-control-sm count-item requiredItem"
                        />
                      </td>
                      <td :id="'url-' + index" v-if="row.order_item_id != 0">
                        <a v-if="row.product_id != 99999999" :href="row.url" target="_blank" class="mt-1 d-block font-12 hover-popup disbaled"
                          >{{ row.sku }} <img :id="'image-' + index" v-if="row.img != 0" :src="row.img"
                        /></a>
                        <span v-else disabled class="hover-popup mt-1 d-block font-12 disbaled">{{ row.sku }} </span>
                      </td>
                      <td :id="'url-' + index" v-if="row.order_item_id == 0">
                        <select
                          name="product_sku[]"
                          class="form-control form-control-sm sku-item requiredItem"
                          :id="'sku-' + index"
                          v-if="products.length > 0"
                          v-on:change="setValue(index, $event)"
                        >
                          <option value="" selected></option>
                          <option v-for="product in products" :value="product.id">{{ product.sku }}</option>
                        </select>
                      </td>
                      <td>
                        <input
                          type="text"
                          placeholder=""
                          name="product_name[]"
                          :id="'product-name-' + index"
                          v-model="row.query"
                          v-on:keyup="autoComplete(index)"
                          class="form-control form-control-sm requiredItem"
                          autocomplete="off"
                          disabled="disabled"
                        />
                        <div class="list-group">
                          <div v-for="result in row.results" v-on:click="setValue(index, $event)" class="list-group-item" v-bind:data-value="result.id">
                            {{ result.name }}
                          </div>
                        </div>
                      </td>
                      <td>
                        <select
                          name="product_color[]"
                          class="form-control form-control-sm product-color-item"
                          :id="'color-' + index"
                          v-if="row.colors.length > 0"
                        >
                          <option :value="1" selected>----</option>
                          <option v-for="color in row.colors" :value="color.id">{{ color.name }}</option>
                        </select>
                        <select class="form-control form-control-sm" :id="'color-' + index" disabled v-else>
                          <option :value="row.color_id" selected="selected">{{ row.color }}</option>
                        </select>
                        <input type="hidden" :value="row.color_id" name="product_color[]" v-if="row.colors.length == 0" />
                      </td>

                      <td>
                        <select name="product_size[]" class="form-control form-control-sm product-size-item" :id="'size-' + index" v-if="row.sizes.length > 0">
                          <option :value="1" selected>----</option>
                          <option v-for="size in row.sizes" :value="size.id">{{ size.name }}</option>
                        </select>
                        <select class="form-control form-control-sm" :id="'size-' + index" disabled v-else>
                          <option :value="row.size_id" selected="selected">{{ row.size }}</option>
                        </select>
                        <input type="hidden" :value="row.size_id" name="product_size[]" v-if="row.sizes.length == 0" />
                      </td>

                      <td class="pl-3 pr-4">
                        <i class="fa fa-euro fa-euro-1"></i>
                        <input
                          type="text"
                          v-model="row.price"
                          name="product_price[]"
                          :id="'price-' + index"
                          class="form-control form-control-sm"
                          v-if="row.order_item_id == 0"
                        />
                        <input
                          type="text"
                          v-model="row.price"
                          name="product_price[]"
                          :id="'price-' + index"
                          class="form-control form-control-sm disbaled"
                          readonly
                          v-if="row.order_item_id != 0"
                        />
                      </td>
                      <td>
                        <i class="fa fa-euro"></i
                        ><input class="form-control form-control-sm  " name="total[]" :value="row.price * row.count" readonly disabled />
                      </td>
                      <td>
                        <span class="text-danger font-20 hand"  v-on:click.stop.prevent="removeElement(index)"><i class="dripicons-minus"></i></span>
                        <input type="hidden" :value="row.order_item_id" name="order_item_id[]" />
                        <input type="hidden" :value="row.product_id" name="product_ids[]" />
                        <input type="hidden" :value="row.artikel" name="product_artikel[]" />
                        <input type="hidden" :value="row.combo_check" name="combo_check[]" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="text-center ">
                <a class="text-success font-24 hand" v-on:click="addRow"><i class="dripicons-plus"></i></a>
              </div>
              <div class="text-center mt-3">
                <button type="submit" class="btn btn-sm btn-info btn-block">items bijwerken</button>
              </div>
            </div>
          </div>
        </div>
        <!-- end col -->

        <div class="col-xl-3 col-lg-4 col-md-12 d-flex">
          <div class="card card-block">
            <div class="card-body">
              <h4 class="header-title mb-3">Overzicht van de bestelling</h4>
              <div class="table-responsive">
                <table class="table mb-0">
                  <thead class="thead-light">
                    <tr>
                      <th>Omschrijving</th>
                      <th>Price</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Netto :</td>
                      <td>€ {{ total_ex }}</td>
                    </tr>
                    <tr>
                      <td>Verzendkosten :</td>
                      <td>€ {{ shipping_cost }}</td>
                    </tr>
                    <tr>
                      <td>Geschatte belasting :</td>
                      <td>€ {{ vat }}</td>
                    </tr>
                    <tr>
                      <th>Totaal :</th>
                      <th>&euro; {{ total }}</th>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- end table-responsive -->
            </div>
          </div>
        </div>
        <!-- end col -->
      </div>
      <!-- end row -->
    </form>
  </div>
</template>
<script>
export default {
  delimiters: ['{{', '}'],
  props: ['rows', 'products', 'shop_id','image_path','url','order_id'],
  data(){
    return {
        price:[],
    }
  },
  methods: {
      addRow: function () {
          this.rows.push({
              order_item_id: 0,
              count: 1,
              product_id: "",
              product_name: "",
              price: "",
              discount:0,
              url: "",
              query: "",
              colors: "",
              sizes: "",
              combo_check: "0",
              img: "0",
              results: [],
              val: "",
          });
      },
      removeElement: function (index) {
          if (this.rows[index].order_item_id != 0) {
              if(confirm("Weet je zeker dat?")){
                var _this = this;
                axios.post(this.url, {
                    id: this.rows[index].order_item_id,
                })
                    .then(function (response, check) {
                        if (response.data.status == 'true') {
                            toastr.success(response.data.msg);
                            _this.rows.splice(index, 1);
                        } else {
                            console.log(response);
                            toastr.warning(response.data.msg);
                        }
                    })
                    .catch(function (error) {
                        toastr.warning(error);
                    });
              }
            } else {
                this.rows.splice(index, 1);
            }
      },
      setValue: function (index, event) {
          var _this=this;
          var id = event.target.value;
          this.rows[index].order_item_id = 0;
          this.rows[index].val = id;
          this.rows[index].colors = [];
          this.rows[index].sizes = [];
          this.product = [];
          this.price = [];
          axios.get('/products/product/byid/find/' + this.rows[index].val).then((response) =>
          {
              var product = response.data.product;
              _this.rows[index].colors = product.colors;
              _this.rows[index].sizes = product.sizes;
              _this.rows[index].count = 1;
              _this.rows[index].product_id = product.id;
              _this.rows[index].product_name = product.contents.title;
              _this.rows[index].query = product.contents.title;
              _this.rows[index].sku = product.sku;
              if (product.prices.price) {
                  _this.rows[index].price =  accounting.toFixed(product.prices.price[_this.shop_id].price_was - product.prices.price[_this.shop_id].discount,2);
              } else {
                  _this.rows[index].price = 0;
              }
              _this.rows[index].url = "https://www.123bestdeal.nl/" + product.id;
              //_this.rows[index].query=product.name;
              // _this.rows[index].val=product.name;
              _this.rows[index].img = _this.image_path+"/product.image.url";
              _this.$emit('row-updated',_this.rows);
          },
          (error) => {
                app_notes.errors = error.response.data;
                console.log(app_notes.errors);
            }
        );
      }
  },
  computed: {
      total: function () {
          var t = 0;
          $.each(this.rows, function (i, e) {
            t += (e.price-e.discount) * e.count;
          });
          t=accounting.toFixed(t,2);
          return t;
      },
      total_ex: function () {
          var t = 0;
          $.each(this.rows, function (i, e) {
              if(e.product_id!=99999999){
                t += (e.price-e.discount) * e.count;
              }
          });
          t=accounting.toFixed(t,2);
          var tmp=t-((t / 121) * 100);
          tmp=accounting.toFixed(tmp,2);
          var netto=t-tmp;
          netto = accounting.toFixed(netto,2);
          return netto;
      },
      shipping_cost : function () {
          var t = 0;
          $.each(this.rows, function (i, e) {
              if(e.product_id==99999999){
                t += (e.price-e.discount) * e.count;
              }
          });
          t=accounting.toFixed(t,2);
          return t;
      },
      vat: function () {
          var t = 0;
          $.each(this.rows, function (i, e) {
              if(e.product_id!=99999999){
                t += (e.price-e.discount) * e.count;
              }
          });
          t=accounting.toFixed(t,2);
          var tmp=t-((t / 121) * 100);
          tmp=accounting.toFixed(tmp,2);
          return tmp;
      },
  },
  filters: {
      currencydisplay: function (value) {
          if (!value) return ''
          return accounting.toFixed(value,2);
      }
  }
}

</script>

