<!-- Tab main_types_tab -->
<div class="tab-pane fade mt-3" id="customer" role="tabpanel">
	<form action="{{ path_for('OrdersPostUpdateSingle') }}" method="post" class="needs-validation" novalidate id="order-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<input type="hidden" name="id" value="{{ order.id }}">
						<input type="hidden" name="_METHOD" value="PUT">
						<div class="row">
							<div class="form-group col-md-6 {{ errors.customer_phone ? ' has-danger': '' }}">
								<label for="customer_phone">Telefoon</label>
								<input type="text" class="form-control form-control-sm" id="customer_phone" name="customer_phone" placeholder="Telefoon" value="{% if(old.customer_phone) %}{{ old.customer_phone }}{% else %}{{ order.order_details.customerPhone }}{% endif %}" >
								{% if errors.customer_phone %}
									<small class="form-control-feedback ml-2">{{ errors.customer_phone |first }}</small>
								{% endif %}
							</div>
							<div class="form-group col-md-6 {{ errors.customer_email ? ' has-danger': '' }}">
								<label for="customer_email">Email</label>
								<input type="text" class="form-control form-control-sm" id="customer_email" name="customer_email" placeholder="Email" value="{% if(old.customer_email) %}{{ old.customer_email }}{% else %}{{ order.order_details.customerEmail }}{% endif %}" required>
								{% if errors.customer_email %}
									<small class="form-control-feedback ml-2">{{ errors.customer_email |first }}</small>
								{% endif %}
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="header-title mb-4">Betaal Adres</h4>
						<div class="row">
							<div class="form-group col-md-4  {{ errors.payment_firstname ? ' has-danger': '' }}">
								<label for="payment_firstname">Voornaam</label>
								<input type="text" class="form-control form-control-sm" id="payment_firstname" name="payment_firstname" placeholder="Naam" value="{% if(old.name) %}{{ old.payment_firstname }}{% else %}{{ order.order_details.address.payment.firstname }}{% endif %}" required>
								{% if errors.payment_firstname %}
									<small class="form-control-feedback ml-2">{{ errors.payment_firstname |first }}</small>
								{% endif %}
							</div>
							<div class="form-group col-md-5  {{ errors.payment_lastname ? ' has-danger': '' }}">
								<label for="payment_lastname">Achternaam</label>
								<input type="text" class="form-control form-control-sm" id="payment_lastname" name="payment_lastname" placeholder="Achternaam" value="{% if(old.payment_lastname) %}{{ old.payment_lastname }}{% else %}{{ order.order_details.address.payment.lastname }}{% endif %}" required>
								{% if errors.payment_lastname %}
									<small class="form-control-feedback ml-2">{{ errors.payment_lastname |first }}</small>
								{% endif %}
							</div>
							<div class="form-group col-md-3 {{ errors.payment_gender ? ' has-danger': '' }}">
								<label for="payment_gender">Geslacht</label>
								<select class="form-control form-control-sm" name="payment_gender" id="payment_gender">
									<option value="M" {% if old.payment_gender=='M' %} selected {% elseif order.order_details.address.payment.gender=='M' %} selected {% endif %}>M</option>
									<option value="F" {% if old.payment_gender=='F' %} selected {% elseif order.order_details.address.payment.gender=='F' %} selected {% endif %}>V</option>
								</select>
								{% if errors.payment_gender %}
									<small class="form-control-feedback ml-2">{{ errors.payment_gender |first }}</small>
								{% endif %}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6 {{ errors.payment_street ? ' has-danger': '' }}">
								<label for="payment_street">Straat</label>
								<input type="text" class="form-control form-control-sm" id="payment_street" name="payment_street" placeholder="Straat" value="{% if(old.payment_street) %}{{ old.payment_street }}{% else %}{{ order.order_details.address.payment.street }}{% endif %}" required>
								{% if errors.payment_street %}
									<small class="form-control-feedback ml-2">{{ errors.payment_street |first }}</small>
								{% endif %}
							</div>
							<div class="form-group col-md-3 {{ errors.payment_houseNumber ? ' has-danger': '' }}">
								<label for="payment_houseNumber">Huisnummer</label>
								<input type="text" class="form-control form-control-sm" id="payment_houseNumber" name="payment_houseNumber" placeholder="Huisnummer" value="{% if(old.payment_houseNumber) %}{{ old.payment_houseNumber }}{% else %}{{ order.order_details.address.payment.houseNumber }}{% endif %}" required>
								{% if errors.payment_houseNumber %}
									<small class="form-control-feedback ml-2">{{ errors.payment_houseNumber |first }}</small>
								{% endif %}
							</div>
							<div class="form-group col-md-3 {{ errors.payment_houseNumberSupplement ? ' has-danger': '' }}">
								<label for="payment_houseNumberSupplement">Aanvulling</label>
								<input type="text" class="form-control form-control-sm" id="payment_houseNumberSupplement" name="payment_houseNumberSupplement" placeholder="Huis nummer aanvulling" value="{% if(old.payment_houseNumberSupplement) %}{{ old.payment_houseNumberSupplement }}{% else %}{{ order.order_details.address.payment.houseNumberSupplement }}{% endif %}">
								{% if errors.payment_houseNumberSupplement %}
									<small class="form-control-feedback ml-2">{{ errors.payment_houseNumberSupplement |first }}</small>
								{% endif %}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-3 {{ errors.payment_zipcode ? ' has-danger': '' }}">
								<label for="payment_zipcode">Postcode</label>
								<input type="text" class="form-control form-control-sm" id="payment_zipcode" name="payment_zipcode" placeholder="Postcode" value="{% if(old.payment_zipcode) %}{{ old.payment_zipcode }}{% else %}{{ order.order_details.address.payment.zipcode }}{% endif %}" required>
								{% if errors.payment_zipcode %}
									<small class="form-control-feedback ml-2">{{ errors.payment_zipcode |first }}</small>
								{% endif %}
							</div>
							<div class="form-group  col-lg-6 {{ errors.payment_city ? ' has-danger': '' }}">
								<label for="payment_city">Plaats</label>
								<input type="text" class="form-control form-control-sm" id="payment_city" name="payment_city" placeholder="Plaats" value="{% if(old.payment_city) %}{{ old.payment_city }}{% else %}{{ order.order_details.address.payment.city }}{% endif %}" required>
								{% if errors.payment_city %}
									<small class="form-control-feedback ml-2">{{ errors.payment_city |first }}</small>
								{% endif %}
							</div>
							<div class="form-group  col-md-3 {{ errors.payment_countryCode ? ' has-danger': '' }}">
								<label for="payment_countryCode">Landcode</label>
								<input type="text" class="form-control form-control-sm" id="payment_countryCode" name="payment_countryCode" placeholder="Landcode" value="{% if(old.payment_countryCode) %}{{ old.payment_countryCode }}{% else %}{{ order.order_details.address.payment.countryCode }}{% endif %}" required>
								{% if errors.payment_countryCode %}
									<small class="form-control-feedback ml-2">{{ errors.payment_countryCode |first }}</small>
								{% endif %}
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="header-title mb-4">Verzending Adres</h4>
						<div class="row">
							<div class="form-group col-md-4  {{ errors.shipping_firstname ? ' has-danger': '' }}">
								<label for="shipping_firstname">Voornaam</label>
								<input type="text" class="form-control form-control-sm" id="shipping_firstname" name="shipping_firstname" placeholder="Naam" value="{% if(old.name) %}{{ old.shipping_firstname }}{% else %}{{ order.order_details.address.shipping.firstname }}{% endif %}">
								{% if errors.shipping_firstname %}
									<small class="form-control-feedback ml-2">{{ errors.shipping_firstname |first }}</small>
								{% endif %}
							</div>
							<div class="form-group col-md-5  {{ errors.shipping_lastname ? ' has-danger': '' }}">
								<label for="shipping_lastname">Achternaam</label>
								<input type="text" class="form-control form-control-sm" id="shipping_lastname" name="shipping_lastname" placeholder="Achternaam" value="{% if(old.shipping_lastname) %}{{ old.shipping_lastname }}{% else %}{{ order.order_details.address.shipping.lastname }}{% endif %}">
								{% if errors.shipping_lastname %}
									<small class="form-control-feedback ml-2">{{ errors.shipping_lastname |first }}</small>
								{% endif %}
							</div>
							<div class="form-group col-md-3 {{ errors.shipping_gender ? ' has-danger': '' }}">
								<label for="shipping_gender">Geslacht</label>
								<select class="form-control form-control-sm" name="shipping_gender" id="shipping_gender">
									<option value="M" {% if old.shipping_gender=='M' %} selected {% elseif order.order_details.address.shipping.gender=='M' %} selected {% endif %}>M</option>
									<option value="F" {% if old.shipping_gender=='F' %} selected {% elseif order.order_details.address.shipping.gender=='F' %} selected {% endif %}>V</option>
								</select>
								{% if errors.shipping_gender %}
									<small class="form-control-feedback ml-2">{{ errors.shipping_gender |first }}</small>
								{% endif %}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6 {{ errors.shipping_street ? ' has-danger': '' }}">
								<label for="shipping_street">Straat</label>
								<input type="text" class="form-control form-control-sm" id="shipping_street" name="shipping_street" placeholder="Straat" value="{% if(old.shipping_street) %}{{ old.shipping_street }}{% else %}{{ order.order_details.address.shipping.street }}{% endif %}">
								{% if errors.shipping_street %}
									<small class="form-control-feedback ml-2">{{ errors.shipping_street |first }}</small>
								{% endif %}
							</div>
							<div class="form-group col-md-3 {{ errors.shipping_houseNumber ? ' has-danger': '' }}">
								<label for="shipping_houseNumber">Huisnummer</label>
								<input type="text" class="form-control form-control-sm" id="shipping_houseNumber" name="shipping_houseNumber" placeholder="Huisnummer" value="{% if(old.shipping_houseNumber) %}{{ old.shipping_houseNumber }}{% else %}{{ order.order_details.address.shipping.houseNumber }}{% endif %}">
								{% if errors.shipping_houseNumber %}
									<small class="form-control-feedback ml-2">{{ errors.shipping_houseNumber |first }}</small>
								{% endif %}
							</div>
							<div class="form-group col-md-3 {{ errors.shipping_houseNumberSupplement ? ' has-danger': '' }}">
								<label for="shipping_houseNumberSupplement">Aanvulling</label>
								<input type="text" class="form-control form-control-sm" id="shipping_houseNumberSupplement" name="shipping_houseNumberSupplement" placeholder="Huis nummer aanvulling" value="{% if(old.shipping_houseNumberSupplement) %}{{ old.shipping_houseNumberSupplement }}{% else %}{{ order.order_details.address.shipping.houseNumberSupplement }}{% endif %}">
								{% if errors.shipping_houseNumberSupplement %}
									<small class="form-control-feedback ml-2">{{ errors.shipping_houseNumberSupplement |first }}</small>
								{% endif %}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-3 {{ errors.shipping_zipcode ? ' has-danger': '' }}">
								<label for="shipping_zipcode">Postcode</label>
								<input type="text" class="form-control form-control-sm" id="shipping_zipcode" name="shipping_zipcode" placeholder="Postcode" value="{% if(old.shipping_zipcode) %}{{ old.shipping_zipcode }}{% else %}{{ order.order_details.address.shipping.zipcode }}{% endif %}">
								{% if errors.shipping_zipcode %}
									<small class="form-control-feedback ml-2">{{ errors.shipping_zipcode |first }}</small>
								{% endif %}
							</div>
							<div class="form-group  col-lg-6 {{ errors.shipping_city ? ' has-danger': '' }}">
								<label for="shipping_city">Plaats</label>
								<input type="text" class="form-control form-control-sm" id="shipping_city" name="shipping_city" placeholder="Plaats" value="{% if(old.shipping_city) %}{{ old.shipping_city }}{% else %}{{ order.order_details.address.shipping.city }}{% endif %}">
								{% if errors.shipping_city %}
									<small class="form-control-feedback ml-2">{{ errors.shipping_city |first }}</small>
								{% endif %}
							</div>
							<div class="form-group  col-md-3 {{ errors.shipping_countryCode ? ' has-danger': '' }}">
								<label for="shipping_countryCode">Landcode</label>
								<input type="text" class="form-control form-control-sm" id="shipping_countryCode" name="shipping_countryCode" placeholder="Landcode" value="{% if(old.shipping_countryCode) %}{{ old.shipping_countryCode }}{% else %}{{ order.order_details.address.shipping.countryCode }}{% endif %}">
								{% if errors.shipping_countryCode %}
									<small class="form-control-feedback ml-2">{{ errors.shipping_countryCode |first }}</small>
								{% endif %}
							</div>
							<div class="col-md-12 mt-3">
								<button type="submit" class="btn btn-success btn-block">Update Order</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
