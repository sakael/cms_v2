<div class="tab-pane fade" id="main_shops_tab" role="tabpanel">
	<div class="card no-margin">
		<div class="card-body">
			<div class="form-group row">
				<label for="webshops_picker" class="col-sm-2 col-form-label col-form-label-sm">Wordt getoond in webshops</label>
				<div class="col-md-10">
					<select class="select2 webshops_picker form-control form-control-sm" id="webshops_picker" multiple="multiple" style="width:100% !important;">
						{% for shop in shops %}
							<option value="{{ shop.id }}" {% if shop.active == 1 %} selected {% endif %}>{{ shop.domain }}</option>
						{% endfor %}
					</select>
				</div>
			</div>
		</div>
	</div>
</div>
