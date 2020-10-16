<div
	class="tab-pane fade in show active" id="main_information_texts" role="tabpanel">
	<!-- main_information_texts tab -->

	<div class="row">
		<div class="col-md-12">
			<h3>Pagina</h3>
		</div>
	</div>
	<div class="form-group row mb-3">
		<label for="title" class="col-md-2 col-form-label">Titel</label>
		<div class="col-md-10">
			<input type="email" class="form-control form-control-sm product-information" name="contents[title]" data-mapping="contents" data-mappingkey="title" id="title" value="{{ product.contents.title }}">
		</div>
	</div>
	<div class="form-group row mb-3">
		<label class="col-md-2 col-form-label" for="description">Beschrijving</label>
		<div class="col-md-10">
			<textarea rows="10" class="form-control form-control-sm rich-text product-information" name="contents[description]" data-mapping="contents" data-mappingkey="description" id="description">{{ product.contents.description }}</textarea>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h3>Extern</h3>
		</div>
	</div>
	<div class="form-group row mb-3">
		<label class="col-md-2 col-form-label" for="title_external">Titel extern</label>
		<div class="col-md-10">
			<input type="text" class="form-control form-control-sm product-information" name="contents[title_external]" data-mapping="contents" data-mappingkey="title_external" id="title_external" value="{{ product.contents.title_external }}">
		</div>
	</div>
	<div class="form-group row mb-3">
		<label class="col-md-2 col-form-label" for="description_external">Beschrijving extern</label>
		<div class="col-md-10">
			<textarea rows="10" class="form-control form-control-sm rich-text product-information" data-mapping="contents" data-mappingkey="description_external" id="description_external" name="contents[description_external]">{{ product.contents.description_external }}</textarea>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10">
			<h3>Seo</h3>
		</div>
	</div>
	<div class="form-group row mb-3">
		<label class="col-md-2 col-form-label" for="seo_title">Seo Titel</label>
		<div class="col-md-10">
			<div class="input-group">
				<input type="text" class="form-control  form-control-sm product-information" name="contents[seo_title]" data-mapping="contents" data-mappingkey="seo_title" id="seo_title" value="{{ product.contents.seo_title }}">
			</div>
		</div>
	</div>
	<div class="form-group row mb-3" k>
		<label class="col-md-2 col-form-label" for="description">Seo Beschrijving</label>
		<div class="col-md-10">
			<textarea rows="5" class="form-control form-control-sm  product-information" name="contents[seo_description]" data-mapping="contents" data-mappingkey="seo_description" id="seo_description">{{ product.contents.seo_description }}</textarea>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10">
			<h3>Youtube</h3>
		</div>
	</div>
	<div class="form-group row mb-3">
		<label class="col-md-2 col-form-label" for="youtube_code">Youtube code</label>
		<div class="col-md-10">
			<input type="text" class="form-control form-control-sm product-information" name="contents[youtube_code]" data-mapping="contents" data-mappingkey="youtube_code" id="youtube_code" value="{{ product.contents.youtube_code }}">
		</div>
	</div>
	<div class="row">
		<div class="col-md-10">
			<h3>Kenmerken</h3>
		</div>
	</div>
	<div id="caracteristics-app" class="">
		<characteristics-app-component :characteristics="characteristics"></characteristic-app-component>
	</div>
	<div class="row">
		<div class="col-md-12">
			<input type="button" class="btn btn-success update-main-information-btn float-right mt-2 " value="bijwerken">
		</div>
	</div>
</div>
<!-- END main_information_texts -->
