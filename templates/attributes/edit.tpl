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
					<form role="form" method="POST" action="{{path_for('Attributes.UpdateSingle')}}" class="form-horizontal bg-white p-3 m-3 needs-validation" novalidate>
						<input type="hidden" name="id" value="{{ Attribute.id }}">
						<input type="hidden" name="_METHOD" value="PUT">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group{{ errors.name ? ' has-danger': ''}}">
									<label for="name" class="label-material">Naam</label>
									<input id="name" type="text" name="name" class="form-control" value="{{Attribute.name}}" required>
									{% if errors.name %}
										<small class="form-control-feedback ml-2">{{errors.name |first}}</small>
									{% endif %}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group{{ errors.attribute_group ? ' has-danger': ''}}">
									<label for="attribute_group" class="label-material">Attribuutgroep</label>
									<select class="form-control select2" data-toggle="select2" name="attribute_group" id="attribute_group" required>
										{% for AttributeGroup in AttributeGroups %}
											<option value="{{ AttributeGroup.id }}" {% if AttributeGroup.id==Attribute.attribute_group_id %} selected {% endif %}>{{ AttributeGroup.name }}</option>
										{% endfor %}
									</select>
									{% if errors.attribute_group %}
										<small class="form-control-feedback ml-2">{{errors.attribute_group |first}}</small>
									{% endif %}
								</div>
							</div>
							<div class="col-md-12 mt-4">
								<div class="form-group">
									<button type="submit" class="btn btn-block btn-primary pull-left ">
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
