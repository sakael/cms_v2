{% extends "layouts/empty.tpl" %}
{% block cssfiles %}{% endblock %}
{% block content %}
        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card">
                            <!-- Logo -->
                            <div class="card-header pt-4 pb-4 text-center bg-primary">
                                <a href="{{path_for('home')}}">
                                    <span><img src="dist/assets/images/logo.png" alt="" height="18"></span>
                                </a>
                            </div>
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <h1 class="text-error">4<i class="mdi mdi-emoticon-sad"></i>4</h1>
                                    <h4 class="text-uppercase text-danger mt-3">Pagina niet gevonden</h4>
                                    <p class="text-muted mt-3">We hebben overal gezocht maar het lijkt erop dat de pagina die je zoekt niet (meer) bestaat. We helpen je graag de weg te vinden naar onze homepage.</p>

                                    <a class="btn btn-info mt-3" href="{{path_for('home')}}"><i class="mdi mdi-reply"></i> Terug naar startpagina</a>
                                </div>
                            </div> <!-- end card-body-->
                        </div>
                        <!-- end card -->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->
{% endblock %} {% block javascript %} {% endblock %}
