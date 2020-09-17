{% extends "layouts/base.tpl" %}

{% block cssfiles_before %}
<!-- third party css -->
<link href="/dist/assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
<link href="/dist/assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
<!-- third party css end -->
{% endblock %}

{% block page_title %}{{page_title}}{% endblock %}

{% block content %}

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ path_for('ProductsIndex') }}">Alle artikelen</a></li>
                    <li class="breadcrumb-item"><a href="{{ path_for('ProductGet',{'id':product.id}) }}">{{ product.sku }}</a></li>
                    <li class="breadcrumb-item active">Product Details</li>
                </ol>
            </div>
            <h4 class="page-title">Product Details</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3">
                        <!-- Product image -->
                        <a href="javascript: void(0);" class="text-center d-block mb-4">
                            {% for image in product.images if image.main == 1 %}
                                <img src="{{ IMAGE_PATH }}/{{ image.url }}" class="img-fluid" style="max-width: 280px;" alt="Product-img">
                            {% endfor %}
                        </a>

                        <div class="d-lg-flex d-none justify-content-center">
                            {% for image in product.images if image.main == 0 %}
                                <a href="javascript: void(0);">
                                    <img src="{{ IMAGE_PATH }}/{{ image.url }}" class="img-fluid img-thumbnail p-2" style="max-width: 75px;" alt="Product-img">
                                </a>
                            {% endfor %}
                        </div>
                    </div> <!-- end col -->
                    <div class="col-lg-9">
                        <form class="pl-lg-4">
                            <!-- Product title -->
                            <h3 class="mt-0">{{ product.contents.title }} <a href="{{ path_for('ProductEdit',{'id': product.id }) }}" class="text-muted"><i class="mdi mdi-square-edit-outline ml-2"></i></a> </h3>
                            <p class="mb-1">Bekijk op webshop <a href="https://beta.123bestdeal.nl/{{ product.urls[0].slug }}.html" target="_blank"><i class="mdi mdi-square-edit-outline ml-2"></i></a></a></p>
                            <p class="font-16">
                                <span class="text-warning mdi mdi-star"></span>
                                <span class="text-warning mdi mdi-star"></span>
                                <span class="text-warning mdi mdi-star"></span>
                                <span class="text-warning mdi mdi-star"></span>
                                <span class="text-warning mdi mdi-star"></span>
                            </p>

                            <!-- Product stock -->
                            <div class="mt-3">
                                <h4><span class="badge badge-success-lighten">Instock</span></h4>
                            </div>

                            {#
                            <!-- Product description -->
                            <div class="mt-4">
                                <h6 class="font-14">Retail Price:</h6>
                                <h3> $139.58</h3>
                            </div>

                            <!-- Quantity -->
                            <div class="mt-4">
                                <h6 class="font-14">Quantity</h6>
                                <div class="d-flex">
                                    <input type="number" min="1" value="1" class="form-control" placeholder="Qty" style="width: 90px;">
                                    <button type="button" class="btn btn-danger ml-2"><i class="mdi mdi-cart mr-1"></i> Add to cart</button>
                                </div>
                            </div>
                            #}

                            <!-- Product description -->
                            <div class="mt-4">
                                <h6 class="font-14">Description:</h6>
                                <p>{{ product.contents.description|striptags }}</p>
                            </div>

                            <!-- Product information -->
                            <div class="mt-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h6 class="font-14">Available Stock:</h6>
                                        <p class="text-sm lh-150">1784</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="font-14">Number of Orders:</h6>
                                        <p class="text-sm lh-150">5,458</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="font-14">Revenue:</h6>
                                        <p class="text-sm lh-150">$8,57,014</p>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div> <!-- end col -->
                </div> <!-- end row-->

                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-centered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Verkoopkanaal</th>
                                <th>Prijs</th>
                                <th>Actief</th>
                            </tr>
                        </thead>
                        <tbody>

                            {% for shop in shops %}

                                <tr>
                                    <td>{{ shop.domain }}</td>
                                    <td>
                                        {% for shop_id, price in product.prices['price'] if shop_id == shop.id %}
                                            <span class="text-muted text-strikethrough"><del>€{{ price.price_was }}</del></span> €{{ price.price }}
                                        {% endfor %}
                                    </td>
                                    <td>
                                        <input type="checkbox" id="switch_{{ shop.id }}" {% if shop.active == 1 %}checked{% endif %} data-switch="success"/>
                                        <label for="switch_{{ shop.id }}" data-on-label="Ja" data-off-label="Nee"></label>

                                        {#
                                        <div class="progress-w-percent mb-0">
                                            <span class="progress-value">478 </span>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 56%;" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        #}

                                    </td>
                                </tr>

                            {% endfor %}

                        </tbody>
                    </table>
                </div> <!-- end table-responsive-->

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row-->



{% endblock %}
