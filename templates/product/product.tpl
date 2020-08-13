{% extends "layouts/base.tpl" %}

{% block cssfiles %}
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
            <h4 class="page-title">{{page_title}}</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                    page contents

            </div>
        </div>
    </div>
</div>

{% endblock %}
