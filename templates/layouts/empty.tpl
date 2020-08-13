<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>123BestDeal CMS | {{page_title}}{% block page_title %} {% endblock %}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="" name="description" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="/dist/assets/images/favicon.ico" />
    <!-- third party css -->
    <link href="/dist/assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- third party css end -->

    <!-- App css -->
    <link href="/dist/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/dist/assets/css/app.min.css" rel="stylesheet" type="text/css" id="light-style" />
    <link href="/dist/assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />
    {% block cssfiles %}{% endblock %}
  </head>
  <body class="loading authentication-bg" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
        {% include 'partials/flash.tpl' %}
        {% block content %}{% endblock %}
        <!-- end page -->
        <!-- bundle -->
        <script src="/dist/assets/js/vendor.min.js"></script>
        <script src="/dist/assets/js/app.min.js"></script>
    </body>
</html>