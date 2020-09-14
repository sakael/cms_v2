<template>
    <div class="categories-type">
        <div class="card" v-for="(item, index)  in categories">
            <div class="card-header text-center" v-on:click="opneClose(index)">
                {{item.name}}
                <i class="fa fa-minus" v-if="item.show"></i>
                <i class="fa fa-plus" v-else></i>
            </div>
            <div class="card-body" v-show="item.show">
              <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                            <label :for="'category_page_title_'+index" class="label-material">Page title</label>
                          <input :id="'category_page_title_'+index" type="text" class="form-control" v-model="item.page_title">
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                            <label :for="'category_page_sub_title_'+index" class="label-material">Page sub title</label>
                          <input :id="'category_page_sub_title_'+index" type="text" class="form-control" v-model="item.page_sub_title">
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                            <label :for="'category_seo_title_'+index" class="label-material">Seo title</label>
                          <input :id="'category_seo_title_'+index" type="text" class="form-control" v-model="item.seo_title">
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label :for="'category_seo_description_'+index" class="label-material">SEO Description</label>
                          <textarea rows="2"  class="form-control rich-text" :id="'category_seo_description_'+index" v-model="item.seo_description"></textarea>
                      </div>
                  </div>
                    <div class="col-md-3">
                      <label :for="'category_type_image_'+index" class="label-material">&nbsp;</label>
                      <div class="custom-file">
                         <input type="file" class="custom-file-input" :id="'category_type_image_'+index" @change="onFileChanged($event,index)">
                         <label class="custom-file-label" :for="'category_type_image_'+index">Foto ...</label>
                      </div>
                    </div>
                    <div class="col-md-3  text-center">
                      <img v-if="item.image!=''" :src="image_path+'/'+item.image" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label :for="'category_page_top_'+index" class="label-material">Page top</label>
                            <editor :api-key="'category_page_top_'+index"  v-model="item.page_top"></editor>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label :for="'category_page_bottom_'+index" class="label-material">Page bottom</label>
                            <editor :api-key="'category_page_bottom_'+index"  v-model="item.page_bottom"></editor>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary pull-left col-md-12 mt-3 mb-3" v-on:click="updateCategoryType(index)">
                                Bijwerken
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue';
export default {
  props: ['get_url','type_id','categories_type','update_url','image_path'],
  data(){
    return {
      categories:[],
    }
  },
  components: {
   'editor': Editor // <- Important part
 },
  mounted: function () {
    let _this = this;
    axios.get(_this.get_url)
      .then( (response) => {
          $.each( response.data.categories, function( key, value, ) {
            if(_this.categories_type[value.id]){
              _this.categories.push({
                  id: _this.categories_type[value.id].cat_id,
                  name: value.name,
                  page_title:_this.categories_type[value.id].page_title,
                  page_sub_title:_this.categories_type[value.id].page_sub_title,
                  seo_description:_this.categories_type[value.id].seo_description,
                  page_top:_this.categories_type[value.id].page_top,
                  page_bottom:_this.categories_type[value.id].page_bottom,
                  seo_title:_this.categories_type[value.id].seo_title,
                  show:0,
                  image:_this.categories_type[value.id].image,
              });
            }else{
              _this.categories.push({
                  id: value.id,
                  name: value.name,
                  page_title:'',
                  page_sub_title:'',
                  seo_description:'',
                  page_top:'',
                  page_bottom:'',
                  seo_title:'',
                  show:0,
                  image:'',
              });
            }
        });
      },(error) => {
        console.log(error);
      });
  },
  methods: {
    opneClose(index){
      if(this.categories[index].show==1)this.categories[index].show=0;
      else this.categories[index].show=1;
    },
    updateCategoryType: function(index){
      var _this = this;
      axios.post(_this.update_url, {
          category_type: _this.categories[index],
          type_id:_this.type_id,
          type:'content'
      }).then( (response) => {
              if (response.data.status == 'true') {
                  toastr.success(response.data.msg);
              } else {
                  console.log(response);
                  toastr.warning(response.data.msg);
              }
          },
          (error) => {
                console.log(error);
            }
          );
    },
    onFileChanged (event,index) {
      var fileName = event.target.value.split("\\").pop();
      $('#'+event.target.id).siblings(".custom-file-label").addClass("selected").html(fileName);

      var img = event.target.files[0];
      let formData=new FormData();
      formData.append('file',img);
      formData.set('type','image');
      formData.set('type_id',this.type_id);
      formData.set('cat_id',this.categories[index].id);
      var _this = this;
      axios.post(_this.update_url, formData,{headers: {
        'Content-Type': 'multipart/form-data'
    }}).then( (response) => {

              if (response.data.status == 'true' && response.data.image) {
                  toastr.success(response.data.msg);
                  _this.categories[index].image=response.data.image;
                  $('#'+event.target.id).siblings(".custom-file-label").addClass("selected").html('');
              } else {
                  console.log(response);
                  toastr.warning(response.data.msg);
              }
          },
          (error) => {
                console.log(error);
            }
          );
    },
  },
}
</script>