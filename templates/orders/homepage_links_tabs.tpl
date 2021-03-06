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
							<a href="#returnExchange" data-toggle="tab" aria-expanded="false" data-toggletab="returnExchange" class="nav-link rounded-0 active">
								<span class="d-md-none d-block">Retour | Omruil</span>
								<span class="d-none d-md-block">Retour | Omruil</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#returnShipmentCredit" data-toggle="tab" aria-expanded="true" data-toggletab="returnShipmentCredit" class="nav-link rounded-0 ">
								<span class="d-md-none d-block">Retour | Credit</span>
								<span class="d-none d-md-block">Retour | Credit</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#waitPayment" data-toggle="tab" aria-expanded="false"  data-toggletab="waitPayment" class="nav-link rounded-0">
								<span class="d-md-none d-block">W op B</span>
								<span class="d-none d-md-block">Wacht op betaling</span>
							</a>
						</li>
					</ul>

					<div class="tab-content">
                        {% include 'orders/homepage_links_tabs/return_exchange.tpl' %}
						{% include 'orders/homepage_links_tabs/return_shipment_credit.tpl' %}
                        {% include 'orders/homepage_links_tabs/wait_payment.tpl' %}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="checking-postcode-popup" class="modal fade model-fullwidth">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Loading...</p>
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
    <script src="/assets/js/pages/orders/homepage_links_tabs.js"></script>
{% endblock %}
