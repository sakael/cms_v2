<template>
  <div>
    <div class="form-group"  v-for="(item, index)  in characteristics">
       <div class="form-group row mb-3">
          <label class="col-md-2 col-form-label" for="seo_title">Kenmerk {{index+1}}</label>
          <div class="col-md-10">
              <input type="text" class="form-control form-control-sm  product-information" name="contents[characteristics]" data-mapping="characteristics" :data-mappingkey="index+1" :id="'characteristic_' + index" v-model="item.value" >
          </div>
       </div>
    </div>
    <div class="form-group row ">
      <div class="col-md-2 offset-md-4 mb-3">
        <input class="btn btn-block btn-sm border-0 btn-info" type="button" v-on:click="addRow"  name="add-btn" value="+">
      </div>
      <div class="col-md-2 offset-md-1 mb-3">
        <input class="btn btn-block btn-sm border-0 btn-warning" type="button" v-on:click="removeRow"  name="add-btn" value="-">
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
