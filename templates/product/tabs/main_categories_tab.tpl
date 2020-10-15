<div class="tab-pane fade" id="main_categories_tab" role="tabpanel">
	<div class="card no-margin">
		<div class="card-body">
			<div class="form-group row">
				<label for="categories_picker" class="col-sm-2 col-form-label col-form-label-sm">Wordt getoond in categorieën</label>
				<div class="col-md-10">
					<select class="select2 categories_picker form-control form-control-sm" id="categories_picker" multiple="multiple" style="width:100% !important;">
						{% for category in categories %}
							<option value="{{ category.id }}" {% if category.active == 1 %} selected {% endif %}>{{ category.name }}</option>
						{% endfor %}
					</select>
				</div>
			</div>
		</div>
	</div>
</div>
