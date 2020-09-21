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
							<a href="#returnOrders" data-toggle="tab" aria-expanded="false" data-toggletab="returnOrders" class="nav-link rounded-0 active">
								<span class="d-md-none d-block">Retour</span>
								<span class="d-none d-md-block">Retour ontvangen</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#waitSupplierOrders" data-toggle="tab" aria-expanded="true" data-toggletab="waitSupplierOrders" class="nav-link rounded-0 ">
								<span class="d-md-none d-block">W op L</span>
								<span class="d-none d-md-block">Wacht op leverancier</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#waitExternalSupplierOrders" data-toggle="tab" aria-expanded="false"  data-toggletab="waitExternalSupplierOrders" class="nav-link rounded-0">
								<span class="d-md-none d-block">W op E</span>
								<span class="d-none d-md-block">Wacht op externe leverancier</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#waitCustomerOrders" data-toggle="tab" aria-expanded="false"  data-toggletab="waitCustomerOrders" class="nav-link rounded-0">
								<span class="d-md-none d-block">W op K</span>
								<span class="d-none d-md-block">Wacht op klant / intern</span>
							</a>
						</li>
                        <li class="nav-item">
							<a href="#creditOrders" data-toggle="tab" aria-expanded="false"  data-toggletab="creditOrders" class="nav-link rounded-0">
								<span class="d-md-none d-block">Te C</span>
								<span class="d-none d-md-block">Te crediteren</span>
							</a>
						</li>
					</ul>

					<div class="tab-content">
                        {% include 'orders/other_tabs/return.tpl' %}
						{% include 'orders/other_tabs/wait_supplier.tpl' %}
                        {% include 'orders/other_tabs/wait_external_supplier.tpl' %}
                        {% include 'orders/other_tabs/wait_customer.tpl' %}
                        {% include 'orders/other_tabs/credit.tpl' %}
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
    <script type="text/javascript">
      var image_url="{{IMAGE_PATH}}";
      {% if orderTab !='' %}
        var orderTab='{{orderTab}}';
      {% else %}
        var orderTab="";
      {% endif %}
    </script>
	<script src="/assets/js/pages/orders/orders.js"></script>
    <script src="/assets/js/pages/orders/other_tabs_orders.js"></script>
{% endblock %}
