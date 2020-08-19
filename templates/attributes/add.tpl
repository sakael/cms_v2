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
					<form role="form" method="POST" action="{{path_for('Attributes.PostAdd')}}" class="form-horizontal bg-white p-3 m-3">
						<input type="hidden" name="_METHOD" value="POST">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group{{ errors.name ? ' has-danger': ''}}">
									<label for="name" class="label-material">Naam</label>
									<input id="name" type="text" name="name" class="form-control" value="{% if(old.name) %}{{old.name}}{% endif %}">
									{% if errors.name %}
										<small class="form-control-feedback ml-2">{{errors.name |first}}</small>
									{% endif %}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group{{ errors.attribute_group ? ' has-danger': ''}}">
									<label for="attribute_group" class="label-material">Attribuutgroep</label>
									{% if selected != 0 %}
										<select class="form-control" name="attribute_group" id="attribute_group" disabled>
											<option value="{{ AttributeGroup.id }}" selected="selected">{{ AttributeGroup.name }}</option>
										</select>
									{% else %}
										<select class="form-control select2" data-toggle="select2" name="attribute_group" id="attribute_group" required>
											{% for AG in AttributeGroup %}
												<option value="{{ AG.id }}">{{ AG.name }}</option>
											{% endfor %}
										</select>
									{% endif %}
									{% if errors.attribute_group %}
										<small class="form-control-feedback ml-2">{{errors.attribute_group |first}}</small>
									{% endif %}
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<button type="submit" class="btn btn-primary pull-left">
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
