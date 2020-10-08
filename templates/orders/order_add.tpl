{% extends "layouts/base.tpl" %}
{% block cssfiles_before %}
	<!-- third party css -->

{% endblock %}
{% block cssfiles %}{% endblock %}
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
							<a href="{{path_for('home')}}">Home</a>
						</li>
						<li class="breadcrumb-item">
							<a href="{{path_for('OrdersIndex')}}">Orders</a>
						</li>
						<li class="breadcrumb-item active">{{page_title}}</li>
					</ol>
				</div>
				<h4 class="page-title">{{page_title}}</h4>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-body">
					<form method="post" action="{{path_for('Orders.PostNew')}}">
						<div class="row p-3">
							<div class="col">
								<div class="form-group row pt-3 pb-2">
									<label for="order_status" class=" col-sm-4 col-md-2 col-form-label pt-2">Shops:</label>
									<div class="col-sm-8 col-md-7 col-form-label text-center">
										<select name="shop" class="form-control form-control-sm " id="shop">
											{% for shop in shops %}
												<option value="{{shop.id}}">{{shop.domain}}</option>
											{% endfor %}
										</select>
									</div>
									<div class="col-sm-12 col-md-3 col-form-label text-center">
										<button class="btn btn-sm btn-success col-md-12" id="StatusChanges">Doorgaan</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>

{% endblock %}
{% block javascript %}{% endblock %}
