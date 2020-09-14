<template>
  <div>
    <div class="form-group"  v-for="(item, index)  in characteristics">
       <div class="row">
          <label class="control-label col-sm-2" for="seo_title">Kenmerk {{index+1}}</label>
          <div class="col-sm-10">
             <div class="input-group">
                <input type="text" class="form-control product-information" name="contents[characteristics]" data-mapping="characteristics" :data-mappingkey="index+1" :id="'characteristic_' + index" v-model="item.value" >
             </div>
          </div>
       </div>
    </div>
    <div class="row">
      <div class="col-md-2 offset-md-4 ">
        <input class="form-control border-0  btn-info" type="button" v-on:click="addRow"  name="add-btn" value="+">
      </div>
      <div class="col-md-2 offset-md-1 ">
        <input class="form-control border-0  btn-warning" type="button" v-on:click="removeRow"  name="add-btn" value="-">
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['characteristics'],
  data(){
    return {
    }
  },
  methods: {
      remove:function (index) {
        this.productVariations.splice(index, 1);
      },
      addRow: function () {
          this.characteristics.push({
            id: '',
            value:  ''
          });
      },
      removeRow: function () {
          this.characteristics.pop();
      },
  },
}
</script>
