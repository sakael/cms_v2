<div class="tab-pane fade in" id="main_information_pricing" role="tabpanel">
	<div class="card-body" style="margin:0 !important;">
		<ul class="nav nav-pills bg-nav-pills">
			{% for shop in shops %}
				<li class="nav-item">
					<a class="nav-link" data-toggletab="main_information_pricing_shops_{{ shop.id }}" data-toggle="tab" href="#main_information_pricing_shops_{{ shop.id }}" role="tab">{{ shop.domain }}</a>
				</li>
			{% endfor %}
		</ul>
		<div class="row">
			<div class="col-md-12">
				<div class="tab-content">
					<div class="spacer">&nbsp;</div>
					{% for shop in shops %}
						<div class="tab-pane fade in" id="main_information_pricing_shops_{{ shop.id }}" role="tabpanel">
							{% set pricing = product.prices.price[shop.id] %}
							<div class="row mt-3 mb-1">
								<div class="col-md-2">
									<div class="form-group">
										<label>Van prijs</label>
										<input class="form-control form-control-sm product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_was" value="{{ pricing.price_was }}" type="number" min="1.00" step="0.01">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Korting</label>
										<input class="form-control form-control-sm product-pricing" data-shopid="{{ shop.id }}" data-mappingid="discount" value="{{ pricing.discount }}" type="number" min="1.00" step="0.25">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Webshop prijs</label>
										<input class="form-control form-control-sm product-pricing" data-shopid="{{ shop.id }}" disabled data-mappingid="price" value="{{ (pricing.price_was - pricing.discount) }}" type="number" min="1.00" step="0.01">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Combo prijs</label>
										<input class="form-control form-control-sm product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_combo" value="{{ pricing.price_combo }}" type="number" min="1.00" step="0.01">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<hr>
									<h4 class="mt-3 mb-2 pb-1">Staffelprijzen</h4>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label>Staffel 2</label>
										<input class="form-control form-control-sm product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_2" value="{{ pricing.price_2 }}" type="number" min="1.00" step="0.01">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Staffel 5</label>
										<input class="form-control form-control-sm product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_5" value="{{ pricing.price_5 }}" type="number" min="1.00" step="0.01">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Staffel 10</label>
										<input class="form-control form-control-sm product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_10" value="{{ pricing.price_10 }}" type="number" min="1.00" step="0.01">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Staffel 20</label>
										<input class="form-control form-control-sm product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_20" value="{{ pricing.price_20 }}" type="number" min="1.00" step="0.01">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Staffel 50</label>
										<input class="form-control product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_50" value="{{ pricing.price_50 }}" type="number" min="1.00" step="0.01">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Staffel 100</label>
										<input class="form-control form-control-sm product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_100" value="{{ pricing.price_100 }}" type="number" min="1.00" step="0.01">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<input type="button" class="btn btn-success update-price-btn float-right mt-2 " value="bijwerken" data-id="{{ shop.id }}">
								</div>
							</div>
						</div>
					{% endfor %}
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END main_information_pricing -->
