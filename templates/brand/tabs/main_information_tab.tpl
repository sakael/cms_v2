<div class="tab-pane fade in show active" id="main_information_tab" role="tabpanel">
   <form role="form" method="POST" action="{{path_for('Brands.UpdateSingle',{'id': Brand.id })}}" class="form-horizontal bg-white p-3 m-3needs-validation" novalidate enctype="multipart/form-data">
      <input type="hidden" name="id" value="{{ Brand.id }}">
      <input type="hidden" name="_METHOD" value="PUT">
      <div class="row">
         <div class="col-md-10">
            <div class="row">
               <div class="col-md-3">
                  <div class="form-group{{ errors.name ? ' has-danger': ''}}">
                     <label for="name" class="label-material">Naam</label>
                     <input id="name" type="text" name="name" class="form-control" value="{{Brand.name}}">
                     {% if errors.name %}
                     <small class="form-control-feedback ml-2">{{errors.name |first}}</small>
                     {% endif %}
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group{{ errors.slug ? ' has-danger': ''}}">
                     <label for="slug" class="label-material">Slug</label>
                     <input id="slug" type="text" name="slug" class="form-control" value="{{Brand.slug}}" {% if (Brand.slug) and  Brand.slug !="" %} readonly {% endif %}>
                     <small class="form-control-feedback ml-2"><i>example-brand</i></small>
                     {% if errors.slug %}
                     <small class="form-control-feedback ml-2">{{errors.slug |first}}</small>
                     {% endif %}
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label for="page_title" class="label-material">Page title</label>
                     <input id="page_title" type="text" name="contents[page_title]" class="form-control" value="{{Brand.contents.page_title}}">
                  </div>
               </div>
               <div class="form-group col-md-3">
                  <div class="form-group">
                     <label for="page_sub_title" class="label-material">Page sub title</label>
                     <input id="page_sub_title" type="text" name="contents[page_sub_title]" class="form-control" value="{{Brand.contents.page_sub_title}}">
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     <label for="seo_title" class="label-material">SEO title</label>
                     <input id="seo_title" type="text" name="contents[seo_title]" class="form-control" value="{{Brand.contents.seo_title}}">
                  </div>
               </div>
               <div class="col-md-6">
                 <div class="form-group">
                    <label for="seo_description" class="label-material">SEO description</label>
                    <input id="seo_description" type="text" name="contents[seo_description]" class="form-control" value="{{Brand.contents.seo_description}}">
                 </div>
               </div>
               <div class="form-group col-md-2">
                  <label for="photo" class="label-material">&nbsp;</label>
                  <div class="custom-file">
                     <input type="file" class="custom-file-input" id="photo" name="photo">
                     <label class="custom-file-label" for="photo">Foto ...</label>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-2 align-self-center">
            <div class="col-sm-12 h-100 d-table">
               <div class="d-table-cell align-middle">
                  {% if Brand.photo %}
                  <img src="{{IMAGE_PATH}}/{{getThumb(Brand.photo,'123bestdeal')}}" style="max-width: 100%">
                  {% endif %}
               </div>
            </div>
         </div>
      </div>
      <div class="row mt-4">
         <div class="col-md-6">
            <div class="form-group">
               <label for="page_top" class="label-material">Page top</label>
               <textarea rows="10" class="form-control rich-text" name="contents[page_top]" id="page_top">{{Brand.contents.page_top}}</textarea>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group">
               <label for="page_bottom" class="label-material">Page bottom</label>
               <textarea rows="10" class="form-control rich-text" name="contents[page_bottom]" id="page_bottom">{{Brand.contents.page_bottom}}</textarea>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-6 mt-3">
            <div class="row">
               <div class="col-md-3">
                  <label for="" class="font-weight-bold">Zichtbaar in :</label>
               </div>
               <div class="form-group col-md-3">
                  <label for="active_menu" class="label-material">Menu</label>
                  <input name="active_menu" {% if Brand.active_menu==1 %} checked="checked" {% endif %} value="1" type="checkbox">
               </div>
               <div class="form-group col-md-3">
                  <label for="active_feed" class="label-material">Feed</label>
                  <input name="active_feed" {% if Brand.active_feed==1 %} checked="checked" {% endif %} value="1" type="checkbox">
               </div>
               <div class="form-group col-md-3">
                  <label for="popular_list" class="label-material">Popular List </label>
                  <input name="popular_list" {% if Brand.popular_list==1 %} checked="checked" {% endif %} value="1" type="checkbox">
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="form-group">
               <button type="submit" class="btn btn-primary pull-left col-md-12">
               Bijwerken
               </button>
            </div>
         </div>
      </div>
   </form>
</div>
