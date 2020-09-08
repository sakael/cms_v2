<div class="tab-pane fade in show active" id="main_information_tab" role="tabpanel">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<form role="form" method="POST" action="{{ path_for('Types.UpdateSingle',{'id':Type.id }) }}" class="form-horizontal bg-white p-3 m-3 needs-validation" novalidate enctype="multipart/form-data">
						<input id="type_id" type="hidden" name="id" value="{{ Type.id }}">
						<input type="hidden" name="_METHOD" value="PUT">
						<div class="row">
							<div class="col-md-10">
								<div class="row">
									<div class="col-md-3">
										<div class="form-group{{ errors.name ? ' has-danger': '' }}">
											<label for="name" class="label-material">Naam</label>
											<input id="name" type="text" name="name" class="form-control" value="{{ Type.name }}" required>
											{% if errors.name %}
												<small class="form-control-feedback ml-2">{{ errors.name |first }}</small>
											{% endif %}
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group{{ errors.slug ? ' has-danger': '' }}">
											<label for="slug" class="label-material">Slug</label>
											<input id="slug" type="text" name="slug" class="form-control" value="{{ Type.slug }}" {% if (Type.slug) and Type.slug !="" %} readonly {% endif %} required>
											{% if errors.slug %}
												<small class="form-control-feedback ml-2">{{ errors.slug |first }}</small>
											{% endif %}
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group{{ errors.brand ? ' has-danger': '' }}">
											<label for="brand" class="label-material">Merken</label>
											<select class="form-control" name="product_brand_id" id="brand" required>
												{% for Brand in Brands %}
													<option value="{{ Brand.id }}" {% if Brand.id==Type.product_brand_id %} selected {% endif %}>{{ Brand.name }}</option>
												{% endfor %}
											</select>
											{% if errors.brand %}
												<small class="form-control-feedback ml-2">{{ errors.brand |first }}</small>
											{% endif %}
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group{{ errors.main_category ? ' has-danger': '' }}">
											<label for="main_category" class="label-material">Hoofdcategorie</label>
											<input id="main_category" type="text" name="main_category" class="form-control" value="{{ Type.main_category_name }}" readonly required>
											{% if errors.main_category %}
												<small class="form-control-feedback ml-2">{{ errors.main_category |first }}</small>
											{% endif %}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="page_title" class="label-material">Page title</label>
											<input id="page_title" type="text" name="contents[page_title]" class="form-control" value="{{ Type.contents.page_title }}">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="page_sub_title" class="label-material">Page sub title</label>
											<input id="page_sub_title" type="text" name="contents[page_sub_title]" class="form-control" value="{{Type.contents.page_sub_title}}">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="seo_title" class="label-material">SEO title</label>
											<input id="seo_title" type="text" name="contents[seo_title]" class="form-control" value="{{Type.contents.seo_title}}">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-8">
										<div class="form-group">
											<label for="seo_description" class="label-material">SEO description</label>
											<input id="seo_description" type="text" name="contents[seo_description]" class="form-control" value="{{Type.contents.seo_description}}">
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
							</div>
							<div class="col-md-2 align-self-center">
								<div class="col-sm-12 h-100 d-table">
									<div class="d-table-cell align-middle">
										{% if Type.photo %}
											<img src="{{ IMAGE_PATH }}/{{getThumb(Type.photo,'123bestdeal')}}" style="max-width: 100%">
										{% endif %}
									</div>
								</div>
							</div>
						</div>
						<div class="row mt-4">
							<div class="col-md-6">
								<div class="form-group">
									<label for="page_top" class="label-material">Page top</label>
									<textarea rows="10" class="form-control rich-text" name="contents[page_top]" id="page_top">{{Type.contents.page_top}}</textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="page_bottom" class="label-material">Page bottom</label>
									<textarea rows="10" class="form-control rich-text" name="contents[page_bottom]" id="page_bottom">{{Type.contents.page_bottom}}</textarea>
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
								<div class="form-group{{ errors.width ? ' has-danger': '' }}">
									<label for="width" class="label-material">Breedte</label>
									<input id="width" type="text" name="measurements[width]" class="form-control" value="{{ Type.measurements.width }}" required>
									{% if errors.width %}
										<small class="form-control-feedback ml-2">{{ errors.measurements.width |first }}</small>
									{% endif %}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group{{ errors.height ? ' has-danger': '' }}">
									<label for="height" class="label-material">Hoogte</label>
									<input id="height" type="text" name="measurements[height]" class="form-control" value="{{ Type.measurements.height }}" required>
									{% if errors.height %}
										<small class="form-control-feedback ml-2">{{ errors.height |first }}</small>
									{% endif %}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group{{ errors.length ? ' has-danger': '' }}">
									<label for="length" class="label-material">Lengte</label>
									<input id="length" type="text" name="measurements[length]" class="form-control" value="{{ Type.measurements.length }}" required>
									{% if errors.length %}
										<small class="form-control-feedback ml-2">{{ errors.measurements.length |first }}</small>
									{% endif %}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group{{ errors.kb_options ? ' has-danger': '' }}">
									<label for="kb_options" class="label-material">kb options</label>
									<input id="kb_options" type="text" name="kb_options" class="form-control" value="{% if(Type.kb_options) %}{{Type.kb_options}}{%else%}0{% endif %}">
									{% if errors.kb_options %}
										<small class="form-control-feedback ml-2">{{ errors.kb_options |first }}</small>
									{% endif %}
								</div>
							</div>
							<div class="col-md-3">
								<div class="search-field-continer">
									<label for="staff_pick" class="label-material">Staff pick</label>
									<div class="form-group{{ errors.staff_pick ? ' has-danger': '' }} custom-search-form ">
										<select class="form-control" name="staff_pick">
											<option value="0" selected>----</option>
											{% for product in products %}
												<option value="{{product.id}}" {% if(Type.staff_pick == product.id) %} selected {% endif %}>{{product.sku}}
													-
													{{product.title}}</option>
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
							<div class="col-md-12 mt-3">
								<div class="row">
									<div class="col-md-12">
										<h3>Zichtbaar in</h3>
									</div>
									<div class="col-md-12">
										<div class="custom-control custom-checkbox custom-control-inline">
											<input type="checkbox" class="custom-control-input" id="active_menu" name="active_menu" {% if Type.active_menu==1 %} checked="checked" {% endif %} value="1">
											<label class="custom-control-label" for="active_menu">Menu</label>
										</div>
										<div class="custom-control custom-checkbox custom-control-inline">
											<input type="checkbox" class="custom-control-input" id="active_feed" name="active_feed" {% if Type.active_feed==1 %} checked="checked" {% endif %} value="1">
											<label class="custom-control-label" for="active_feed">Feed</label>
										</div>
										<div class="custom-control custom-checkbox custom-control-inline">
											<input type="checkbox" class="custom-control-input" id="popular_list" name="popular_list" {% if Type.popular_list==1 %} checked="checked" {% endif %} value="1">
											<label class="custom-control-label" for="popular_list">Menu</label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 mt-5">
								<div class="form-group">
									<button type="submit" class="btn btn-block btn-primary">
										Bijwerken
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<!-- END CARD BODY -->
			</div>
		</div>
	</div>
</div>
