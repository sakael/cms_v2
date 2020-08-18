<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>123BestDeal CMS |
			{{page_title}}
			{% block page_title %}{% endblock %}
		</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta
		content="" name="description"/>
		<!-- App favicon -->
		<link
		rel="shortcut icon" href="/assets/images/favicon.ico"/>
		<!-- third party css -->
		<link
		href="/dist/assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css"/>
		<!-- third party css end -->
		{% block cssfiles_before %}{% endblock %}
		<!-- App css -->
		<link href="/dist/assets/css/icons.min.css" rel="stylesheet" type="text/css"/>
		<link href="/dist/assets/css/app.min.css" rel="stylesheet" type="text/css" id="light-style"/>
		<link href="/dist/assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style"/>
		<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/> {% block cssfiles %}{% endblock %}
		</head>
		<body
			class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
			<!-- Begin page -->
			<div
				class="wrapper">
				<!-- ========== Left Sidebar Start ========== -->
				{% include 'partials/leftsidebar.tpl' %}
				<!-- Left Sidebar End -->

				<!-- ============================================================== -->
				<!-- Start Page Content here -->
				<!-- ============================================================== -->

					<div class="content-page"> <div
						class="content">
						<!-- Topbar Start -->
						{% include 'partials/topbar.tpl' %}
						<!-- end Topbar -->

						<!-- Start Content-->
							<div class="container-fluid"> {% include 'partials/flash.tpl' %}
							{% block content %}{% endblock %}
						</div>
						<!-- container -->
					</div>
					<!-- content -->

					<!-- Footer Start -->
					{% include 'partials/footer.tpl' %}
					<!-- end Footer -->
				</div>

				<!-- ============================================================== -->
			<!-- End Page content -->
				<!-- ============================================================== -->
			</div>
			<!-- END wrapper -->


			<!-- bundle -->
			<script src="/dist/assets/js/vendor.min.js"></script>
			<script src="/dist/assets/js/app.min.js"></script>
			<!-- third party js -->
			<script src="/dist/assets/js/vendor/apexcharts.min.js"></script>
			<script src="/dist/assets/js/vendor/jquery-jvectormap-1.2.2.min.js"></script>
			<script src="/dist/assets/js/vendor/jquery-jvectormap-world-mill-en.js"></script>
			<!-- third party js ends -->

			<!-- end demo js-->
			<!-- custom --><script src="/dist/assets/js/custom.min.js"> </script>
			<!-- end custom js-->
			{% block javascript %}{% endblock %}
		</body>
	</html>
