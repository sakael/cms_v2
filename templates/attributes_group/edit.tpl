{% extends "layouts/base.tpl" %}
{% block cssfiles_before %}
	<!-- third party css -->
	<link href="/dist/assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css"/>
	<link
	href="/dist/assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css"/>
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
					<form role="form" method="POST" action="{{path_for('AttributeGroups.UpdateSingle')}}" class="form-horizontal bg-white p-3 m-3 needs-validation" novalidate>
						<input type="hidden" name="id" value="{{ AttributeGroup.id }}">
						<input type="hidden" name="_METHOD" value="PUT">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group{{ errors.name ? ' has-danger': ''}}">
									<label for="name" class="label-material">Naam</label>
									<input id="name" type="text" name="name" class="form-control" value="{{AttributeGroup.name}}" required>
									{% if errors.name %}
										<small class="form-control-feedback ml-2">{{errors.name |first}}</small>
									{% endif %}
								</div>
								<div class="form-group{{ errors.multiselect ? ' has-danger': ''}}">
									<label for="multiselect" class="label-material">Multiselect</label>
									<input type="checkbox" value="1" {% if AttributeGroup.multiselect==1 %} checked="checked" {% endif %} name="multiselect">
									{% if errors.multiselect %}
										<small class="form-control-feedback ml-2">{{errors.multiselect |first}}</small>
									{% endif %}
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<button type="submit" class="btn btn-primary pull-left">
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

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="m-t-0 header-title mb-4">Alle Attributen in
						{{AttributeGroup.name}}</h4>
					<div class="table-responsive">
						<table class="table table-centered w-100 dt-responsive nowrap" id="all_attributes_table">
							<thead class="thead-light text-sm">
								<tr>
									<th>Id</th>
									<th class="all">Title</th>
									<th>Attribuut groep</th>
									<th>
										<i class="fa fa-cog"></i>
									</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
				<!-- end card-body-->
			</div>
			<!-- end card-->
		</div>
		<!-- end col -->
	</div>
	<!-- end row -->
{% endblock %}
{% block javascript %}
	<!-- third party js -->
	<script src="/dist/assets/js/vendor/jquery.dataTables.min.js"></script>
	<script src="/dist/assets/js/vendor/dataTables.bootstrap4.js"></script>
	<script src="/dist/assets/js/vendor/dataTables.responsive.min.js"></script>
	<script src="/dist/assets/js/vendor/responsive.bootstrap4.min.js"></script>

	<!-- specific page js file -->
	<script>
		var id = {{ AttributeGroup.id }};
	</script>
	<script src="/assets/js/pages/attributes-group-single.js"></script>
{% endblock %}
