<div class="tab-pane fade" id="main_b2b_tab" role="tabpanel">
	<div class="card no-margin">
		<div class="card-body">
			<div class="form-group row">
				<label for="latest_bought_price" class="col-sm-2 col-form-label col-form-label-sm">Laatste inkoopprijs</label>
				<div class="col-sm-8">
					<input type="text" name="latest_bought_price" id="latest_bought_price" class="form-control form-control-sm" data-mappingkey="latest_bought_price" value="{{product.b2b.latest_bought_price | default('0.00')}}">
				</div>
			</div>

			<div class="form-group row">
				<label for="remarks" class="col-sm-2 col-form-label col-form-label-sm">Opmerkingen</label>
				<div class="col-sm-8">
					<textarea rows="2" class="form-control form-control-sm" id="remarks" name="remarks" data-mappingkey="remarks">{{product.b2b.remarks}}</textarea>
				</div>
				<div class="col-sm-2">
					<input type="button" class="btn btn-success btn-block update-b2b-information-btn mt-2 " value="bijwerken">
				</div>
			</div>
		</div>
	</div>
