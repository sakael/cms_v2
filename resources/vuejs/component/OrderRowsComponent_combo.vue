<template>
  <div>
    <table class="table order_items_vue">
        <thead>
        <tr>
            <th style="width: 100px;">Aantal</th>
            <th>Sku</th>
            <th>Naam</th>
            <th style="width: 150px;">Attribuut</th>
            <th style="width: 130px;">Prijs</th>
            <th style="width: 130px;">Total</th>
            <th style="width: 30px;"></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(row, index) in rows" class="order-items-list">
            <td v-if="row.combocheck == 0">
                <input type="number" v-model="row.count" name="count[]" :id="'count-'+index" class="form-control form-control-sm count-item requiredItem" >
                <input type="hidden" name="combocheck[]" :value="row.combocheck">
            </td>
            <td v-else>
                <input type="hidden" name="count[]" value="1" :id="'count-'+index" class="form-control"><i class="fa fa-long-arrow-right requiredItem"></i>Combo
                <input type="hidden" name="combocheck[]" :value="row.combocheck">
            </td>
            <td :id="'url-'+index" v-if="row.new !=0">
                <a :href="row.url" target="_blank" class="hover-popup form-control form-control-sm disbaled">{{row.sku}}
                    <img :id="'image-'+index" v-if="row.img != 0" :src="row.img"></a>
            </td>
            <td :id="'url-'+index" v-if="row.new ==0">
                <select name="product_sku[]" class="form-control form-control-sm sku-item requiredItem" :id="'sku-'+index" v-if="products.length > 0" v-on:change="setValue(index, $event)" >
                  <option value="" selected></option>
                  <option v-for="product in products" :value="product.id">{{product.sku}}</option>
                </select>
            </td>
            <td>
                <input type="text" :readonly="row.combocheck != 0 ? true : false" placeholder="" name="product_name[]" :id="'product-name-'+index" v-model="row.query" v-on:keyup="autoComplete(index)" class="form-control form-control-sm requiredItem" autocomplete="off" disabled="disabled" >
                <div class="list-group">
                    <div v-for="result in row.results" v-on:click="setValue(index, $event)" class="list-group-item" v-bind:data-value="result.id">{{result.name}}</div>
                </div>
            </td>
            <td v-if="row.combocheck == 0">
                <select name="product_color[]" class="asdasdasd form-control form-control-sm product-color-item" :id="'color-'+index" v-if="row.colors.length > 0" >
                    <option :value="1" selected >----</option>
                    <option v-for="color in row.colors" :value="color.id" v-if="color.active==1" selected>{{color.name}}</option>
                </select>
                <select class="form-control form-control-sm" :id="'color-'+index" disabled v-else>
                    <option :value="row.color_id" selected="selected">{{row.color}}</option>
                </select>
                <input type="hidden" :value="row.color_id" name="product_color[]" v-if="row.colors.length == 0">
            </td>

            <td class="pl-3 pr-4">
              <i class="fa fa-euro fa-euro-1"></i><input type="text" v-model="row.price-row.discount" name="product_price[]" :readonly="row.combocheck != 0 ? true : false" :id="'price-'+index" class="form-control form-control-sm" >
            </td>
            <td>
              <i class="fa fa-euro"></i><input class="form-control form-control-sm  " name="total[]" :value="(row.price - row.discount) * row.count" readonly disabled/>
            </td>
            <td>
                <input type="button" value="-" class="form-control border-0 btn-danger btn-remove-order-row" name="remove-btn[]" v-on:click.stop.prevent="removeElement(index);">
                <input type="hidden" :value="row.combo_id" name="combo_id[]">
                <input type="hidden" :value="row.order_item_id" name="order_item_id[]">
                <input type="hidden" :value="row.product_id" name="product_ids[]">
                <input type="hidden" :value="row.artikel" name="product_artikel[]">
            </td>
        </tr>
        <tr>
            <td colspan="8" align="center" >
              <input type="button" name="add-btn" style="max-width:300px" value="+" class="form-control border-0  btn-info" v-on:click="addRow">
            </td>
        </tr>
        <tr>
            <td colspan="7" class="text-right pr-3"><h4>
                    Totaal : {{total}}
                </h4></td>
            <td colspan="1"></td>
        </tr>
        </tbody>
    </table>
  </div>
</template>
<script>
export default {
  delimiters: ['{{', '}'],
  props: ['rows', 'products', 'shop_id','image_path','url'],
  data(){
    return {
        price:[],
    }
  },
  methods: {
      addRow: function () {
          this.rows.push({
              order_item_id: "",
              count: 1,
              product_id: "",
              product_name: "",
              price: "",
              discount:0,
              url: "",
              query: "",
              colors: "",
              combo_id: "0",
              img: "0",
              results: [],
              val: "",
              combocheck: "0",
              new: "0",
          });
      },
      removeElement: function (index) {
          if (this.rows[index].new == 1) {
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
          } else {
              this.rows.splice(index, 1);
          }

      },
      setValue: function (index, event) {
          var _this=this;
          var id = event.target.value;
          this.rows[index].order_item_id = '';
          this.rows[index].val = id;
          this.rows[index].colors = [];
          this.product = [];
          this.price = [];
          axios.get('/products/product/byid/find/' + this.rows[index].val).then((response) =>
          {
              var product = response.data.product;
              _this.rows[index].colors = product.colors;
              _this.rows[index].count = 1;
              _this.rows[index].product_id = product.id;
              _this.rows[index].product_name = product.contents.page_title;
              _this.rows[index].query = product.contents.page_title;
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
              _this.rows[index].combocheck = '0';
              _this.rows[index].new = 0;
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
          if($(".total-amount").length){
           $(".total-amount").html(''+t);
          }
          if($(".total-amount-ex").length){
            var tmp=t-((t / 121) * 100);
            tmp=accounting.toFixed(tmp,2);
            var netto=t-tmp;
           $(".total-amount-ex").html(accounting.toFixed(netto,2));
          }
          return t;
      }
  },
  filters: {
      currencydisplay: function (value) {
          if (!value) return ''
          return accounting.toFixed(value,2);
      }
  }
}

</script>