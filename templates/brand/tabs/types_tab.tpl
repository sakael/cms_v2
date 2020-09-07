<div class="tab-pane fade" id="types_tab" role="tabpanel">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="table-responsive">
						<table width="100%" class="table table-centered w-100 dt-responsive nowrap" id="all_brand_types_table">
							<thead class="thead-light text-sm">
								<tr>
									<th>Id</th>
									<th class="all">Title</th>
									<th>Populaire</th>
									<th>Actief menu</th>
									<th>Actief feed</th>
									<th>Bijgewerkt om</th>
									<th>Gemaakt op</th>
									<th>
										<i class="fa fa-cog"></i>
									</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
				<div class="card-footer">
					<div class="pull-right text-right mt-2">
						<span class="fa fa-info-circle text-blue"></span>
						<small>
							De informatie in de tabellen wordt weergegeven met een maximale vertraging van 5 minuten</br>
						Klik met rechts op een artikel om direct naar de Bol.com pagina te gaan.
					</small>
				</div>
				<p>
					<a href="{{path_for('Brands.GetAddType',{'id':Brand.id})}}" class="btn btn-primary mt-3">Type toevoegen</a>
				</p>
			</div>
		</div>
	</div>
	<!-- end active -->
</div></div>
