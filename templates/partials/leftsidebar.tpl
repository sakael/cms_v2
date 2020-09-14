<div
	class="left-side-menu">
	<!-- LOGO -->
	<a href="index.html" class="logo text-center logo-light">
		<span class="logo-lg">
			<img src="/dist/assets/images/logo.png" alt="" height="16"/>
		</span>
		<span class="logo-sm">
			<img src="/dist/assets/images/logo_sm.png" alt="" height="16"/>
		</span>
	</a>
	<!-- LOGO -->
	<a href="index.html" class="logo text-center logo-dark">
		<span class="logo-lg">
			<img src="/dist/assets/images/logo-dark.png" alt="" height="16"/>
		</span>
		<span class="logo-sm">
			<img src="/dist/assets/images/logo_sm_dark.png" alt="" height="16"/>
		</span>
	</a>
	<div
		class="h-100" id="left-side-menu-container" data-simplebar>
		<!--- Sidemenu -->
		<ul class="metismenu side-nav">
			<li class="side-nav-title side-nav-item">Navigation</li>
			<li class="side-nav-item">
				<a href="{{path_for('home')}}" class="side-nav-link">
					<i class="uil-home-alt"></i>
					<span>
						Dashboards
					</span>
				</a>
			</li>
			<li class="side-nav-title side-nav-item mt-3">Productgegevens</li>
			<li class="side-nav-item {% if active_menu == 'products' %}mm-active{% endif %}">
				<a href="javascript: void(0);" class="side-nav-link">
					<i class="uil uil-box"></i>
					<span>
						Artikelen
					</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					{% if user.checkPermissionByRouteName('ProductsIndex') or auth.user.super%}
						<li>
							<a href="{{path_for('ProductsIndex')}}">Alle Artikelen</a>
						</li>
					{% endif %}
					{% if user.checkPermissionByRouteName('Attributes.GetIndex') or auth.user.super%}
						<li>
							<a href="{{path_for('Products.Writeoff.GetIndex')}}">Afschrijvingen</a>
						</li>
					{% endif %}
					{% if user.checkPermissionByRouteName('Reviews.Get.New') or auth.user.super%}
						<li>
							<a href="{{path_for('Reviews.Get.New')}}">Reviews</a>
						</li>
					{% endif %}
				</ul>
			</li>
			<li class="side-nav-item {% if active_menu == 'attributes' or active_menu == 'attributes_group'%}mm-active{% endif %}">
				<a href="javascript: void(0);" class="side-nav-link">
					<i class=" uil-dialpad-alt"></i>
					<span>
						Attributen
					</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					{% if user.checkPermissionByRouteName('Attributes.GetIndex') or auth.user.super%}
						<li class="{% if active_menu == 'attributes_group' %}active{% endif %}">
							<a href="{{path_for('AttributeGroups.GetIndex')}}">Attribuut groepen</a>
						</li>
					{% endif %}
					{% if user.checkPermissionByRouteName('Attributes.GetIndex') or auth.user.super%}
						<li class="{% if active_menu == 'attributes' %}active{% endif %}">
							<a href="{{path_for('Attributes.GetIndex')}}">Attributen</a>
						</li>
					{% endif %}
				</ul>
			</li>

			<li class="side-nav-item {% if active_menu == 'brands' or active_menu == 'types'%}mm-active{% endif %}">
				<a href="javascript: void(0);" class="side-nav-link">
					<i class="uil-store"></i>
					<span>
						Merken / Types
					</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					{% if user.checkPermissionByRouteName('Attributes.GetIndex') or auth.user.super%}
						<li class="{% if active_menu == 'brands' %}active{% endif %}">
							<a href="{{path_for('Brands.GetIndex')}}">Merken</a>
						</li>
					{% endif %}
					{% if user.checkPermissionByRouteName('Attributes.GetIndex') or auth.user.super%}
						<li class="{% if active_menu == 'types' %}active{% endif %}">
							<a href="{{path_for('Types.GetIndex')}}">Types</a>
						</li>
					{% endif %}
				</ul>
			</li>
			<li class="side-nav-item ">
				<a href="javascript: void(0);" class="side-nav-link">
					<i class="dripicons-network-3"></i>
					<span>
						Categorieën
					</span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level " aria-expanded="false">
					{% if user.checkPermissionByRouteName('Categories.GetIndex') or auth.user.super%}
						<li>
							<a href="{{path_for('Categories.GetIndex')}}">Alle Categorieën</a>
						</li>
					{% endif %}
				</ul>
			</li>
			{% if user.checkPermissionByRouteName('BarcodeGet') or auth.user.super%}
				<li class="side-nav-item">
					<a href="{{path_for('BarcodeGet')}}" class="side-nav-link">
						<i class="mdi mdi-barcode-scan"></i>
						<span>
							Barcode
						</span>
					</a>
				</li>
			{% endif %}
			<li class="side-nav-title side-nav-item mt-3">Persoonlijk</li>
			<li class=" side-nav-item  ">
				<small>
					<a href="{{path_for('notes.all')}}" class="side-nav-link">
						<i class="mdi mdi-message-processing-outline"></i>
						Berichten ( <span class="notes_count {% if notes.notes_count > 0 %} text-danger {% endif %}">{{ notes.notes_count }}</span> )</a>
				</small>
			</li>
		</ul>
	</div>
	<!-- menu -->
	<!-- Sidebar -left -->
</div>
