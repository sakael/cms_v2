{% extends "layouts/base.tpl" %}
{% block cssfiles %}
	<!-- third party css -->
	<link href="/dist/assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css"/>
	<link href="/dist/assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css"/>
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
				<div class="page-title-right">
					<ol class="breadcrumb m-0">
						<li class="breadcrumb-item">
							<a href="{{ path_for('ProductsIndex') }}">Alle artikelen</a>
						</li>
						<li class="breadcrumb-item">
							<a href="{{ path_for('ProductGet',{'id':product.id}) }}">{{ product.sku }}</a>
						</li>
						<li class="breadcrumb-item active">Product Details</li>
					</ol>
				</div>
				<h4 class="page-title">Product Details</h4>
			</div>
		</div>
	</div>
	<!-- end page title -->

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="statistics col-md-2">
							<div class="statistic d-flex align-items-center bg-white has-shadow">
								{% for image in product.images if image.main == 1 %}
									<div class="text-center" style="padding:15px;width: 100%;">
										<img class="img-fluid" src="{{ IMAGE_PATH }}/{{getThumb(image.url,'123bestdeal')}}" style="min-height:100px;height:100px;">
									</div>
								{% endfor %}
							</div>
						</div>
						<div class="statistics col-md-10">
							<div class="statistic d-flex align-items-center bg-white has-shadow" style="height: 100%">
								<div class="row">
									<div class="col-md-12">
										<p class="mt-2">{{ product.contents.title }}
											-
											{{ product.sku }}</p>
										<p>
											<small>{{ product.contents.seo_description|striptags }}</small>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- end card-body-->
			</div>
			<!-- end card-->
		</div>
		<!-- end col-->
	</div>
	<!-- end row-->


	<ul class="nav nav-pills bg-nav-pills nav-justifieds">
		<li class="nav-item">
			<a class="nav-link active" data-toggletab="main_information_tab" data-toggle="tab" href="#main_information_tab" role="tab">Hoofdinformatie</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggletab="main_images_tab" data-toggle="tab" href="#main_images_tab" role="tab">Afbeeldingen</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggletab="main_shops_tab" data-toggle="tab" href="#main_shops_tab" role="tab">Webshops</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggletab="main_categories_tab" data-toggle="tab" href="#main_categories_tab" role="tab">Categorieën</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggletab="main_connections_tab" data-toggle="tab" href="#main_connections_tab" role="tab">Merken/types</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggletab="main_attributes_tab" data-toggle="tab" href="#main_attributes_tab" role="tab">Attributen</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggletab="main_combos_tab" data-toggle="tab" href="#main_combos_tab" role="tab">Combo's</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggletab="main_orders_tab" data-toggle="tab" href="#main_orders_tab" role="tab">Bestellingen</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggletab="main_history_tab" data-toggle="tab" href="#main_history_tab" role="tab">Historie</a>
		</li>
		{% if auth.user.super %}
			<li class="nav-item">
				<a class="nav-link" data-toggletab="main_b2b_tab" data-toggle="tab" href="#main_b2b_tab" role="tab">B2B</a>
			</li>
		{% endif %}
	</ul>
	<!-- Tab panels -->
	<div
		class="tab-content">
		<!-- main_information_tab -->
		{% include 'product/tabs/main_information_tab.tpl' %}
		<!-- end main_information_tab -->

		<!-- main_images_tab -->
		{% include 'product/tabs/main_images_tab.tpl' %}
		<!-- end main_images_tab -->

		<!-- main_shops_tab -->
		{% include 'product/tabs/main_shops_tab.tpl' %}
		<!-- end main_shops_tab -->

		<!-- main_categories_tab -->
		{% include 'product/tabs/main_categories_tab.tpl' %}
		<!-- end main_categories_tab -->

		<!-- main_connections_tab -->
		{% include 'product/tabs/main_connections_tab.tpl' %}
		<!-- end main_connections_tab -->

		<!-- main_attributes_tab -->
		{% include 'product/tabs/main_attributes_tab.tpl' %}
		<!-- end main_attributes_tab -->

		<!-- main_combos_tab -->
		{% include 'product/tabs/main_combos_tab.tpl' %}
		<!-- end main_combos_tab -->

		<!-- main_orders_tab -->
		{% include 'product/tabs/main_orders_tab.tpl' %}
		<!-- end main_orders_tab -->

		<!-- main_history_tab -->
		{% include 'product/tabs/main_history_tab.tpl' %}
		<!-- end main_history_tab -->
			{% if auth.user.super %}
			<!-- main_b2b_tab -->
		{% include 'product/tabs/main_b2b_tab.tpl' %}
			<!-- end main_history_tab -->
		{% endif %}

	</div>

	<!-- end row-->
{% endblock %}
{% block javascript %}
	{% include 'partials/tinymce.tpl' %}
	<!-- third party js -->
	<script src="/dist/assets/js/vendor/jquery.dataTables.min.js"></script>
	<script src="/dist/assets/js/vendor/dataTables.bootstrap4.js"></script>
	<script src="/dist/assets/js/vendor/dataTables.responsive.min.js"></script>
	<script src="/dist/assets/js/vendor/responsive.bootstrap4.min.js"></script>
	{% include 'product/partials/product_footer.tpl' %}
{% endblock %}
