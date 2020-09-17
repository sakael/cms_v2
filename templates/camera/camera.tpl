{% extends "layouts/base.tpl" %}

{% block page_title %}
	{{page_title}}
{% endblock %}
{% block content %}
	<div class="row mt-3 mb-3">
		<div class="col-12">
			<img style="width:100%" src="http://37.153.194.233:554/cgi-bin/CGIProxy.fcgi?cmd=snapPicture2&usr=admin&pwd=!Y@mY@m21!&t=" name="refresh" id="refresh" onload='reload(this)' onerror='reload(this)'>
		</div>
		<!-- end col-->
	</div>
	<!-- end row-->
{% endblock %}

{% block javascript %}
	<!-- specific page js file -->
	<script src="/assets/js/setimmediate.js"></script>
	<script language="JavaScript" type="text/javascript">
		function reload() {
            setImmediate('reloadImg("refresh")')
        };
        function reloadImg(id) {
            var obj = document.getElementById(id);
            var date = new Date();
            obj.src = "http://37.153.194.233:554/cgi-bin/CGIProxy.fcgi?cmd=snapPicture2&usr=admin&pwd=!Y@mY@m21!&t=" + date.getTime();
        }
	</script>
{% endblock %}
