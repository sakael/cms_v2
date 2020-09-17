{% extends "layouts/base.tpl" %}

{% block cssfiles_before %}
<!-- third party css -->
<link href="/dist/assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
<link href="/dist/assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
<!-- third party css end -->
{% endblock %}

{% block page_title %}{{page_title}}{% endblock %}

{% block content %}

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ path_for('ProductsIndex') }}">Alle artikelen</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{ product.sku }}</a></li>
                    <li class="breadcrumb-item active">Product Details</li>
                </ol>
            </div>
            <h4 class="page-title">Product Details</h4>
        </div>
    </div>
</div>
<!-- end page title -->

{% endblock %}
