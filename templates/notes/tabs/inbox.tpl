<!-- Tab main_types_tab -->
<div class="tab-pane fade" id="inbox" role="tabpanel">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-centered w-100 dt-responsive nowrap" id="all_inbox_notes_table">
							<thead class="thead-light text-sm">
								<tr>
									<th>ID</th>
									<th>Van</th>
									<th>Model</th>
									<th>Bericht</th>
									<th>Bijgewerkt om</th>
									<th>Gemaakt op</th>
									<th width="200">
										<i class="fa fa-cog"></i>
									</th>
								</tr>
							</thead>
							<tbody>
								{% for attribute in attributes %}
									<tr>
										<td>
											{{attribute.id}}
										</td>
										<td>
											{{attribute.name}}
										</td>
										<td>
											{{attribute.attribute_group_name}}
										</td>
										<td class="table-action">
											<a href="{{ path_for('Attributes.GetSingle',{'id':attribute.id}) }}" class="action-icon" target="_blank">
												<i class="mdi mdi-square-edit-outline"></i>
											</a>
										</td>
									</tr>
								{% endfor %}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Tab main_generate_tab -->
