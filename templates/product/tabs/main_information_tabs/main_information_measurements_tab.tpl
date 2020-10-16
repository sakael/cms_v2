<div class="tab-pane fade in" id="main_information_measurements" role="tabpanel">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<h5 class="mt-3 mb-2">Artikel afmetingen (mm)</h5>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Lengte</label>
					<input class="form-control form-control-sm product-measurements" data-mapping="measurements" data-mappingkey="length" value="{{ product.measurements.length }}" type="number" min="1.00" step="0.01">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Hoogte</label>
					<input class="form-control form-control-sm product-measurements" data-mapping="measurements" data-mappingkey="height" value="{{ product.measurements.height }}" type="number" min="1.00" step="0.01">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Breedte</label>
					<input class="form-control form-control-sm product-measurements" data-mapping="measurements" data-mappingkey="width" value="{{ product.measurements.width }}" type="number" min="1.00" step="0.01">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Gewicht</label>
					<input class="form-control form-control-sm product-measurements" data-mapping="measurements" data-mappingkey="weight" value="{{ product.measurements.weight }}" type="number" min="1.00" step="0.01">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
					<h5 class="mt-3 mb-2">Alleen nodig bij universele artikelen</h5>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Min Lengte</label>
					<input class="form-control form-control-sm product-measurements" data-mapping="measurements" data-mappingkey="minlength" value="{{ product.measurements.minlength }}" type="number" min="1.00" step="0.01">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Max Lengte</label>
					<input class="form-control form-control-sm product-measurements" data-mapping="measurements" data-mappingkey="maxlength" value="{{ product.measurements.maxlength }}" type="number" min="1.00" step="0.01">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Min Breedte</label>
					<input class="form-control form-control-sm product-measurements" data-mapping="measurements" data-mappingkey="minwidth" value="{{ product.measurements.minwidth }}" type="number" min="1.00" step="0.01">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Max Breedte</label>
					<input class="form-control form-control-sm product-measurements" data-mapping="measurements" data-mapping="measurements" data-mappingkey="maxwidth" value="{{ product.measurements.maxwidth }}" type="number" min="1.00" step="0.01">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<input type="button" class="btn btn-success btn-sm btn-block update-measurements-btn float-right mt-2 " value="bijwerken">
			</div>
		</div>
	</div>
</div>
<!-- END main_information_measurements -->
