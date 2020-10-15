<div class="tab-pane fade" id="main_images_tab" role="tabpanel">
    <div class="card">
        <div class="card-body">
            <div class="row ui-sortable">
                <!-- upload area -->
                <div class="col-sm-2 col-md-2 file_uploader_area mb-1" >
                    <div class="img-thumbnail file_uploader">
                        <div class="text-center p-1">
                            <img src="/assets/images/drop-here.png" class="img-fluid" title="Sleep afbeelding of bestand hier naartoe">
                        </div>
                        <div class="text-center file_uploader_helptext btn btn-sm btn-warning btn-block mb-1 ">
                            Bestand uploaden
                        </div>
                    </div>
                </div>
                {% for image in product.images %}
                    <div class="col-sm-2 col-md-2 ui-draggable" id="{{ image.id }}">
                        <div class="img-thumbnail">
                            <div class="text-center" style="padding:15px;">
                                {% if image.non_image == 1 %}
                                    <a href="{{ image.url }}" target="_blank">
                                        <img src="/img/file_icon.png" style="min-height:100px;height:100px;" class="img-fluid">
                                    </a>
                                {% else %}
                                    <img src="{{ IMAGE_PATH }}/{{getThumb(image.url,'123bestdeal')}}" style="min-height:100px;height:100px;" class="img-fluid" title="{{ image.title }}">
                                {% endif %}
                            </div>
                            <div class="caption">
                                <input type="text" class="form-control form-control-sm image_name mb-1" data-imageid="{{ image.id }}" value="{{ image.title|default('Geen titel ingesteld') }}">
                                <div style="font-size:70%; padding-left:10px;padding-bottom:10px;">
                                    {% if image.non_image == 0 %}
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input image_checkbox" id="image_main" {% if image.main == 1 %}checked{% endif %} data-cbtype="image_main" data-imageid="{{ image.id }}">
                                            <label class="custom-control-label" for="image_main" style="padding-top:4px">Hoofdafbeelding webshop</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input image_checkbox" id="image_main_ext" {% if image.main_ext == 1 %}checked{% endif %} data-cbtype="image_main_ext" data-imageid="{{ image.id }}">
                                            <label class="custom-control-label" for="image_main_ext" style="padding-top:4px">Hoofdafbeelding extern</label>
                                        </div>
                                    {% endif %}
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input image_checkbox" id="image_hidden" {% if image.visible == 0 %}checked{% endif %} data-cbtype="image_hidden" data-imageid="{{ image.id }}">
                                        <label class="custom-control-label" for="image_hidden" style="padding-top:4px">Verbergen</label>
                                    </div>
                                    <a href="#" class="btn btn-danger btn-sm btn-remove-image btn-block font-10 mt-1" data-imageid="{{ image.id }}" role="button">Verwijderen</a>
                                </div>
                            </div>
                        </div>
                    </div>

                {% endfor %}
            </div>
        </div>
    </div>
</div>
