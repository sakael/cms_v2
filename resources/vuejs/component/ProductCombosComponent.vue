<template>
  <div>
    <table width="100%" class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Product ID</th>
            <th>Combo ID</th>
            <th>Combo Title</th>
            <th>Combo SKU</th>
            <th>Datum</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <draggable v-model="product_combos" tag="tbody" @end="updateItemOrder">
            <tr v-for="(productCombo,index) in product_combos">
                <td>{{ productCombo.product_id }}</td>
                <td>{{ productCombo.id }}</td>
                <td>{{ productCombo.product_title }}</td>
                <td >
                  <select v-model="productCombo.product_id" class="form-control" @change="onChange(index,$event)" :key="productCombo.sort">
                    <option  value="">Please select one</option>
                    <option v-for="(product) in products" :value="product.id">{{product.sku}}</option>
                  </select>

                </td>
                <td><span  class="mt-2">{{ productCombo.created_at}}</span></td>
                <td>
                  <input class="form-control border-0 btn-danger" type="button"  name="remove-btn[]" value="-" v-on:click="remove(index,productCombo.id)">
                </td>
            </tr>
        </draggable>
        <tfoot>
            <tr>
              <td colspan="6">
                <div class="col-md-3 text-center mx-auto">
                  <div class="form-control border-0  btn-info text-center" @click="addRow()">
                    <i class="fa fa-plus" ></i>
                  </div>
                </div>
              </td>
            </tr>
        </tfoot>
        </tbody>
    </table>
    <hr>
    <small>

    </small>
  </div>
</template>
<script>
import draggable from "vuedraggable";
export default {
  props:['product_id','update_url','get_url','lang','delete_url'],
  components: {
    draggable
  },
  data(){
    return {
      errors:[],
      product_combos:[],
      products:[]
    }
  },
  mounted: function () {
    let _this = this;
    axios.get(_this.get_url,{params: {product_id:_this.product_id}})
      .then( (response) => {
        _this.products=response.data.products;
        console.log(response.data);
          $.each( response.data.product_combos, function( key, value, ) {
            _this.product_combos.push({
                id: value.id,
                sort: value.sort_order,
                product_sku: response.data.products[value.combo_product_id].sku,
                product_id: response.data.products[value.combo_product_id].id,
                product_title: response.data.products[value.combo_product_id].contents[_this.lang].page_title,
                created_at: value.created_at,
                new:0
            });
        });
      },(error) => {
        console.log(error);
      });
  },
  methods: {
      addRow() {
          var index=this.product_combos.push({
            id: '',
            product_sku: '',
            product_id: '',
            product_title: '',
            created_at: '',
            sort:'',
            new:1
          });
          this.product_combos[index-1].sort=index;
      },
      updateItemOrder(){
        let _this = this;
        var items = this.product_combos.map(function(item, index) {
                    item.sort=index+1;
                    return item;
                });
                this.update('update');
      },
      onChange(index,event) {
        this.product_combos[index].product_sku= this.products[this.product_combos[index].product_id].sku;
        this.product_combos[index].product_id=this.products[this.product_combos[index].product_id].id;
        this.product_combos[index].product_title=this.products[this.product_combos[index].product_id].contents[this.lang].page_title;
        if(this.product_combos[index].new==0){
          this.update('updateInsert',index);
        }else{
          this.update('insert',index);
        }
       },
       update(type,index=null){
         var _this = this;
         axios.post(this.update_url, {
             product_id: _this.product_id,
             product_combos: _this.product_combos,
             index:index,
             type:type
         }).then( (response) => {
             if(response.data.status=='true'){
               if(type=='insert' && index !=null && (response.data.combo_id)){
                 _this.product_combos[index].new=0;
                 _this.product_combos[index].id=response.data.combo_id
               }else{
                 $.each( _this.product_combos, function( key, value, ) {
                   value.new=0;
                 });
               }

               toastr.success(response.data.msg);
             }
             else{
               toastr.warning(response.data.msg);
             }
           },
           (error) => {
                 _this.errors = error.response.data;
                 console.log(_this.errors);
             }
           );
       },
       remove:function (index,id) {
         if(this.product_combos[index].new==1){
           this.product_combos.splice(index, 1);
         }
         else{
           var _this = this;
           axios.delete(this.delete_url,{ data:{
               'product_id': _this.product_id,
               'id' :_this.product_combos[index].id
           }}).then( (response) => {
               if(response.data.status=='true'){
                 _this.product_combos.splice(index, 1);
                 toastr.success(response.data.msg);
               }
               else{
                 toastr.warning(response.data.msg);
               }
             },
             (error) => {
                   _this.errors = error.response.data;
                   console.log(_this.errors);
               }
             );
         }
       },
  }
}
</script>
