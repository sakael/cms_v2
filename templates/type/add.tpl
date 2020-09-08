{% extends "layouts/base.tpl" %}
{% block cssfiles_before %}<!-- third party css -->
	<!-- third party css end -->
{% endblock %}
{% block page_title %}
	{{page_title}}
{% endblock %}
{% block content %}
	<!-- start page title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<h4 class="page-title">{{page_title}}</h4>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<form role="form" method="POST" action="{{path_for('Types.PostAdd')}}" class="form-horizontal bg-white p-3 m-3 needs-validation" novalidate enctype="multipart/form-data">
					 <input type="hidden" name="_METHOD" value="POST">
               {% if Brand %}
               <input type="hidden" name="product_brand_id" value="{{ Brand.id }}">
               {% endif %}
               <div class="row">
                  <div class="col-md-12">
                     <div class="row">
                        <div class="col-md-3">
                           <div class="form-group{{ errors.name ? ' has-danger': ''}}">
                              <label for="name" class="label-material">Naam</label>
                              <input id="name" type="text" name="name" class="form-control" value="{% if(old.name) %}{{old.name}}{% endif %}" required>
                              {% if errors.name %}
                              <small class="form-control-feedback ml-2">{{errors.name |first}}</small>
                              {% endif %}
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group{{ errors.slug ? ' has-danger': ''}}">
                              <label for="slug" class="label-material">Slug</label>
                              <input id="slug" type="text" name="slug" class="form-control" value="{% if(old.slug) %}{{old.slug}}{% endif %}" required>
                              <small class="form-control-feedback ml-2"><i>example-type</i></small>
                              {% if errors.slug %}
                              <small class="form-control-feedback ml-2">{{errors.slug |first}}</small>
                              {% endif %}
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group{{ errors.brand ? ' has-danger': ''}}">
                              <label for="brand" class="label-material">Merken</label>
                              <select class="form-control" name="product_brand_id" id="brand" {% if Brand %} disabled {% endif %} required>
                                {% if Brands %}
                                  {% for Brand in Brands %}
                                    <option value="{{ Brand.id }}" {% if Brand.id==old.product_brand_id %} selected="selected" {% endif %} >{{ Brand.name }}</option>
                                  {% endfor %}
                                {% elseif Brand %}
                                  <option value="{{ Brand.id }}" selected="selected" >{{ Brand.name }}</option>
                                {% endif %}
                              </select>
                              {% if errors.brand %}
                                <small class="form-control-feedback ml-2">{{errors.brand |first}}</small>
                              {% endif %}
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group{{ errors.main_category_id ? ' has-danger': '' }}">
                              <label for="main_category_id" class="label-material">Hoofdcategorie</label>
                              <select class="form-control" name="main_category_id" id="main_category_id" {% if Brand %} disabled {% endif %} required>
                                {% for mainCatoegory in mainCatoegories %}
                                  <option value="{{ mainCatoegory.id }}" {% if mainCatoegory.id==old.main_category_id %} selected="selected" {% endif %} >{{ mainCatoegory.name }}</option>
                                {% endfor %}
                              </select>
                              {% if errors.main_category_id %}
                                <small class="form-control-feedback ml-2">{{ errors.main_category_id |first }}</small>
                              {% endif %}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="page_title" class="label-material">Page title</label>
                              <input id="page_title" type="text" name="contents[page_title]" class="form-control" value="{% if(old.contents.page_title) %}{{old.contents.page_title}}{% endif %}">
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="page_sub_title" class="label-material">Page sub title</label>
                              <input id="page_sub_title" type="text" name="contents[page_sub_title]" class="form-control" value="{% if(old.contents.page_sub_title) %}{{old.contents.page_sub_title}}{% endif %}">
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="seo_title" class="label-material">SEO title</label>
                              <input id="seo_title" type="text" name="contents[seo_title]" class="form-control" value="{% if(old.contents.seo_title) %}{{old.contents.seo_title}}{% endif %}">
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-8">
                           <div class="form-group">
                              <label for="seo_description" class="label-material">SEO description</label>
                              <input id="seo_description" type="text" name="contents[seo_description]" class="form-control" value="{% if(old.contents.seo_description) %}{{old.contents.seo_description}}{% endif %}">
                           </div>
                        </div>
                        <div class="form-group col-md-4">
                           <label for="photo" class="label-material">&nbsp;</label>
                           <div class="custom-file">
                              <input type="file" class="custom-file-input" id="photo" name="photo">
                              <label class="custom-file-label" for="photo">Foto ...</label>
                           </div>
                        </div>
                     </div>
                     <div class="row mt-4">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="page_top" class="label-material">Page top</label>
                              <textarea rows="10" class="form-control rich-text" name="contents[page_top]" id="page_top">{% if(old.contents.page_top) %}{{old.contents.page_top}}{% endif %}</textarea>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="page_bottom" class="label-material">Page bottom</label>
                              <textarea rows="10" class="form-control rich-text" name="contents[page_bottom]" id="page_bottom">{% if(old.contents.page_bottom) %}{{old.contents.page_bottom}}{% endif %}</textarea>
                           </div>
                        </div>
                     </div>
                     <div class="row mt-4">
                        <div class="col-md-6">
                           <h3>Afmetingen</h3>
                        </div>
                        <div class="col-md-6">
                           <h3>Opties</h3>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-2">
                           <div class="form-group{{ errors.width ? ' has-danger': ''}}">
                              <label for="width" class="label-material">Breedte</label>
                              <input id="width" type="text" name="measurements[width]" class="form-control" value="{% if(old.measurements.width) %}{{old.measurements.width}}{% endif %}">
                              {% if errors.width %}
                              <small class="form-control-feedback ml-2">{{errors.width |first}}</small>
                              {% endif %}
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group{{ errors.measurements ? ' has-danger': ''}}">
                              <label for="height" class="label-material">Hoogte</label>
                              <input id="height" type="text" name="measurements[height]" class="form-control" value="{% if(old.measurements.height) %}{{old.measurements.height}}{% endif %}">
                              {% if errors.measurements %}
                              <small class="form-control-feedback ml-2">{{errors.measurements | first}}</small>
                              {% endif %}
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group{{ errors.length ? ' has-danger': ''}}">
                              <label for="length" class="label-material">Lengte</label>
                              <input id="length" type="text" name="measurements[length]" class="form-control" value="{% if(old.measurements.length) %}{{old.measurements.length}}{% endif %}">
                              {% if errors.length %}
                              <small class="form-control-feedback ml-2">{{errors.length |first}}</small>
                              {% endif %}
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group{{ errors.kb_options ? ' has-danger': ''}}">
                              <label for="kb_options" class="label-material">kb options</label>
                              <input id="kb_options" type="text" name="kb_options" class="form-control" value="{% if(old.kb_options) %}{{old.kb_options}}{%else%}0{% endif %}">
                              {% if errors.kb_options %}
                              <small class="form-control-feedback ml-2">{{errors.kb_options |first}}</small>
                              {% endif %}
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="search-field-continer" id="live-search-products">
                             <label for="staff_pick" class="label-material">Staff pick</label>
                             <div class="form-group{{ errors.staff_pick ? ' has-danger': '' }} custom-search-form ">
                               <select class="form-control" name="staff_pick">
                                 <option value="0" selected>----</option>
                                 {% for product in products %}
                                  <option value="{{product.id}}" {% if(Type.staff_pick == product.id) %} selected {% endif %}>{{product.sku}} - {{product.title}}</option>
                                 {% endfor %}
                               </select>
                               {% if errors.staff_pick %}
                               <small class="form-control-feedback ml-2">{{ errors.staff_pick |first }}</small>
                               {% endif %}
                             </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6 mt-3">
                           <div class="row">
                              <div class="col-md-12">
							     <h3>Zichtbaar in</h3>
                              </div>
							  
                              <div class="col-md-12">
								<div class="custom-control custom-checkbox custom-control-inline">
									<input type="checkbox" class="custom-control-input" id="active_menu" name="active_menu"  {% if old.active_menu==1 %} checked="checked" {% endif %} value="1" >
									<label class="custom-control-label" for="active_menu">Menu</label>
								</div>
                              <div class="custom-control custom-checkbox custom-control-inline">
									<input type="checkbox" class="custom-control-input" id="active_feed" name="active_feed"   {% if old.active_feed==1 %} checked="checked" {% endif %} value="1" >
									<label class="custom-control-label" for="active_feed">Feed</label>
								</div>
								<div class="custom-control custom-checkbox custom-control-inline">
									<input type="checkbox" class="custom-control-input" id="popular_list" name="popular_list"  {% if old.popular_list==1 %} checked="checked" {% endif %} value="1" >
									<label class="custom-control-label" for="popular_list">Menu</label>
								</div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12 mt-4">
                     <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary">
                        Toevoegen
                        </button>
                     </div>
                  </div>
               </div>
					</form>
				</div>
			</div>
		</div>
	</div>
{% endblock %}
{% block javascript %}{% endblock %}
