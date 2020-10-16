{% extends "layouts/base.tpl" %}
{% block cssfiles %}
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
					<div class="row mb-2">
						<div class="col-sm-4">
							<a href="{{path_for('Categories.GetAdd')}}" class="btn btn-primary mb-2">
								<i class="mdi mdi-plus-circle mr-2"></i>
								Category toevoegen</a>
						</div>
						<!-- end col-->
					</div>
					<div class="table-responsive">
						<table class="table table-centered w-100 dt-responsive nowrap" id="categories-datatable">
							<thead class="thead-light text-sm">
								<tr>
									<th>Id</th>
									<th>Titel</th>
									<th>Actief</th>
									<th>Gemaakt op</th>
									<th class="table-action text-center">>
										<i class="fa fa-cog"></i>
									</th>
								</tr>
							</thead>
							<tbody>
								{% for category in categories %}
									<tr>
										<td>
											{{category.id}}
										</td>
										<td>
											{{category.name}}
										</td>
										<td {% if category.active == '1' %} data-sort="1" data-order="1" {% else %} data-sort="0" data-order="0" {% endif %}>
											{% if category.active == '1' %}
												<i class="uil uil-check fs-26 text-success " data-order="1"></i>
											{% endif %}
										</td>
										<td>
											{{category.created_at}}
										</td>
										<td class="table-action text-center">>
											<a href="{{ path_for('Categories.GetSingle',{'id':category.id}) }}" class="action-icon" target="_blank">
												<i class="mdi mdi-square-edit-outline"></i>
											</a>
										</td>
									</tr>
								{% endfor %}
							</tbody>
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
	<script src="/assets/js/pages/categories.js"></script>
{% endblock %}
