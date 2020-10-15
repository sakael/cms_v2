<div class="tab-pane fade in show active" id="main_information_tab" role="tabpanel">
   <div class="card no-margin">
      <div class="card-body">
         <style>
            .control-label {
            padding-top: 12px;
            }
         </style>
         <div class="row">
            <ul class="nav nav-pills bg-nav-pills nav-justifieds">
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
         </div>
         <div class="row">
            <div class="col-md-12">
               <form class="form-horizontal" accept-charset="utf-8">
                  <div class="tab-content">
                     <div class="spacer">&nbsp;</div>
                     <div class="tab-pane fade in show active" id="main_information_texts" role="tabpanel">
                        <!-- main_information_texts tab -->
                        <div class="col-md-12">
                          <div class="row">
                             <div class="col-md-10">
                                <h3>Pagina</h3>
                             </div>
                          </div>
                           <div class="form-group">
                              <div class="row">
                                 <label class="control-label col-sm-2" for="title">Titel</label>
                                 <div class="col-sm-10">
                                    <div class="input-group">
                                       <input type="text" class="form-control product-information" name="contents[title]" data-mapping="contents"data-mappingkey="title" id="title" value="{{ product.contents.title }}">
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="row">
                                 <label class="control-label col-sm-2" for="description">Beschrijving</label>
                                 <div class="col-sm-10">
                                    <textarea rows="10" class="form-control rich-text product-information" name="contents[description]" data-mapping="contents" data-mappingkey="description" id="description">{{ product.contents.description }}</textarea>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-10">
                                 <h3>Extern</h3>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="row">
                                 <label class="control-label col-sm-2" for="title_external">Titel extern</label>
                                 <div class="col-sm-10">
                                    <input type="text" class="form-control product-information"  name="contents[title_external]"  data-mapping="contents" data-mappingkey="title_external" id="title_external" value="{{ product.contents.title_external }}">
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="row">
                                 <label class="control-label col-sm-2" for="description_external">Beschrijving extern</label>
                                 <div class="col-sm-10">
                                    <textarea rows="10" class="form-control rich-text product-information" data-mapping="contents" data-mappingkey="description_external" id="description_external" name="contents[description_external]">{{ product.contents.description_external }}</textarea>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-10">
                                 <h3>Seo</h3>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="row">
                                 <label class="control-label col-sm-2" for="seo_title">Seo Titel</label>
                                 <div class="col-sm-10">
                                    <div class="input-group">
                                       <input type="text" class="form-control product-information" name="contents[seo_title]" data-mapping="contents" data-mappingkey="seo_title" id="seo_title" value="{{ product.contents.seo_title }}">
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="row">
                                 <label class="control-label col-sm-2" for="description">Seo Beschrijving</label>
                                 <div class="col-sm-10">
                                    <textarea rows="5" class="form-control  product-information" name="contents[seo_description]" data-mapping="contents" data-mappingkey="seo_description" id="seo_description">{{ product.contents.seo_description }}</textarea>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-10">
                                 <h3>Youtube</h3>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="row">
                                 <label class="control-label col-sm-2" for="youtube_code">Youtube code</label>
                                 <div class="col-sm-10">
                                    <div class="input-group">
                                       <input type="text" class="form-control product-information" name="contents[youtube_code]" data-mapping="contents" data-mappingkey="youtube_code" id="youtube_code" value="{{ product.contents.youtube_code }}">
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-10">
                                 <h3>Kenmerken</h3>
                              </div>
                           </div>
                           <div id="caracteristics-app" class="border pl-4 pr-4 pb-5  pt-5 mt-2">
                             <characteristics-app-component :characteristics="characteristics" ></characteristic-app-component>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                  <input type="button" class="btn btn-success update-main-information-btn float-right mt-2 " value="bijwerken" >
                              </div>
                          </div>
                        </div>
                     </div>
                     <!-- END main_information_texts -->
                     <div class="tab-pane fade in" id="main_information_pricing" role="tabpanel">
                        <div class="card-body" style="margin:0 !important;">
                           <div class="row">
                              <ul class="nav nav-pills nav-justified">
                                 {% for shop in shops %}
                                 <li class="nav-item">
                                    <a class="nav-link" data-toggletab="main_information_pricing_shops_{{ shop.id }}" data-toggle="tab" href="#main_information_pricing_shops_{{ shop.id }}" role="tab">{{ shop.domain }}</a>
                                 </li>
                                 {% endfor %}
                              </ul>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="tab-content">
                                    <div class="spacer">&nbsp;</div>
                                    {% for shop in shops %}
                                    <div class="tab-pane fade in" id="main_information_pricing_shops_{{ shop.id }}" role="tabpanel">
                                       {% set pricing = product.prices.price[shop.id] %}
                                       <div class="row">
                                          <div class="col-md-12">
                                             <hr>
                                             <small>Basis</small>
                                             <div class="spacer">&nbsp;</div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md-2">
                                             <div class="form-group">
                                                <label>Van prijs</label>
                                                <input class="form-control product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_was" value="{{ pricing.price_was }}" type="number" min="1.00" step="0.01">
                                             </div>
                                          </div>
                                          <div class="col-md-2">
                                             <div class="form-group">
                                                <label>Korting</label>
                                                <input class="form-control product-pricing" data-shopid="{{ shop.id }}" data-mappingid="discount" value="{{ pricing.discount }}" type="number" min="1.00" step="0.25">
                                             </div>
                                          </div>
                                          <div class="col-md-2">
                                             <div class="form-group">
                                                <label>Webshop prijs</label>
                                                <input class="form-control product-pricing" data-shopid="{{ shop.id }}" disabled data-mappingid="price" value="{{ (pricing.price_was - pricing.discount) }}" type="number" min="1.00" step="0.01">
                                             </div>
                                          </div>
                                          <div class="col-md-2 offset-md-2">
                                             <div class="form-group">
                                                <label>Combo prijs</label>
                                                <input class="form-control product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_combo" value="{{ pricing.price_combo }}" type="number" min="1.00" step="0.01">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md-12">
                                             <hr>
                                             <small>Staffelprijzen</small>
                                             <div class="spacer">&nbsp;</div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md-2">
                                             <div class="form-group">
                                                <label>Staffel 2</label>
                                                <input class="form-control product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_2" value="{{ pricing.price_2 }}" type="number" min="1.00" step="0.01">
                                             </div>
                                          </div>
                                          <div class="col-md-2">
                                             <div class="form-group">
                                                <label>Staffel 5</label>
                                                <input class="form-control product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_5" value="{{ pricing.price_5 }}" type="number" min="1.00" step="0.01">
                                             </div>
                                          </div>
                                          <div class="col-md-2">
                                             <div class="form-group">
                                                <label>Staffel 10</label>
                                                <input class="form-control product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_10" value="{{ pricing.price_10 }}" type="number" min="1.00" step="0.01">
                                             </div>
                                          </div>
                                          <div class="col-md-2">
                                             <div class="form-group">
                                                <label>Staffel 20</label>
                                                <input class="form-control product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_20" value="{{ pricing.price_20 }}" type="number" min="1.00" step="0.01">
                                             </div>
                                          </div>
                                          <div class="col-md-2">
                                             <div class="form-group">
                                                <label>Staffel 50</label>
                                                <input class="form-control product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_50" value="{{ pricing.price_50 }}" type="number" min="1.00" step="0.01">
                                             </div>
                                          </div>
                                          <div class="col-md-2">
                                             <div class="form-group">
                                                <label>Staffel 100</label>
                                                <input class="form-control product-pricing" data-shopid="{{ shop.id }}" data-mappingid="price_100" value="{{ pricing.price_100 }}" type="number" min="1.00" step="0.01">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md-12">
                                             <input type="button" class="btn btn-success update-price-btn float-right mt-2 " value="bijwerken" data-id="{{ shop.id }}">
                                          </div>
                                       </div>
                                    </div>
                                    {% endfor %}
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- END main_information_pricing -->
                     <div class="tab-pane fade in" id="main_information_urls" role="tabpanel">
                        <div class="col-md-12">
                           <div id="product-urls">
                              <product-url-component :product_id="product_id" :shop_domain="shopDomain" :update_url="update_url" :get_url="get_url"></product-url-component>
                           </div>
                        </div>
                     </div>
                     <!-- END main_information_urls -->
                     <div class="tab-pane fade in" id="main_information_measurements" role="tabpanel">
                        <div class="col-md-12">
                           <div class="row">
                              <div class="col-md-12">
                                 <hr>
                                 <small>Artikel afmetingen (mm)</small>
                                 <div class="spacer">&nbsp;</div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label>Lengte</label>
                                    <input class="form-control product-measurements" data-mapping="measurements" data-mappingkey="length" value="{{ product.measurements.length }}" type="number" min="1.00" step="0.01">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label>Hoogte</label>
                                    <input class="form-control product-measurements" data-mapping="measurements" data-mappingkey="height" value="{{ product.measurements.height }}" type="number" min="1.00" step="0.01">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label>Breedte</label>
                                    <input class="form-control product-measurements" data-mapping="measurements" data-mappingkey="width" value="{{ product.measurements.width }}" type="number" min="1.00" step="0.01">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label>Gewicht</label>
                                    <input class="form-control product-measurements" data-mapping="measurements" data-mappingkey="weight" value="{{ product.measurements.weight }}" type="number" min="1.00" step="0.01">
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <br/><span><small>Alleen nodig bij universele artikelen</small></span>
                                 <hr>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label>Min Lengte</label>
                                    <input class="form-control product-measurements" data-mapping="measurements" data-mappingkey="minlength" value="{{ product.measurements.minlength }}" type="number" min="1.00" step="0.01">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label>Max Lengte</label>
                                    <input class="form-control product-measurements" data-mapping="measurements" data-mappingkey="maxlength" value="{{ product.measurements.maxlength }}" type="number" min="1.00" step="0.01">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label>Min Breedte</label>
                                    <input class="form-control product-measurements" data-mapping="measurements"  data-mappingkey="minwidth" value="{{ product.measurements.minwidth }}" type="number" min="1.00" step="0.01">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label>Max Breedte</label>
                                    <input class="form-control product-measurements" data-mapping="measurements"  data-mapping="measurements" data-mappingkey="maxwidth" value="{{ product.measurements.maxwidth }}" type="number" min="1.00" step="0.01">
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <input type="button" class="btn btn-success update-measurements-btn float-right mt-2 " value="bijwerken">
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- END main_information_measurements -->
                     <div class="tab-pane fade in" id="main_information_others" role="tabpanel">
                        <div class="col-md-12">
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label>Locatie</label>
                                    <input class="form-control product-measurements" name="location" data-mapping="other-info" data-mappingkey="location" id="location" value="{{ product.location }}" type="text">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label>Pakket</label>
                                    <label class="form-control">
                                    <input type="checkbox"  data-mapping="other-info" data-mappingkey="package" name="package" {% if (product.package) %} checked {% endif %} value="1"> ja, altijd als pakket versturen
                                    </label>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label>Klasse</label>
                                    <select name="classification" data-mapping="other-info" data-mappingkey="classification" class="form-control">
                                       <option value="0" selected>niet van toepassing</option>
                                       <option value="1" {% if (product.classification==1) %} selected {% endif %}>1 ster (sleeve, etc)</option>
                                       <option value="2" {% if (product.classification==2) %} selected {% endif %}>2 sterren (budget)</option>
                                       <option value="3" {% if (product.classification==3) %} selected {% endif %}>3 sterren (past goed)</option>
                                       <option value="4" {% if (product.classification==4) %} selected {% endif %}>4 sterren (op maat)</option>
                                       <option value="5" {% if (product.classification==5) %} selected {% endif %}>5 sterren (luxe)</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label>Voorraad</label>
                                    <select name="stocklevel" data-mapping="other-info" data-mappingkey="stocklevel" class="form-control">
                                    <option value="1" {% if (product.stocklevel==1) %} selected {% endif %}>Voorradig</option>
                                    <option value="2" {% if (product.stocklevel==2) %} selected {% endif %}>Enkele voorradig</option>
                                    <option value="3" {% if (product.stocklevel==3) %} selected {% endif %}>Binnenkort weer voorradig</option>
                                    <option value="4" {% if (product.stocklevel==4) %} selected {% endif %}>Niet meer leverbaar</option>
                                    <option value="5" {% if (product.stocklevel==5) %} selected {% endif %}>Voorradig, externe leverancier</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                   <label for="comment">Opmerkingen</label>
                                   <textarea rows="2" class="form-control" id="comment" data-mapping="other-info" data-mappingkey="comment" name="comment">{{ product.comment }}</textarea>
                                </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group">
                                   <label> </label>
                                   <label class="form-control">
                                    <input type="checkbox" {% if (product.active) %} checked {% endif %} data-mapping="other-info" data-mappingkey="active" name="active" value="1"> Active
                                    </label>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <input type="button" class="btn btn-success update-other-info-btn float-right mt-2 " value="bijwerken">
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- END main_information_others -->
                     <div class="tab-pane fade in" id="main_information_variations" role="tabpanel">
                        <variations-app-component :product_id="{{product.id}}"  :update_url="update_url"></variations-app-component>
                     </div>
                     <!-- END main_information_variations -->
                  </div>
                  <!-- END tab-content -->
               </form>
            </div>
         </div>
      </div>
      <!-- END CARD BODY -->
   </div>
</div>
