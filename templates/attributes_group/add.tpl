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
					<form role="form" method="POST" action="{{path_for('AttributeGroups.PostAdd')}}" class="form-horizontal bg-white p-3 m-3 needs-validation" novalidate>
						<input type="hidden" name="_METHOD" value="POST">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group{{ errors.name ? ' has-danger': ''}}">
									<label for="name" class="label-material">Naam</label>
									<input id="name" type="text" name="name" class="form-control" value="{% if(old.name) %}{{old.name}}{% endif %}" required>
									{% if errors.name %}
										<small class="form-control-feedback ml-2">{{errors.name |first}}</small>
									{% endif %}
								</div>
							</div>
							<div class="col-md-6">
								<div class="custom-control mt-4 ml-4 custom-checkbox {{ errors.multiselect ? ' has-danger': ''}}">
									<input type="checkbox" class="custom-control-input" value="1" name="multiselect" {% if old.multiselect==1 %} checked="checked" {% endif %} id="multiselect">
									<label class="custom-control-label" for="multiselect">Multiselect</label>
									{% if errors.multiselect %}
										<small class="form-control-feedback ml-2">{{errors.multiselect |first}}</small>
									{% endif %}
								</div>
							</div>
							<div class="col-md-12 mt-4">
								<div class="form-group">
									<button type="submit" class="btn btn-block btn-primary pull-left">
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
