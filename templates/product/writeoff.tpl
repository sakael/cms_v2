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
					<div
						class="form-inline mb-5">
						<!-- Autoclose -->
						<div class="form-group mr-3">
							<input type="text" class="form-control datepicker" data-provide="datepicker" data-date-autoclose="true" id="date_from">
						</div>
						<div class="form-group mr-3">
							<span>t/ m</span>
						</div>
						<!-- Autoclose -->
						<div class="form-group mr-3">
							<input type="text" class="form-control datepicker" data-provide="datepicker" data-date-autoclose="true" id="date_to">
						</div>
						<button class="btn btn-info" id="show_writeoff">tonen</button>
					</div>
					<div class="table-responsive">
						<table class="table table-centered w-100 dt-responsive nowrap" id="all_write_off_table">
							<thead>
								<tr>
									<th width="50">SKU</th>
                                    <th class="all">Titel</th>
									<th>Aantal</th>
									<th width="150">Totaal omzet</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

{% endblock %}
{% block javascript %}
<!-- third party js -->
<script src="/dist/assets/js/vendor/jquery.dataTables.min.js"></script>
<script src="/dist/assets/js/vendor/dataTables.bootstrap4.js"></script>
<script src="/dist/assets/js/vendor/dataTables.responsive.min.js"></script>
<script src="/dist/assets/js/vendor/responsive.bootstrap4.min.js"></script>

<!-- specific page js file -->
<script src="/assets/js/pages/writeoff.js"></script>
{% endblock %}
