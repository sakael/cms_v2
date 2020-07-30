<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>123BestDeal CMS | Inloggen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="" name="description" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="dist/assets/images/favicon.ico" />
    <!-- third party css -->
    <link href="dist/assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- third party css end -->

    <!-- App css -->
    <link href="dist/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="dist/assets/css/app.min.css" rel="stylesheet" type="text/css" id="light-style" />
    <link href="dist/assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />
    {% block cssfiles %}{% endblock %}
  </head>

    <body class="loading authentication-bg" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    {% include 'partials/flash.tpl' %}
        <div class="account-pages mt-5 mb-5">
            <div class="container">
            
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card">

                            <!-- Logo -->
                            <div class="card-header pt-4 pb-4 text-center bg-primary">
                                <a href="index.html">
                                    <span><img src="dist/assets/images/logo.png" alt="" height="18"></span>
                                </a>
                            </div>

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center mt-0 font-weight-bold">Sign In</h4>
                                    <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>
                                </div>

                                <form  method="post" action="{{path_for('auth.login.post')}}">

                                    <div class="form-group">
                                        <label for="emailaddress">Email address</label>
                                        <input class="form-control" type="email" name="email" id="emailaddress" value="{% if(old.email) %}{{old.email}}{% endif %}" required="" placeholder="Enter your email">
                                    </div>

                                    <div class="form-group">
                                        <a href="pages-recoverpw.html" class="text-muted float-right"><small>Forgot your password?</small></a>
                                        <label for="password">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password">
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                            <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-primary" type="submit"> Log In </button>
                                    </div>

                                </form>
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->
        <!-- bundle -->
        <script src="dist/assets/js/vendor.min.js"></script>
        <script src="dist/assets/js/app.min.js"></script>
        
    </body>
</html>
