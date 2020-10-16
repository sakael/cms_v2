<div class="tab-pane fade in show active" id="main_information_tab" role="tabpanel">
	<div class="card no-margin">
		<div class="card-body">
			<style>
				.control-label {
					padding-top: 12px;
				}
			</style>

			<ul class="nav nav-tabs  nav-bordered mb-3">
				<li class="nav-item">
					<a class="nav-link active" data-toggletab="main_information_texts" data-toggle="tab" href="#main_information_texts" role="tab">Teksten</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggletab="main_information_pricing" data-toggle="tab" href="#main_information_pricing" role="tab">Prijzen</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggletab="main_information_urls" data-toggle="tab" href="#main_information_urls" role="tab">Urls</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggletab="main_information_measurements" data-toggle="tab" href="#main_information_measurements" role="tab">Afmetingen</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggletab="main_information_others" data-toggle="tab" href="#main_information_others" role="tab">Overig</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggletab="main_information_variations" data-toggle="tab" href="#main_information_variations" role="tab">Variaties</a>
				</li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<form class="form-horizontal" accept-charset="utf-8">
						<div class="tab-content">
							<div class="spacer">&nbsp;</div>
							{% include 'product/tabs/main_information_tabs/main_information_texts_tab.tpl' %}
							{% include 'product/tabs/main_information_tabs/main_information_pricing_tab.tpl' %}
							{% include 'product/tabs/main_information_tabs/main_information_urls_tab.tpl' %}
							{% include 'product/tabs/main_information_tabs/main_information_measurements_tab.tpl' %}
							{% include 'product/tabs/main_information_tabs/main_information_others_tab.tpl' %}
							{% include 'product/tabs/main_information_tabs/main_information_variations_tab.tpl' %}
						</div>
						<!-- END tab-content -->
					</form>
				</div>
			</div>
		</div>
		<!-- END CARD BODY -->
	</div>
</div>
