{% extends "layouts/base.tpl" %}
{% block cssfiles_before %}{% endblock %}
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
				<h4 class="page-title">{{page_title}}</h4>
			</div>
		</div>
	</div>
	<!-- end page title -->
	<ul class="nav nav-pills bg-nav-pills nav-justified">
		<li class="nav-item">
			<a class="nav-link active" data-toggletab="main_information_tab" data-toggle="tab" href="#main_information_tab" role="tab">Hoofdinformatie</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggletab="main_categories_tab" data-toggle="tab" href="#main_categories_tab" role="tab">Categorieën</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggletab="main_generate_tab" data-toggle="tab" href="#main_generate_tab" role="tab">Geschikte universele artikelen</a>
		</li>
	</ul>
	<!-- Tab panels -->
	<div
		class="tab-content">
		<!-- main_information_tab -->
		{% include 'type/tabs/main_information_tab.tpl' %}
		<!-- end main_information_tab -->
		<!-- main_categories_tab -->
		{% include 'type/tabs/main_categories_tab.tpl' %}
		<!-- end main_categories_tab -->
		<!-- main_generate_tab -->
	{% include 'type/tabs/main_generate_tab.tpl' %}
		<!-- end main_generate_tab -->
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
		var type_id = {{ Type.id }};
		var brand_id = {{Type.product_brand_id}};
		  var cat="{{Type.categories_contents | json_encode}}";
  var app_categories = new Vue({
      el: "#categories_app",
      data: {
          lang:"{{ language }}",
          type_id:"{{ Type.id }}",
          get_url:"{{path_for('CategoriesType.GetAll')}}",
          update_url:"{{path_for('CategoriesType.UpdatePost')}}",
          categories_type:[],
          IMAGE_PATH:"{{ IMAGE_PATH }}"
      },
      mounted: function() {
          {% for key,categoriesContent in Type.categories_contents %}
          this.categories_type[{{key}}]={
              cat_id:'{{key}}',
              image: '{{getThumb(categoriesContent.image,"123bestdeal")}}',
              page_title:'{{ categoriesContent.page_title }}',
              page_sub_title:'{{ categoriesContent.page_sub_title }}',
              seo_description:'{{ categoriesContent.seo_description }}',
              page_top:`{{ categoriesContent.page_top | raw }}`,
              page_bottom:`{{ categoriesContent.page_bottom | raw }}`,
              seo_title:'{{ categoriesContent.seo_title }}',
          };
          {% endfor %}
      },
  });
	</script>
	<script src="/assets/js/pages/type_edit.js"></script>
	{% endblock %}
