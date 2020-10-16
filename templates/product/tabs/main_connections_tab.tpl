<div class="tab-pane fade" id="main_connections_tab" role="tabpanel">
	<div class="card">
		<div class="card-body">
			<ul class="nav nav-tabs  nav-bordered mb-3">
				<li class="nav-item-types">
					<a class="nav-link active" data-toggletab="main_types_tab" data-toggle="tab" href="#main_types_tab" role="tab">Types</a>
				</li>
				<li class="nav-item-types">
					<a class="nav-link" data-toggletab="main_generate_tab" data-toggle="tab" href="#main_generate_tab" role="tab">Genereer merk/type lijst</a>
				</li>
			</ul>
			<!-- Tab panels -->
			<div class="tab-content">
				<div class="tab-pane fade in show active" id="main_types_tab" role="tabpanel">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table width="100%" class="table dt-responsive nowrap" id="main_connections_tab_table">
									<thead class="thead-light text-sm">
										<tr>
											<th></th>
											<th>id</th>
											<th>merk</th>
											<th>type</th>
											<th>
												<i class="fa fa-cog"></i>
											</th>
										</tr>
									</thead>
								</table>
								<div class="col-12 mt-3">
									<div class="row">
										<div class="col">
											<label>&nbsp;Merk</label>
											<select id="select_brand" class="form-control">
												<option selected>Merken...</option>
												{%for brand in brands%}
													<option value="{{brand.id}}">{{brand.name}}</option>
												{% endfor %}
											</select>
										</div>
										<div class="col">
											<label>&nbsp;Type</label>
											<select id="select_type" class="form-control">
												<option selected>Merken...</option>
												{%for brand in brands%}
													<option value="{{brand.id}}">{{brand.name}}</option>
												{% endfor %}
											</select>
										</div>
										<div class="col">
											<label>&nbsp;</label>
											<div class="btn btn-sm btn-primary form-control " id="add_child">koppelen</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Tab main_types_tab -->
				<div class="tab-pane fade" id="main_generate_tab" role="tabpanel">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table width="100%" class="table table-centered w-100 dt-responsive nowrap" id="main_generate_tab_table">
									<thead>
										<tr>
											<th>merk</th>
											<th>type</th>
											<th>aansl.</th>
											<th>gekoppeld?</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- Tab main_generate_tab -->
			</div>
			<!-- Tabs -->
		</div>
	</div>
</div>
