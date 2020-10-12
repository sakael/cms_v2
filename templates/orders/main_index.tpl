{% extends "layouts/base.tpl" %}
{% block cssfiles_before %}
	<!-- third party css -->
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
	{% include '/orders/partials/claimby.tpl' %}
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
								<span class="d-md-none d-block">Nieuwe</span>
								<span class="d-none d-md-block">Nieuwe orders</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#claimedOrders" data-toggle="tab" aria-expanded="true" data-toggletab="claimedOrders" class="nav-link rounded-0 ">
								<span class="d-md-none d-block">Geclaimd</span>
								<span class="d-none d-md-block">Geclaimd / inpakken</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#readyForShippingOrders" data-toggle="tab" aria-expanded="false" data-toggletab="readyForShippingOrders" class="nav-link rounded-0">
								<span class="d-md-none d-block">Verzendklaar</span>
								<span class="d-none d-md-block">Verzendklaar</span>
							</a>
						</li>
					</ul>

					<div class="tab-content">
						{% include 'orders/main_tabs/new_orders.tpl' %}
						{% include 'orders/main_tabs/claimed.tpl' %}
						{% include 'orders/main_tabs/ready_for_shipping.tpl' %}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="checking-postcode-popup" class="modal fade model-fullwidth" tabindex="-1" role="dialog" aria-labelledby="info-header-modalLabel" aria-hidden="true">
		<div class="modal-dialog modal-full-width modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header modal-colored-header bg-info">
					<h4 class="modal-title" id="info-header-modalLabel">Postcode controle</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light btn-warning" data-dismiss="modal">Sluiten</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
{% endblock %}
{% block javascript %}
	<!-- third party js -->
	<script src="dist/assets/js/vendor/jquery.dataTables.min.js"></script>
	<script src="dist/assets/js/vendor/dataTables.bootstrap4.js"></script>
	<script src="dist/assets/js/vendor/dataTables.responsive.min.js"></script>
	<script src="dist/assets/js/vendor/responsive.bootstrap4.min.js"></script>
	<script src="/js/DYMO.Label.Framework.latest.js" type="text/javascript" charset="UTF-8"></script>
	<script type='text/javascript' src="/js/picklist/StarWebPrintBuilder.js"></script>
    <script type='text/javascript' src="/js/picklist/StarWebPrintTrader.js"></script>
    <script src="/js/DYMO.Label.Framework.latest.js" type="text/javascript" charset="UTF-8"></script>
    <script src="/js/labels/base64.js" type="text/javascript"> </script>
    <script src="/js/labels/printLabel.js"></script>
	<!-- specific page js file -->
	<script type="text/javascript">
		var image_url ="{{ IMAGE_PATH }}";{% if orderTab !='' %}var orderTab ='{{ orderTab }}';
{% else %}
var orderTab = "";{% endif %}
	</script>
	<script src="assets/js/pages/orders/orders.js"></script>
	<script src="assets/js/pages/orders/main_orders.js"></script>
{% endblock %}
