{% extends "layouts/base.tpl" %}

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
			<form role="form" method="POST" action="{{path_for('users.userPut')}}" class="form-horizontal m-3  needs-validation" novalidate autocomplete="off">
				<div class="row">
					<div class="col-md-12 mb-4">
						<div class="form-group">
							<button type="submit" class="btn btn-block btn-primary pull-left col-md-12">
								Bijwerken
							</button>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<div class="card mb-4">
							<div class="card-body">
								<input type="hidden" name="id" value="{{ user.id }}">
								<input type="hidden" name="_METHOD" value="PUT">
								<div class="form-group{{ errors.name ? ' has-danger': ''}}">
									<label for="name" class="label-material">Name</label>
									<input id="name" type="text" name="name" class="form-control" value="{%if(old.name)%}{{old.name}}{%else%}{{user.name}}{%endif%}" required>
									{% if errors.name %}
										<small class="form-control-feedback ml-2">{{errors.name |first}}</small>
									{% endif %}
								</div>
								<div class="form-group{{ errors.lastname ? ' has-danger': ''}}">
									<label for="lastname" class="label-material">Last Name</label>
									<input id="lastname" type="text" name="lastname" class="form-control" value="{% if(old.lastname) %}{{old.lastname}}{% else %}{{user.lastname}}{% endif %}" required>
									{% if errors.lastname %}
										<small class="form-control-feedback ml-2">{{errors.lastname |first}}</small>
									{% endif %}
								</div>
								<div class="form-group{{ errors.email ? ' has-danger': ''}}">
									<label for="email" class="label-material">Email</label>
									<input id="email" type="text" name="email" class="form-control" autocomplete="off" value="{% if(old.email) %}{{old.email}}{% else %}{{user.email}}{% endif %}" required>
									{% if errors.email %}
										<small class="form-control-feedback ml-2">{{errors.email |first}}</small>
									{% endif %}
								</div>
								<div class="form-group{{ errors.password ? ' has-danger': ''}}">
									<label for="password" class="label-material">Nieuw Wachtwoord</label>
									<input id="password" type="password" name="password" value="" class="form-control" autocomplete="new-password">
									{% if errors.password %}
										<small class="form-control-feedback ml-2">{{errors.password |first}}</small>
									{% endif %}
								</div>
								<div class="form-group{{ errors.super ? ' has-danger': ''}}">
									<label for="" class="label-material">Super</label><br>
									<input type="checkbox" id="super" value="1" {% if user.super==1 %} checked="checked" {% endif %} name="super" data-switch="bool"/><label for="super" data-on-label="Ja" data-off-label="nee"></label>
									{% if errors.super %}
										<small class="form-control-feedback ml-2">{{errors.super |first}}</small>
									{% endif %}
								</div>

							</div>
						</div>
					</div>
					<div class="col-7">
						<div class="card mb-4">
							<div class="card-body">
								<div class="row ">
									<div class="col col-xs-12">
										<div class="mt-2 mb-2">
											<div
												class="custom-control custom-checkbox custom-control-inline">
												<!-- Success Switch-->
												<input type="checkbox" id="standard" data-switch="success"/>
												<label for="standard" data-on-label="Ja" data-off-label="Nee"></label>
                                                <label class="ml-2 mr-2"> Standaard gebruiker</label>
											</div>
											<div
												class="custom-control custom-checkbox custom-control-inline">
												<!-- Success Switch-->
												<input type="checkbox" id="medior" data-switch="success"/>
												<label for="medior" data-on-label="Ja" data-off-label="Nee"></label>
												<label class="ml-2 mr-2"> Medior gebruiker</label>
											</div>
                                            <div
												class="custom-control custom-checkbox custom-control-inline">
												<!-- Success Switch-->
												<input type="checkbox" id="packaging" data-switch="success"/>
												<label for="packaging" data-on-label="Ja" data-off-label="Nee"></label>
												<label class="ml-2"> Verpakkingsgebruiker</label>
											</div>
										</div>
									</div>
									
								</div>
								<div class="row ">
									<div class="col-md-12">
										<table class="table user-permissions-table ">
											<thead>
												<tr>
													<th>Route naam</th>
													<th>Route url</th>
													<th>Toestaan</th>
												</tr>
											</thead>
											<tbody>
												{% for route in routes %}
													{% set TmpChecking = 'false' %}
													{% for route_user in routes_user %}
														{% if route.id == route_user.pivot_route_id %}
															{% set TmpChecking = 'true' %}
															<tr>
																<td>{{route.route_name}}</td>
																<td>{{route.route_name}}</td>
																<td>
																	<input type="checkbox" data-switch="bool" id="route_id[{{route.id}}][allow]" value="1" {% if route_user.pivot_allow==1 %} checked="checked" {% endif %} name="route_id[{{route.id}}][allow]" data-user-standard="{{route.standard}}" data-user-packaging="{{route.packaging}}" data-user-medior="{{route.medior}}"/><label for="route_id[{{route.id}}][allow]" data-on-label="Ja" data-off-label="nee"></label>
																</td>
																<input type="hidden" value="{{route.route_name}}" name="route_id[{{route.id}}][route_name]">
															</tr>
														{% endif %}
													{% endfor %}
													{% if TmpChecking == 'false'%}
														<tr>
															<td>{{route.route_name}}</td>
															<td>{{route.route_name}}</td>
															<td>
																<input type="checkbox" data-switch="bool" id="route_id[{{route.id}}][allow]" value="1" name="route_id[{{route.id}}][allow]" data-user-standard="{{route.standard}}" data-user-packaging="{{route.packaging}}" data-user-medior="{{route.medior}}"/><label for="route_id[{{route.id}}][allow]" data-on-label="Ja" data-off-label="nee"></label>
															</td>
															<input type="hidden" value="{{route.route_name}}" name="route_id[{{route.id}}][route_name]">
														</tr>
													{% endif %}
												{% endfor %}
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 mt-0">
						<div class="form-group">
							<button type="submit" class="btn btn-block btn-primary pull-left col-md-12">
								Bijwerken
							</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
{% endblock %}
{% block javascript %}
	<script type="text/javascript">
		$(document).ready(function () {
$("#standard").change(function () {
$('#medior').prop("checked", false);
$('#packaging').prop("checked", false);

$("[data-user-packaging]").each(function () {
if ($(this).data('user-packaging') == 1) {
$(this).prop("checked", false);
}
});
$("[data-user-medior]").each(function () {
if ($(this).data('user-medior') == 1) {
$(this).prop("checked", false);
}
});

if (this.checked) {
$("[data-user-standard]").each(function () {
if ($(this).data('user-standard') == 1) {
$(this).prop("checked", true);
}
});
} else {
$("[data-user-standard]").each(function () {
if ($(this).data('user-standard') == 1) {
$(this).prop("checked", false);
}
});
}
});
$("#packaging").change(function () {
$('#medior').prop("checked", false);
$('#standard').prop("checked", false);

$("[data-user-standard]").each(function () {
if ($(this).data('user-standard') == 1) {
$(this).prop("checked", false);
}
});
$("[data-user-medior]").each(function () {
if ($(this).data('user-medior') == 1) {
$(this).prop("checked", false);
}
});
if (this.checked) {
$("[data-user-packaging]").each(function () {
if ($(this).data('user-packaging') == 1) {
$(this).prop("checked", true);
}
});
} else {
$("[data-user-packaging]").each(function () {
if ($(this).data('user-packaging') == 1) {
$(this).prop("checked", false);
}
});
}
});
$("#medior").change(function () {
$('#standard').prop("checked", false);
$('#packaging').prop("checked", false);

$("[data-user-packaging]").each(function () {
if ($(this).data('user-packaging') == 1) {
$(this).prop("checked", false);
}
});
$("[data-user-standard]").each(function () {
if ($(this).data('user-standard') == 1) {
$(this).prop("checked", false);
}
});


if (this.checked) {
$("[data-user-medior]").each(function () {
if ($(this).data('user-medior') == 1) {
$(this).prop("checked", true);
}
});
} else {
$("[data-user-medior]").each(function () {
if ($(this).data('user-medior') == 1) {
$(this).prop("checked", false);
}
});
}
});
});
	</script>
{% endblock %}
