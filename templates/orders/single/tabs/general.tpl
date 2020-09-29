<!-- Tab main_types_tab -->
<div class="tab-pane fade active show mt-3" id="general" role="tabpanel">
		<div id="orderRows">
			<order-rows-component  @row-updated="rows=$event" :rows="rows" :products="products" :order_id="order_id" :shop_id="shop_id" :IMAGE_PATH="IMAGE_PATH" :url="url"></order-rows-component>
		</div>
	</form>
	<div class="row equal">
		<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12  order-1 d-flex">
			<div class="card">
				<div class="card-body">
					<h4 class="header-title mb-4">Order overzicht</h4>
					<p class="mb-2">
						<i class="fa fa-euro"></i>
						<span class="total-amount">{{order.gross_price}}</span>
						(
						<i class="fa fa-euro"></i>
						<span class="total-amount-ex">{{order.net_price}}</span>
						ex. BTW)
						{% if order.transaction_id and  order.transaction_id!='' %}
							<a href="" class="msp-control" data-transaction-id="{{order.transaction_id}}">
								(controleer)</a>
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
		<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 order-2 d-flex">
			<div class="card">
				<div class="card-body ">
					<h4 class="header-title mb-1 text-center">Status</h4>
					<div class="form-group row mb-3 {{ errors.order_status ? ' has-danger': '' }} pt-3">
						<label for="order_status" class="col-3 col-form-label">Status</label>
						<div class="col-9">
							<select name="order_status" class="form-control" id="order_status">
								{% for status in orderStatus %}
									<option value="{{ status.id }}" {% if status.id==Order.status_id %} selected="selected" {% endif %}>{{ status.title }}</option>
								{% endfor %}
							</select>
							{% if errors.order_status %}
								<small class="form-control-feedback ml-2">{{ errors.order_status |first }}</small>
							{% endif %}
						</div>
					</div>
					<div class="form-group row mb-3 justify-content-end">
						<div class="col-9">
							<div class="custom-control custom-checkbox col-12">
								<input ame="inform_user" id="inform_user" alue="1" type="checkbox" class="custom-control-input" id="order_status">
								<label class="custom-control-label" for="order_status">klant informeren over wijziging</label>
							</div>
						</div>
					</div>
					<div class="form-group mb-0 justify-content-end row">
						<div class="col-9">
							<button type="submit" class="btn btn-info font-14 btn-sm btn-block {% if ( not user.checkPermissionByRouteName('OrdersOrderStatusPostUpdateManually') or not auth.user.super ) %} disabled {% endif %}" {% if ( not user.checkPermissionByRouteName('OrdersOrderStatusPostUpdateManually') or not auth.user.super ) %} disabled {% endif %} id="StatusChanges">Status opslaan</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-2 col-lg-6 col-md-6 order-3 d-flex">
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
		<div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 order-4">
			<div class="card" style="min-height:266px;">
				<div class="card-body">
					<div class="text-center pt-3">
						<div class="current-status-info">
							<button type="button" data-toggle="tooltip" data-placement="top" title="" data-original-title="HUIDIGE STATUS" class="btn disabled  {% if order.status_id ==1 %} btn-primary {% elseif order.status_id ==3 %} btn-success {% elseif order.status_id ==10 %} btn-info {% else %} btn-warning {% endif %} btn-block mt-2 mb-3">{{ attribute(orderStatus, order.status_id).title }} <br> ( {{ user.getUserNameById(order.user_id) | capitalize }} ) <br> {{order.updated_at}}</button>
						</div>
						<button type="button" class="btn btn-secondary btn-block mt-3 mb-2" id="duplicate">
							<i class="dripicons-duplicate mr-1"></i>
							<span>Dupliceren</span>
						</button>
					</div>
				</div>
			</div>
		</div><!-- end col -->
	</div>
	<!-- end row -->
	<div class="row">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
			<div id="app_notes">
				<notes-app-component @row-updated="notes=$event" :notes="notes" :user_id="user_id" :users="users" :order_id="order_id" :errors="errors" :url="url"></notes-app-component>
			</div>
		</div>
	</div>
	<div id="msp-status-popup" id="full-width-modal" class="modal fade model-fullwidth" tabindex="-1" role="dialog" aria-labelledby="info-header-modalLabel" aria-hidden="true">
		<div class="modal-dialog modal-full-width">
			<div class="modal-content">
				<div class="modal-header modal-colored-header bg-info">
					<h4 class="modal-title" id="info-header-modalLabel">Betaling van
						{{order.id}}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light" data-dismiss="modal">Sluiten</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
</div>
<!-- Tab main_generate_tab -->
