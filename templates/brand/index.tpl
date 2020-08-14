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
					<div class="row mb-2">
						<div class="col-sm-4">
							<a href="{{path_for('Brands.GetAdd')}}" class="btn btn-primary mb-2">
								<i class="mdi mdi-plus-circle mr-2"></i>
								Brand toevoegen</a>
						</div>
						<!-- end col-->
					</div>
					<div class="table-responsive">
						<table class="table table-centered w-100 dt-responsive nowrap" id="brands-datatable">
							<thead class="thead-light text-sm">
								<tr>
									<th>id</th>
									<th>titel</th>
									<th>Populaire</th>
									<th>Actief menu</th>
									<th>Actief feed</th>
									<th>Typen in</th>
									<th>Gemaakt op</th>
									<th>
										<i class="fa fa-cog"></i>
									</th>
								</tr>
							</thead>
							<tbody>
								{% for brand in brands %}
									<tr>
										<td>
											{{brand.id}}
										</td>
										<td>
											{{brand.name}}
										</td>
										<td>
											{{brand.popular_list}}
										</td>
										<td>
											{{brand.active_menu}}
										</td>
										<td>
											{{brand.active_feed}}
										</td>
										<td>
											{{brand.types_count}}
										</td>
										<td>
											{{brand.created_at}}
										</td>
										<td class="table-action">
											<a href="{{ path_for('Brands.GetSingle',{'id':brand.id}) }}" class="action-icon" target="_blank">
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
	<script src="/assets/js/pages/brands.js"></script>
{% endblock %}
