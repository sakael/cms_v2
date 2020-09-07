{% extends "layouts/base.tpl" %}
{% block cssfiles_before %}<!-- third party css -->
	<!-- third party css end -->
{% endblock %}
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

	<ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
		<li class="nav-item">
			<a href="#main_information_tab" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0 active">
				<i class="mdi mdi-playlist-star d-md-none d-block"></i>
				<span class="d-none d-md-block">Hoofdinformatie</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#types_tab" data-toggle="tab" aria-expanded="true" class="nav-link rounded-0">
				<i class="mdi mdi-all-inclusive d-md-none d-block"></i>
				<span class="d-none d-md-block">Typen</span>
			</a>
		</li>
	</ul>
	<!-- Tab panels -->
	<div
		class="tab-content">
		<!-- main_information_tab -->
		{% include 'brand/tabs/main_information_tab.tpl' %}
		<!-- end main_information_tab -->
		<!-- main_categories_tab -->
	{% include 'brand/tabs/types_tab.tpl' %}
		<!-- end main_categories_tab -->
	</div>
{% endblock %}
{% block javascript %}
	{% include 'partials/tinymce.tpl' %}
	<!-- third party js -->
	<script src="/dist/assets/js/vendor/jquery.dataTables.min.js"></script>
	<script src="/dist/assets/js/vendor/dataTables.bootstrap4.js"></script>
	<script src="/dist/assets/js/vendor/dataTables.responsive.min.js"></script>
	<script src="/dist/assets/js/vendor/responsive.bootstrap4.min.js"></script>
	<!-- specific page js file -->
	<script>
		var product_brand_id = {{ Brand.id }};
	</script>
	<script src="/assets/js/pages/brand_edit.js"></script>
{% endblock %}
