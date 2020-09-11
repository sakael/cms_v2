{% extends "layouts/base.tpl" %}
{% block cssfiles %}{% endblock %}
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
					<div class="row mb-2">
                    <div class="col-12">
						<form role="form" method="GET" action="" id="barcodeform" class=" m-3" enctype="multipart/form-data">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group mb-3 {{ errors.barcode ? ' has-danger': '' }}">
										<label for="barcode">Barcode</label>
										<input type="text" id="barcode" class="form-control" name="barcode">
									</div>
								</div>
								<div class="col-md-6 text-center "><img id="mario" style="visibility: hidden;" src="/assets/audios/mario.gif" height="150">
									<img id="not_ok" class="" style="visibility: hidden;" src="/assets/audios/not_ok_logo.jpg" height="150"></div>
							</div>
						</form>
					</div>
				</div></div>
				<!-- end card-body-->
			</div>
			<!-- end card-->
		</div>
		<!-- end col -->
	</div>
	<!-- end row -->
{% endblock %}
{% block javascript %}
	<script src="/js/howler.min.js" type="text/javascript"></script>
	<script src="/assets/js/pages/barcode.js"></script>
{% endblock %}
