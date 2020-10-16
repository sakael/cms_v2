<template>
  <div>
    <div class="table-responsive">
      <table width="100%" class="table">
        <thead>
          <tr>
            <th>id</th>
            <th>datum</th>
            <th>url</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="productUrl in product_urls">
            <td>{{ productUrl.id }}</td>
            <td>{{ productUrl.created_at }}</td>
            <td v-if="productUrl.new != 1">
              <a :href="'http://' + shop_domain + '/' + productUrl.slug + '.html'" target="_blank">{{ productUrl.slug }}.html</a>
            </td>
            <td v-else-if="productUrl.new == 1"><input type="text" class="form-control" v-model="productUrl.slug" /></td>
            <td v-if="productUrl.new == 1">
              <div name="add-btn" class="form-control border-0 btn-success text-center" @click="addUrl()">
                <i class="fa fa-check"></i>
              </div>
            </td>
            <td v-else></td>
          </tr>
          <tr>
            <td colspan="4">
              <div class="text-center ">
                <a class="text-success font-24 hand" @click="addRow()">
                  <i class="dripicons-plus"></i>
                </a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <hr />
    <small>
      De bovenste URL wordt gebruikt op de webshop, oudere URLS verwijzen automatisch naar de bovenste
    </small>
  </div>
</template>
<script>
export default {
  props:['shop_domain','product_id','update_url','get_url'],
  data(){
    return {
      errors:[],
      product_urls:[]
    }
  },
  mounted: function () {
    let _this = this;
    axios.get(_this.get_url,{params: {product_id:_this.product_id}})
      .then( (response) => {
          $.each( response.data.product_urls, function( key, value, ) {
            _this.product_urls.push({
                id: value.id,
                slug: value.slug,
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
          this.product_urls.push({
            id:'',
            slug:'',
            timestamp:'',
            new: "1",
          });
      },
      addUrl() {
              var _this = this;
              axios.put(this.update_url, {
                  product_id: this.product_id,
                  product_urls: this.product_urls,
              }).then( (response) => {
                  if(response.data.status=='true'){
                    console.log(response.data);
                    _this.product_urls=[];
                    $.each( response.data.product_urls, function( key, value, ) {
                      _this.product_urls.push({
                          id: value.id,
                          slug: value.slug,
                          created_at: value.created_at,
                          new:0
                      });
                  });
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
</script>
