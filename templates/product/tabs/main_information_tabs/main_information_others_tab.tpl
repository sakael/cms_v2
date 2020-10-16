<div class="tab-pane fade in" id="main_information_others" role="tabpanel">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-6 col-sm-6 col-lg-3">
				<div class="form-group">
					<label>Locatie</label>
					<input class="form-control form-control-sm product-measurements" name="location" data-mapping="other-info" data-mappingkey="location" id="location" value="{{ product.location }}" type="text">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-lg-3">
				<div class="form-group">
					<label>Klasse</label>
					<select name="classification" data-mapping="other-info" data-mappingkey="classification" class="form-control form-control-sm">
						<option value="0" selected>niet van toepassing</option>
						<option value="1" {% if (product.classification==1) %} selected {% endif %}>1 ster (sleeve, etc)</option>
						<option value="2" {% if (product.classification==2) %} selected {% endif %}>2 sterren (budget)</option>
						<option value="3" {% if (product.classification==3) %} selected {% endif %}>3 sterren (past goed)</option>
						<option value="4" {% if (product.classification==4) %} selected {% endif %}>4 sterren (op maat)</option>
						<option value="5" {% if (product.classification==5) %} selected {% endif %}>5 sterren (luxe)</option>
					</select>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-lg-3">
				<div class="form-group">
					<label>Voorraad</label>
					<select name="stocklevel" data-mapping="other-info" data-mappingkey="stocklevel" class="form-control form-control-sm">
						<option value="1" {% if (product.stocklevel==1) %} selected {% endif %}>Voorradig</option>
						<option value="2" {% if (product.stocklevel==2) %} selected {% endif %}>Enkele voorradig</option>
						<option value="3" {% if (product.stocklevel==3) %} selected {% endif %}>Binnenkort weer voorradig</option>
						<option value="4" {% if (product.stocklevel==4) %} selected {% endif %}>Niet meer leverbaar</option>
						<option value="5" {% if (product.stocklevel==5) %} selected {% endif %}>Voorradig, externe leverancier</option>
					</select>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-lg-3">
				<div class="form-group">
					<label>Leverdatum</label>
					<input type="text" data-mapping="other-info" data-mappingkey="delivery_at" class="form-control form-control-sm datepicker delivery_at" value="{{product.delivery_at}}"  data-provide="datepicker" data-date-autoclose="true" id="date_from">
				</div>
			</div>
		</div>
		<div class="row mt-2 mb-2">
			<div class="col-md-6 col-sm-12 col-lg-6">
				<div class="form-group">
					<label for="comment">Opmerkingen</label>
					<textarea rows="3" class="form-control form-control-sm" id="comment" data-mapping="other-info" data-mappingkey="comment" name="comment">{{ product.comment }}</textarea>
				</div>
			</div>
			<div class="col-md-2 col-sm-6">
				<div class="custom-control custom-checkbox mt-4">
					<input type="checkbox" class="custom-control-input" {% if (product.active) %} checked {% endif %} id="active" data-mapping="other-info" data-mappingkey="active" name="active" value="1">
					<label class="custom-control-label" for="active">Active</label>
				</div>
			</div>
			<div class="col-md-2 col-sm-6 mt-4">
				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" data-mapping="other-info" data-mappingkey="package" id="package" name="package" {% if (product.package) %} checked {% endif %} value="1">
					<label class="custom-control-label" for="package">Pakket</label>
				</div>
			</div>
		</div>
		<div class="row mt-2 mb-2">
			
		</div>
		<div class="row">
			<div class="col-md-12">
				<input type="button" class="btn btn-success btn-sm btn-block update-other-info-btn float-right mt-2 " value="bijwerken">
			</div>
		</div>
	</div>
</div>
<!-- END main_information_others -->
