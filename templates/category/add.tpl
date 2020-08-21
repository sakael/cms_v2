{% extends "layouts/base.tpl" %}
{% block cssfiles_before %}
	<!-- third party css -->
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
	<!-- end page title -->
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<form role="form" method="POST" action="{{path_for('Categories.PostAdd')}}" class="form-horizontal bg-white p-3 m-3 needs-validation" novalidate enctype="multipart/form-data">
						<input type="hidden" name="_METHOD" value="POST">
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
											<small class="form-control-feedback ml-2">
												<i>example-type</i>
											</small>
											{% if errors.slug %}
												<small class="form-control-feedback ml-2">{{errors.slug |first}}</small>
											{% endif %}
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="page_title" class="label-material">Page Ttitle</label>
											<input id="page_title" type="text" name="contents[page_title]" class="form-control" value="{% if(old.contents.page_title) %}{{old.contents.page_title}}{% endif %}">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="page_sub_title" class="label-material">Page sub title</label>
											<input id="page_sub_title" type="text" name="contents[page_sub_title]" class="form-control" value="{% if(old.contents.page_sub_title) %}{{old.contents.page_sub_title}}{% endif %}">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="seo_description" class="label-material">SEO description</label>
											<textarea id="seo_description" type="text" name="contents[seo_description]" class="form-control" rows="5">
												{% if(old.contents.seo_description) %}
													{{old.contents.seo_description}}
												{% endif %}
												{{Category.contents.seo_description}}</textarea>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="seo_title" class="label-material">SEO title</label>
											<input id="seo_title" type="text" name="contents[seo_title]" class="form-control" value="{% if(old.contents.seo_title) %}{{old.contents.seo_title}}{% endif %}">
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-check mt-4 ml-2">
											<input type="checkbox" name="active" {% if old.active==1 %} checked="checked" {% endif %} value="1" class="custom-control-input" id="active">
											<label class="custom-control-label" for="active">Active</label>
										</div>
									</div>
									<div class="form-group col-md-3 ">
										<label for="photo" class="label-material">&nbsp;</label>
										<div class="custom-file">
											<input type="file" class="custom-file-input" id="photo" name="photo">
											<label class="custom-file-label" for="photo">Foto ...</label>
										</div>
									</div>

								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="page_top" class="label-material">Page top</label>
											<textarea rows="10" class="form-control rich-text" name="contents[page_top]" id="page_top">
												{% if(old.contents.page_top) %}
													{{old.contents.page_top}}
												{% endif %}
											</textarea>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="page_bottom" class="label-material">Page bottom</label>
											<textarea rows="10" class="form-control rich-text" name="contents[page_bottom]" id="page_bottom">
												{% if(old.contents.page_bottom) %}
													{{old.contents.page_bottom}}
												{% endif %}
											</textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 mt-4">
								<div class="form-group">
									<button type="submit" class="btn btn-block  btn-primary pull-left">
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
{% block javascript %}
	{% include "partials/tinymce.tpl" %}
{% endblock %}
