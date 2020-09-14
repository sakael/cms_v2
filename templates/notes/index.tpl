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
	<ul class="nav nav-pills bg-nav-pills nav-justified">
		<li class="nav-item">
			<a class="nav-link active" data-toggletab="inbox" href="#inbox" data-toggle="tab" role="tab">Inbox</a>
		</li>
		<li class="nav-item">
			<a class="nav-link " href="#sent" data-toggletab="sent" data-toggle="tab" role="tab">Verzonden
				<span style="font-weight: bold;"></span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link " href="#niew" data-toggletab="niew" data-toggle="tab" role="tab">Nieuw</a>
		</li>
	</ul>
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="card-body">
						<div class="tab-content mt-4">
							{% include 'notes/tabs/inbox.tpl' %}
							{% include 'notes/tabs/sent.tpl' %}
							{% include 'notes/tabs/new.tpl' %}
						</div>
						<!-- /.tabcontent -->
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
	<script src="/assets/js/pages/attributes.js"></script>
{% endblock %}
