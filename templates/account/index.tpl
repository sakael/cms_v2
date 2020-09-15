{% extends "layouts/base.tpl" %}
{% block cssfiles_before %}
{% endblock %}
{% block page_title %}{{auth.user.name}} {{auth.user.lastname}}{% endblock %}
{% block content %}
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">{{auth.user.name}} {{auth.user.lastname}}</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                   <form id="register-form" method="post" action="{{path_for('auth.account.post')}}" autocomplete="off" class="p-3">
                    <input type="hidden" name="_METHOD" value="POST">
                    <input type="hidden" name="id" value="{{auth.user.id}}">
                    <div class="form-group{{ errors.name ? ' has-danger': ''}}">
                        <label for="name" class="label-material">Name</label>
                        <input id="name" type="text" name="name" class="form-control" value="{% if(old.name) %}{{old.name}}{% else %}{{auth.user.name}} {% endif %}" required>
                        {% if errors.name %}
                            <small class="form-control-feedback ml-2">{{errors.name |first}}</small>
                        {% endif %}
                    </div>
                    <div class="form-group{{ errors.lastname ? ' has-danger': ''}}">
                        <label for="lastname" class="label-material">Last Name</label>
                        <input id="lastname" type="text" name="lastname" class="form-control" value="{% if(old.lastname) %}{{old.lastname}}{% else %}{{auth.user.lastname}}{% endif %}" required>
                        {% if errors.lastname %}
                            <small class="form-control-feedback ml-2">{{errors.lastname |first}}</small>
                        {% endif %}
                    </div>
                    <div class="form-group{{ errors.email ? ' has-danger': ''}}">
                        <label for="email" class="label-material">Email</label>
                        <input id="email" type="text" name="email" class="form-control" value="{% if(old.email) %}{{old.email}}{% else %}{{auth.user.email}}{% endif %}" required>
                        {% if errors.email %}
                            <small class="form-control-feedback ml-2">{{errors.email |first}}</small>
                        {% endif %}
                    </div>
                    <div class="form-group{{ errors.old_password ? ' has-danger': ''}}">
                        <label for="old_password" class="label-material">Huidig wachtwoord</label>
                        <input id="old_password" type="password" name="old_password" class="form-control" >
                        {% if errors.old_password %}
                            <small class="form-control-feedback ml-2">{{errors.old_password |first}}</small>
                        {% endif %}
                    </div>
                    <div class="form-group{{ errors.password ? ' has-danger': ''}}">
                        <label for="password" class="label-material">Nieuw Wachtwoord</label>
                        <input id="password" type="password" name="password" class="form-control" >
                        {% if errors.password %}
                            <small class="form-control-feedback ml-2">{{errors.password |first}}</small>
                        {% endif %}
                    </div>
                    <button type="submit" id="login" class="btn btn-primary">bijwerken</button>
                </form>

            </div>
        </div>
    </div>
</div>

{% endblock %}
