{% extends "layouts/base.tpl" %}
{% block cssfiles_before %}<!-- third party css -->
<link href="/dist/assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css"/>
	<link
	href="/dist/assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css"/>
	<!-- third party css end -->
{% endblock %}
{% block cssfiles %}
	<!-- third party css -->
	
    <link
	href="/assets/flags/css/flag-icon.min.css" rel="stylesheet" type="text/css"/>
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
					<ul class="nav nav-pills bg-nav-pills nav-justified mb-3 orders-nav-tabs">
						<li class="nav-item">
							<a href="#newOrders" data-toggle="tab" aria-expanded="false" data-toggletab="newOrders" class="nav-link rounded-0 active">
								<i class="mdi mdi-home-variant d-md-none d-block"></i>
								<span class="d-none d-md-block">Nieuwe orders</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#claimedOrders" data-toggle="tab" aria-expanded="true" data-toggletab="claimedOrders" class="nav-link rounded-0 ">
								<i class="mdi mdi-account-circle d-md-none d-block"></i>
								<span class="d-none d-md-block">Geclaimd / inpakken</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#readyForShippingOrders" data-toggle="tab" aria-expanded="false"  data-toggletab="readyForShippingOrders" class="nav-link rounded-0">
								<i class="mdi mdi-settings-outline d-md-none d-block"></i>
								<span class="d-none d-md-block">Verzendklaar</span>
							</a>
						</li>
					</ul>

					<div class="tab-content">
					    {% include 'orders/main_tabs/new_orders.tpl' %}
						<div class="tab-pane" id="claimedOrders">
							<p>...</p>
						</div>
						<div class="tab-pane" id="readyForShippingOrders">
							<p>...</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{% endblock %}
{% block javascript %}
	<!-- third party js -->
	<script src="dist/assets/js/vendor/jquery.dataTables.min.js"></script>
	<script src="dist/assets/js/vendor/dataTables.bootstrap4.js"></script>
	<script src="dist/assets/js/vendor/dataTables.responsive.min.js"></script>
	<script src="dist/assets/js/vendor/responsive.bootstrap4.min.js"></script>
    <!-- specific page js file -->
    <script type="text/javascript">
      var image_url="{{IMAGE_PATH}}";
      {% if orderTab !='' %}
        var orderTab='{{orderTab}}';
      {% else %}
        var orderTab="";
      {% endif %}
    </script>
	<script src="assets/js/pages/orders/orders.js"></script>
    <script src="assets/js/pages/orders/main_orders.js"></script>
{% endblock %}
