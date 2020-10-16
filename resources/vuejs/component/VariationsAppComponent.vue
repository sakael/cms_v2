<template>
  <div>
    <div class="row" v-for="(product_variation,index) in productVariations" :id="'variation-row-'+index">
      <input type="hidden" :value="product_variation.id" name="product_variation_id[]" />
      <div class="col-md-2">
          <div class="form-group">
              <label>Variaties Groep</label>
              <select v-if="product_variation.new==1" class="form-control form-control-sm variations-group" name="variations-group[]" @change="variationsGroupSelected(index, $event)" >
                <option value="" selected>---</option>
                <option  v-for="(item, index)  in variationsGroup" :value="index" :selected="item.id==product_variation.variationGroupId">
                    {{index}}
                </option>
              </select>

              <select v-else disabled class="form-control form-control-sm variations-group" name="variations-group[]" @change="variationsGroupSelected(index, $event)" >
                <option value="" selected>---</option>
                <option  v-for="(item, index)  in variationsGroup" :value="index" :selected="item.id==product_variation.variationGroupId">
                    {{index}}
                </option>
              </select>
          </div>
      </div>
      <div class="col-md-2">
          <div class="form-group" v-if="product_variation.new==1" >
              <label>Variaties</label>
              <select v-if="Object.keys(product_variation.variations).length > 0" class="form-control form-control-sm variations" name="variations[]" @change="variationselected(index, $event)">
                <option selected>
                </option>
                <option  v-for="(item, index)  in product_variation.variations" :value="item.id" :selected="item.id==product_variation.variationId" >{{item.name}}</option>
             </select>
            <select  class="form-control form-control-sm variations" name="variations[]" v-else @change="variationselected(index, $event)">
              <option selected></option>
                <option  v-for="(item, index)  in product_variation.variations" :value="item.id">{{item.name}}</option>
            </select>
          </div>

          <div class="form-group" v-else>
              <label>Variaties</label>
              <select disabled v-if="Object.keys(product_variation.variations).length > 0" class="form-control  form-control-sm variations" name="variations[]" @change="variationselected(index, $event)">
                <option selected>
                </option>
                <option  v-for="(item, index)  in product_variation.variations" :value="item.id" :selected="item.id==product_variation.variationId" >{{item.name}}</option>
             </select>
            <select disabled class="form-control form-control-sm variations" name="variations[]" v-else @change="variationselected(index, $event)">
              <option selected></option>
                <option  v-for="(item, index)  in product_variation.variations" :value="item.id">{{item.name}}</option>
            </select>
          </div>

      </div>
      <div class="col-md-2">
          <div class="form-group">
              <label>Variaties Sub Groep</label>
              <select v-if="product_variation.new==1" class="form-control form-control-sm sub-variations-group" name="sub-variations-group[]" @change="subVariationsGroupSelected(index, $event)" >
                <option value="null" selected>---</option>
                <option  v-for="(item, index)  in variationsGroup" :value="index" :selected="item.id==product_variation.subVariationGroupId">
                    {{index}}
                </option>
              </select>

              <select v-else  disabled class="form-control form-control-sm sub-variations-group" name="sub-variations-group[]" @change="subVariationsGroupSelected(index, $event)" >
                <option value="null" selected>---</option>
                <option  v-for="(item, index)  in variationsGroup" :value="index" :selected="item.id==product_variation.subVariationGroupId">
                    {{index}}
                </option>
              </select>
          </div>
      </div>
      <div class="col-md-2">
          <div class="form-group" v-if="product_variation.new==1">
              <label>Variaties Sub</label>
              <select v-if="Object.keys(product_variation.subVariations).length> 0" class="form-control form-control-sm sub-variations" name="sub-variations[]" @change="subVariationselected(index, $event)">
                <option selected value="null">
                </option>
                <option  v-for="(item, index)  in product_variation.subVariations" :value="item.id" :selected="item.id==product_variation.subVariationId">
                  {{item.name}}
                </option>
             </select>
            <select  class="form-control form-control-sm sub-variations" name="sub-variations[]" v-else @change="subVariationselected(index, $event)">
              <option selected value="null">
              </option>
                <option  v-for="(item, index)  in product_variation.subVariations" :value="item.id">
                  {{item.name}}
                </option>
            </select>
          </div>

          <div class="form-group" v-else>
              <label>Variaties Sub</label>
              <select disabled v-if="Object.keys(product_variation.subVariations).length> 0" class="form-control form-control-sm sub-variations" name="sub-variations[]" @change="subVariationselected(index, $event)">
                <option selected value="null">
                </option>
                <option  v-for="(item, index)  in product_variation.subVariations" :value="item.id" :selected="item.id==product_variation.subVariationId">
                  {{item.name}}
                </option>
             </select>
            <select disabled class="form-control  form-control-sm sub-variations" name="sub-variations[]" v-else @change="subVariationselected(index, $event)">
              <option selected value="null">
              </option>
                <option  v-for="(item, index)  in product_variation.subVariations" :value="item.id">
                  {{item.name}}
                </option>
            </select>
          </div>
      </div>
      <div class="col-md-2">
          <div class="form-group">
              <label>afbeelding id</label>
              <select class="form-control form-control-sm variation-image" name="variation-image[]" v-model="product_variation.image_id">
                <option value="">
                </option>
                <option  v-for="(item, index)  in images" :value="item.id" :selected="item.id==product_variation.image_id">
                  ({{item.id}})-{{item.title}}
                </option>
              </select>
          </div>
      </div>
      <div class="col-md-1">
          <div class="form-group">
              <label>Prijs</label>
            <input type="number" class="form-control form-control-sm" min="0" name="variation-prijs[]" v-model="product_variation.price"/>
          </div>
      </div>
      <div class="col-md-1" style="display:none">
          <div class="form-group">
              <label>EAN</label>
            <input type="text" class="form-control form-control-sm"  name="variation-prijs[]" v-model="product_variation.ean"/>
          </div>
      </div>

      <div class="col-md-1">
        <div class="form-row">
          <div class="col-md-6">
                <label>Active </label>
              <input type="checkbox" style="width: auto;float: none;margin: 0 auto;" class="form-control form-control-sm"  name="variation-active[]" v-model="product_variation.active">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <input class="form-control form-control-sm border-0  btn-info" type="button" v-on:click="addRow"  name="add-btn" value="+">
      </div>
      <div class="col-md-4"></div>
      <div class="col-md-4">
        <input class="form-control form-control-sm btn-sm border-0  btn-success" type="button" v-on:click="updateVariations"  name="add-btn" value="bijwerken">
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['update_url','product_id'],
  data(){
    return {
      productVariations:[],
      variationsGroup:[],
      images:[],
    }
  },
  mounted: function () {
    let _this = this;
    axios.get('/product-info/get-variations', {params: {product_id: _this.product_id}})
      .then( (response) => {
        _this.images=response.data.images;
        _this.variationsGroup=response.data.variationGroups;
        _this.count=_this.productVariations.length;
          $.each( response.data.currentVariations, function( key, value, ) {
            if(value.sub_variation_group_name && value.sub_variation_group_name!=''){
              var subVariations =_this.variationsGroup[value.sub_variation_group_name]['variations'];
            }
            else{
              subVariations='';
            }
            _this.productVariations.push({
                id: value.id,
                variationId: value.variation_id,
                image_id: value.image_id,
                variationName: value.name,
                variationGroupName: value.variation_group_name,
                variationGroupId:value.variation_group_id,
                variations: _this.variationsGroup[value.variation_group_name]['variations'],
                subVariations: subVariations,
                subVariationId:  value.sub_variation_id,
                subVariationGroupId:value.sub_variation_group_id,
                subVariationGroupName: value.sub_variation_group_name,
                active:(value.active ==1),
                ean:value.ean,
                price:value.price,
                new:0
            });
        });
      },(error) => {
        console.log(error);
      });
  },
  methods: {
      remove:function (index) {
        //this.productVariations.splice(index, 1);
      },
      variationsGroupSelected:function (index, event) {
          var selected = event.target.value;
          this.productVariations[index].variations=this.variationsGroup[selected]['variations'];
          this.productVariations[index]['variationGroupId']=this.variationsGroup[selected]['id'];
      },
      variationselected:function (index, event) {
          var selected = event.target.value;
          this.productVariations[index]['variationId']=selected;
      },
      subVariationsGroupSelected:function (index, event) {
          var selected = event.target.value;
          this.productVariations[index].subVariations=this.variationsGroup[selected]['variations'];
          this.productVariations[index]['subVariationGroupId']=this.variationsGroup[selected]['id'];
      },
      subVariationselected:function (index, event) {
          var selected = event.target.value;
          this.productVariations[index]['subVariationId']=selected;
      },
      addRow: function () {
          this.productVariations.push({
            id: '',
            variationId:  '',
            image_id:  '',
            variationName:  '',
            variationGroupName:  '',
            variationGroupId:'',
            variations:  '',
            subVariations:  '',
            subVariationId: '',
            subVariationGroupId: '',
            subVariationGroupName: '',
            active: '',
            ean:'',
            price:'',
            new:1,
          });
      },
      updateVariations: function(){
        var _this = this;
        axios.post(_this.update_url, {
            productVariations: _this.productVariations,
            product_id:_this.product_id
        }).then( (response) => {
                if (response.data.status == 'true') {
                    toastr.success(response.data.msg);
                    _this.$emit('row-updated',_this.productVariations);
                } else {
                    console.log(response);
                    toastr.warning(response.data.msg);
                }
            },
            (error) => {

                  console.log(error);
              }
            );
      }
  },
}
</script>
