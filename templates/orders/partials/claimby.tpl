<div class="row order-top-accordion">
	<div class="col-md-12">
		<div class="card mb-0">
			<div class="card-body">
				<div class="accordion" id="accordionExample">
					<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
						<div class="row mb-1">
							<div class="col">
								<div class="card card-body m-0">
									<div class="row mb-3">
										<div class="col">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"  value="1" class="custom-control-input" name="show_paid_orders" id="show_paid_orders">
                                                <label class="custom-control-label" for="show_paid_orders">Toon niet betaalde orders</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"  value="1" class="custom-control-input" name="by-others" id="by-others">
                                                <label class="custom-control-label" for="by-others">Toon ook orders van anderen (bij 'Inpakken')</label>
                                            </div>
                                        </div>
										<div class="col text-right">
											<button class="btn btn-info btn-sm" id="postcode-control" type="button">
												<i class="fa fa-check mr-2"></i>Postcode controle
											</button>
											<a href="{{path_for('Orders.GetNew')}}" class="btn btn-success btn-sm ml-2">
												<i class="fa fa-check mr-2"></i>Niewe order toevoegen
											</a>
										</div>
									</div>
									<div class="row mt-1">
										<div class="col-md-3">
											<label for="claim-by-productgroup">Claim per productgroep:</label>
											<div class="form-inline">
												<select name="claim-by-productgroup" id="claim-by-productgroup" class="form-control form-control-sm col-md-8">
													<option selected disabled hidden value=''></option>
													<option value="TH">Telefoonhoezen</option>
													<option value="KB">Keyboards</option>
													<option value="SP">Screenprotectors</option>
													<option value="SL">Sleeves</option>
													<option value="A">Accessoires</option>
													<option value="ST">Stekkers</option>
													<option value="PEN">Pennen</option>
													<option value="C">Cases</option>
													<option value="MH">Musthaves</option>
												</select>
												<button type="button" class="btn btn-success btn-sm col-md-3 offset-md-1" id="claim-by-productgroup-btn">Claim !</button>
											</div>
										</div>
										<div class="col-md-3">
											<label for="claim-by-stelling">Claim per stelling:</label>
											<div class="form-inline">
												<select name="claim-by-stelling" id="claim-by-stelling" class="form-control form-control-sm col-md-8">
													<option selected disabled hidden value=''></option>
													<option value="TH">TH</option>
													<option value="AA">AA</option>
													<option value="BB">BB</option>
													<option value="CC">CC</option>
													<option value="DD">DD</option>
													<option value="EE">EE</option>
													<option value="FF">FF</option>
													<option value="GG">GG</option>
													<option value="HH">HH</option>
													<option value="EX">Externe levering</option>
												</select>
												<button type="button" class="btn btn-success  btn-sm  col-md-3 offset-md-1" id="claim-by-claim-by-stelling-btn">Claim !</button>
											</div>
										</div>
										<div class="col-md-3">
											<label for="claim-by-buitenland">Claim zendingen buitenland:</label>
											<div class="form-inline">
												<select name="claim-by-buitenland" id="claim-by-buitenland" class="form-control form-control-sm col-md-8">
													<option selected disabled hidden value=''></option>
													<option value="buitenland">Ja</option>
												</select>
												<button type="button" class="btn btn-success btn-sm col-md-3 offset-md-1" id="claim-by-buitenland-btn">Claim !</button>
											</div>
										</div>
										<div class="col-md-3">
											<label for="claim-by-B2B">Claim B2B zendingen:</label>
											<div class="form-inline">
												<select name="claim-by-B2B" id="claim-by-B2B" class="form-control form-control-sm col-md-8">
													<option selected disabled hidden value=''></option>
													<option value="b2b">Ja</option>
												</select>
												<button type="button" class="btn btn-success btn-sm  col-md-3 offset-md-1" id="claim-by-B2B-btn">Claim !</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="text-center">
					<h5 class="m-0">
						<span class="btn-link collapsed text-success font-20" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							<i class="dripicons-plus"></i>
							<i class="dripicons-minus pt-2 text-secondary"></i>
						</span>
					</h5>
				</div>
			</div>
		</div>
	</div>
</div>
