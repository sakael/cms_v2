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


	<!-- end page title -->
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<form role="form" method="POST" action="{{path_for('Categories.UpdateSingle')}}" class="form-horizontal bg-white p-3 m-3 needs-validation" novalidate enctype="multipart/form-data">
						<input type="hidden" name="id" value="{{ Category.id }}">
						<input type="hidden" name="_METHOD" value="PUT">
						<div class="row">
							<div class="col-md-10">
								<div class="row">
									<div class="col-md-3">
										<div class="form-group{{ errors.name ? ' has-danger': ''}}">
											<label for="name" class="label-material">Naam</label>
											<input id="name" type="text" name="name" class="form-control" value="{{Category.name}}" required>
											{% if errors.name %}
												<small class="form-control-feedback ml-2">{{errors.name |first}}</small>
											{% endif %}
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group{{ errors.slug ? ' has-danger': ''}}">
											<label for="slug" class="label-material">Slug</label>
											<input id="slug" type="text" name="slug" class="form-control" value="{{Category.slug}}" {% if (Category.slug) and Category.slug !="" %} readonly {% endif %} required>
											<small class="form-control-feedback ml-2">
												<i>example-category</i>
											</small>
											{% if errors.slug %}
												<small class="form-control-feedback ml-2">{{errors.slug |first}}</small>
											{% endif %}
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="page_title" class="label-material">Page Ttitle</label>
											<input id="page_title" type="text" name="contents[page_title]" class="form-control" value="{{Category.contents.page_title}}">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="page_sub_title" class="label-material">Page sub title</label>
											<input id="page_sub_title" type="text" name="contents[page_sub_title]" class="form-control" value="{{Category.contents.page_sub_title}}">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="seo_description" class="label-material">SEO description</label>
											<textarea id="seo_description" type="text" name="contents[seo_description]" class="form-control" rows="5">{{Category.contents.seo_description}}</textarea>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="seo_title" class="label-material">SEO title</label>
											<input id="seo_title" type="text" name="contents[seo_title]" class="form-control" value="{{Category.contents.seo_title}}">
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-check mt-4 pt-3">
											<input type="checkbox" name="active" {% if Category.active==1 %} checked="checked" {% endif %} value="1" class="form-check-input" id="active">
											<label class="form-check-label" for="active">Active</label>
										</div>
									</div>
									<div class="form-group col-md-3">
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
											<textarea rows="10" class="form-control rich-text" name="contents[page_top]" id="page_top">{{Category.contents.page_top}}</textarea>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="page_bottom" class="label-material">Page bottom</label>
											<textarea rows="10" class="form-control rich-text" name="contents[page_bottom]" id="page_bottom">{{Category.contents.page_bottom}}</textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2 align-self-center">
								<div class="col-sm-12 h-100 d-table">
									<div class="d-table-cell align-middle">
										{% if Category.photo %}
											<img src="{{IMAGE_PATH}}/{{getThumb(Category.photo,'123bestdeal')}}" style="max-width: 100%">
										{% endif %}
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mt-4">
								<div class="form-group">
									<button type="submit" class="btn btn-block btn-primary pull-left col-md-12">
										Bijwerken
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
