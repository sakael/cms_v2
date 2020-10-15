<div class="tab-pane fade" id="main_attributes_tab" role="tabpanel">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					{% for group_name, group in attributes %}
						<div class="row" style="padding:5px;">
							<div class="col-md-2">
								<span>{{ group_name }}</span>
							</div>
							<div class="col-md-10">
								<select class="select2 attributes_picker" data-groupid="{{ group.id }}" {% if group.multiselect == 1 %} multiple="multiple" {% endif %} style="width:100% !important;">
									{% if group.multiselect != 1 %}
										<option value="" selected>----</option>
									{% endif %}
									{% for attr in group.attributes %}
										<option value="{{ attr.id }}" {% if attr.active == 1 %} selected {% endif %}>{{ attr.name }}</option>
									{% endfor %}
								</select>
							</div>
						</div>
					{% endfor %}
				</div>
			</div>
		</div>
	</div>
</div>
