{% extends "layouts/base.tpl" %}
{% block cssfiles_before %}{% endblock %}
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
	<!-- end page title -->

	<div class="row justify-content-center">
		<div class="col-lg-7 col-md-10 col-sm-11">

			<div class="horizontal-steps mt-3 mb-3 pb-3">
				<div class="horizontal-steps-content">
					<div class="step-item {% if order.status_id == 1 %}current{% endif %}">
						<span data-toggle="tooltip" data-placement="bottom" title="" data-original-title="{{order.created_at}}">Bestelling geplaatst</span>
					</div>
					{% for change in allOrderChanges %}
						<div class="step-item {% if order.status_id == change.status_id_after %}current{% endif %}">
							<span data-toggle="tooltip" data-placement="bottom" title="" data-html="true" data-original-title="{{change.created_at}} <br>( {{users[change.user_id].name}} {{users[change.user_id].lastname}} )">{{orderStatus[change.status_id_after].title}}</span>
						</div>
					{% endfor %}
				</div>
				<div class="process-line" style="{% if order.status_id == 3 %} width: 100%; {% elseif order.status_id == 15 %} width: 50%; {% else %}width: 0%; {% endif %}"></div>
			</div>
		</div>
	</div>
	<!-- end row -->


	<div class="row equal">
		<div class="col-lg-8 d-flex">
			<div class="card card-block">
				<div class="card-body">
					<h4 class="header-title mb-3">ITEMS VAN BESTELLING
						{{order.id}}</h4>
					<div class="table-responsive">
						<table class="table mb-0">
							<thead class="thead-light">
								<tr>
									<th>Item</th>
									<th>SKU</th>
									<th>Aantal</th>
									<th>Prijs</th>
									<th>Totaal</th>
								</tr>
							</thead>
							<tbody>
								{% for product in order.order_items %}
									<tr>
										<td>
											{% if product.product_id != 99999999 %}
												<div class="item-image">
													<img src="{{ IMAGE_PATH }}/{{getThumb(product.product.images[0].url,'123bestdeal')}}" alt="contact-img" title="contact-img" class="rounded" height="48">
												</div>
											{% else %}
												<div class="item-image">
												</div>
											{% endif %}
											<p class="m-0 d-inline-block align-middle font-14">
												<a href="{{ path_for('ProductGet',{'id':product.product_id })}}" class="text-info">{{product.product_name}}</a>
												{% for attribute in product.attributes %}
												| {{attribute.title}}
												{% endfor %}
											</p>

										</td>
										<td class="pt-3">{{product.product.sku}}</td>
										<td class="pt-3">{{product.count}}</td>
										<td class="pt-3">€
											{{product.price}}</td>
										<td class="pt-3">€
											{{product.totalprice}}</td>
									</tr>
								{% endfor %}
							</tbody>
						</table>
					</div>
					<!-- end table-responsive -->

				</div>
			</div>
		</div>
		<!-- end col -->

		<div class="col-lg-4 d-flex">
			<div class="card card-block">
				<div class="card-body">
					<h4 class="header-title mb-3">Overzicht van de bestelling</h4>
					<div class="table-responsive">
						<table class="table mb-0">
							<thead class="thead-light">
								<tr>
									<th>Omschrijving</th>
									<th>Price</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Netto :</td>
									<td>€
										{{order.net_price - order.shipping_cost}}</td>
								</tr>
								<tr>
									<td>Verzendkosten :</td>
									<td>€
										{{order.shipping_cost}}</td>
								</tr>
								<tr>
									<td>Geschatte belasting :</td>
									<td>€
										{{order.gross_price - order.net_price}}</td>
								</tr>
								<tr>
									<th>Totaal :</th>
									<th>€
										{{order.gross_price}}</th>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- end table-responsive -->

				</div>
			</div>
		</div>
		<!-- end col -->
	</div>
	<!-- end row -->


	<div class="row equal">

		<div class="col-lg-4 order-1 d-flex">
			<div class="card">
				<div class="card-body">
					<h4 class="header-title mb-3">Order overzicht</h4>
					<p class="mb-2">
						<i class="fa fa-euro"></i>
						<span class="total-amount">{{order.gross_price}}</span>
						(
						<i class="fa fa-euro"></i>
						<span class="total-amount-ex">{{order.net_price}}</span>
						ex. BTW)
						{% if order.transaction_id and  order.transaction_id!='' %}
							<a  href="" class="msp-control" data-transaction-id="{{order.transaction_id}}"> (controleer)</a>
						{% endif %}
					</p>
					<p class="mb-1">
						<a href="#" onclick="askForPayment('{{order.id}}');">Betaalverzoek</a>
					</p>
					<p class="mb-1">
						<a class="ls-modal" href="{{path_for('OrderGetInvoice',{'id':order.id})}}" target="_blank">
							{% if order.invoicenr %}Factuur{% else %}Proforma
							{% endif %}
							downloaden</a>
					</p>

					{% if order.ispaid==1 %}
						<p class="mb-1">
							Voldaan via
							{{ order.payment.type | capitalize }}
						</p>
					{% else %}
						<p class="mb-1">
							<span class="text-danger">Order is nog niet voldaan</span>
						</p>
						{% if (user.checkPermissionByRouteName('Orders.RegisterPayment')) %}
							<p class="mb-1">
								<a href="#" onclick="forePayment('{{order.id}}');">Registreer betaling</a>
							</p>
						{% endif %}
					{% endif %}
					{% if order.clinetsOrders | length > 0%}
						<p class="mb-0">Klant oude bestellingen
							<u>
								<a href="#old_orders" id="a_old_orders" data-toggle="tab">(<b>
										{{ order.clinetsOrders | length }}
									</b>)</a>
							</u>
						</p>
					{% endif %}
					{% if order.transaction_id %}
						<p class="mb-1">Betalings identifier:
							<span class="copy_class font-weight-bold" data-copy-text="{{order.id}}">{{ order.transaction_id }}</span>
							<input type="hidden" value="{{ order.transaction_id }}" id="{{order.id}}" style="border: none;height: 0;padding: 0;"/>
						</p>
					{% endif %}

					{% for note in order.notes %}
						{% if 'Gedupliceerd' in  note.note%}
							<p class="mb-1">{{note.note |replace({"\n":'',"\r":''}) |raw }}
								({{note.created_at}})</p>
						{% endif %}
					{% endfor %}

				</div>
			</div>
		</div>
		<!-- end col -->
		<div class="col-lg-3 order-2 d-flex">
			<div class="card">
				<div class="card-body">
					<h4 class="header-title mb-3">factuuradres</h4>
					<h5>{{ order.order_details.address.payment.firstname }}
						{{ order.order_details.address.payment.lastname }}</h5>
					<address class="mb-0 font-14 address-lg">
						{{ order.order_details.address.payment.street }}
						{{ order.order_details.address.payment.houseNumber }}
						{{ order.order_details.address.payment.houseNumberSupplement }}<br>
						{{ order.order_details.address.payment.zipcode }},
						{{ order.order_details.address.payment.city }}
						{{ order.order_details.address.payment.countryCode }}<br>
						<abbr title="Phone">P:
						</abbr>
						{{ order.order_details.customerPhone }}
						<br/>
						<abbr title="Email">E:
						</abbr>
						{{ order.order_details.customerEmail }}
					</address>

				</div>
			</div>
		</div>
		<!-- end col -->
		<div class="col-lg-3 order-2 d-flex">
			<div class="card">
				<div class="card-body">
					<h4 class="header-title mb-3">Verzendingsadres</h4>
					<h5>{{ order.order_details.address.shipping.firstname }}
						{{ order.order_details.address.shipping.lastname }}</h5>
					<address class="mb-0 font-14 address-lg">
						{{ order.order_details.address.shipping.street }}
						{{ order.order_details.address.shipping.houseNumber }}
						{{ order.order_details.address.shipping.houseNumberSupplement }}<br>
						{{ order.order_details.address.shipping.zipcode }},
						{{ order.order_details.address.shipping.city }}
						{{ order.order_details.address.shipping.countryCode }}<br>
						<abbr title="Phone">P:
						</abbr>
						{{ order.order_details.customerPhone }}
						<br/>
						<abbr title="Email">E:
						</abbr>
						{{ order.order_details.customerEmail }}
					</address>

				</div>
			</div>
		</div>
		<!-- end col -->

		<div class="col-lg-2 order-4 d-flex">
			<div class="card">
				<div class="card-body">
					<h4 class="header-title mb-3 text-center">Levering Info</h4>
					<div class="text-center">
						<i class="mdi mdi-truck-fast h2 text-muted"></i>
						{% if order.status_id == 3 %}
							{% if (order.dhl) %}
								<h5>
									<b>DHL</b>
								</h5>
								<p class="mb-1">
									<b>Barcode :</b>
									<a href="https://www.dhlparcel.nl/nl/volg-uw-zending?tc= {{order.dhl.barcode}} &pc={{order.order_details.address.shipping.zipcode}}" target="_blank">{{order.dhl.barcode}}</a>
								</p>
							{% elseif order.shipping_cost == 1.99 or order.shipping_cost == 0 %}
								<h5>
									<b>POST</b>
								</h5>
								<p class="mb-1">
									<b>Barcode :</b>
									{{order.id}}
								</p>
							{% elseif order.shipping_cost == 3.99 %}
								<h5>
									<b>Parcel (Snelle levering)</b>
								</h5>
								<p class="mb-1">
									<b>Barcode :</b>
									{{order.id}}
								</p>
							{% endif %}

						{% endif %}
					</div>
				</div>
			</div>
		</div>
		<!-- end col -->
	</div>
	<!-- end row -->
	<div class="row">
		
		<div class="col-10">
			<div id="app_notes">
				<notes-app-component @row-updated="notes=$event" :notes="notes" :user_id="user_id" :users="users" :order_id="order_id" :errors="errors" :url="url"></notes-app-component>
			</div>
		</div>
		<div class="col-2">
			<div class="card" style="min-height:266px;">
				<div class="card-body">
					<div class="text-center">
						<button type="button" data-toggle="tooltip" data-placement="top" title="" data-original-title="HUIDIGE STATUS" class="btn disabled  {% if order.status_id ==1 %} btn-primary {% elseif order.status_id ==3 %} btn-success {% elseif order.status_id ==10 %} btn-info {% else %} btn-warning {% endif %} btn-block mt-2 mb-3">{{ attribute(orderStatus, order.status_id).title }} <br> ( {{ user.getUserNameById(order.user_id) | capitalize }} ) <br> {{order.updated_at}}</button>
						<button type="button" class="btn btn-secondary btn-block mt-2 mb-3" id="duplicate"><i class="dripicons-duplicate mr-1"></i> <span>Dupliceren</span> </button>
						<a class="btn btn-danger btn-block mt-2 {% if not user.checkPermissionByRouteName('OrdersGetSingleEdit') or not auth.user.super %} disabled {% endif %}" href="{{path_for('OrdersGetSingleEdit',{'id':order.id})}}"><i class="dripicons-document-edit mr-1"></i> <span>Bewerken</span> </a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="msp-status-popup" class="modal fade model-fullwidth" tabindex="-1" role="dialog" aria-labelledby="info-header-modalLabel" aria-hidden="true">
		<div class="modal-dialog modal-full-width modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header modal-colored-header bg-info">
					<h4 class="modal-title" id="info-header-modalLabel">Betaling van {{order.id}}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light" data-dismiss="modal">Sluiten</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

{% endblock %}
{% block javascript %}
	<script type="text/javascript">
		var orderId = {{ order.id }};
		var paymentRegistrationLink = '{{ SITE_URL }}';
	</script>
	<script src="/assets/js/pages/orders/single/index.js"></script>
	<!-- specific page js file -->
	<script type="text/javascript">
		var orderId = {{ order.id }};
			new Vue({
			el: '#app_notes',
			data: {
			notes: [],
			user_id: '{{ auth.user.id }}',
			users: [],
			order_id: '{{ order.id }}',
			errors: null,
			url: '{{ path_for('notes.note.add') }}'
			},
			mounted: function (){
				{% for note in order.notes %}
				var note = '{{ note.note |replace({"\n":'', "\r":''}) |raw }}';
				this.notes.push({
				message: note,
				user_id: '{{ note.user_from_name }}',
				user_id_to: '{{ note.user_to.name }}',
				order_id: '{{ note.order_id }}',
				created_at: '{{ note.created_at }}',
				updated_at: '{{ note.updated_at }}'
				});{% endfor %}

				{% for user in users %}
				this.users.push({id: '{{ user.id }}', name: '{{ user.name }}'});{% endfor %}
			}
			});
	</script>
{% endblock %}

