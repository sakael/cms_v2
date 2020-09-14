<div class="navbar-custom">
	<ul class="list-unstyled topbar-right-menu float-right mb-0">
		<li class="dropdown notification-list d-lg-none">
			<a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
				<i class="dripicons-search noti-icon"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
				<form class="p-3">
					<input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username"/>
				</form>
			</div>
		</li>
		{% if language == 'nl' %}
			<li class="dropdown notification-list topbar-dropdown">
				<a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
					<img src="/assets/images/flags/dutch.jpg" alt="user-image" class="mr-0 mr-sm-1" height="12">
					<span class="align-middle d-none d-sm-inline-block">Nederlands</span>
					<i class="mdi mdi-chevron-down d-none d-sm-inline-block align-middle"></i>
				</a>
				<div
					class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu">
					<!-- item-->
					<a href="{{base_url()}}{{current_path()}}/?lang=en" class="dropdown-item notify-item">
						<img src="/assets/images/flags/us.jpg" alt="user-image" class="mr-1" height="12">
						<span class="align-middle">Engels</span>
					</a>
				</div>
			</li>
		{% else %}
			<li class="dropdown notification-list topbar-dropdown">
				<a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
					<img src="/assets/images/flags/us.jpg" alt="user-image" class="mr-0 mr-sm-1" height="12">
					<span class="align-middle d-none d-sm-inline-block">Engels</span>
					<i class="mdi mdi-chevron-down d-none d-sm-inline-block align-middle"></i>
				</a>
				<div
					class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu">
					<!-- item-->
					<a href="{{base_url()}}{{current_path()}}/?lang=nl" class="dropdown-item notify-item">
						<img src="/assets/images/flags/dutch.jpg" alt="user-image" class="mr-1" height="12">
						<span class="align-middle">Nederlands</span>
					</a>
				</div>
			</li>
		{% endif %}

		<li class="dropdown notification-list">
			<a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
				<i class="dripicons-bell noti-icon"></i>
				<span class="noti-icon-badge"></span>
			</a>
			<div
				class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-lg">
				<!-- item-->
				<div class="dropdown-item noti-title">
					<h5 class="m-0">
						<span class="float-right">
							<a href="javascript: void(0);" class="text-dark">
								<small>Clear All</small>
							</a>
						</span>Notification
					</h5>
				</div>

				<div
					style="max-height: 230px;" data-simplebar>
					<!-- item-->
					<a href="javascript:void(0);" class="dropdown-item notify-item">
						<div class="notify-icon bg-primary">
							<i class="mdi mdi-comment-account-outline"></i>
						</div>
						<p class="notify-details">
							Caleb Flakelar commented on Admin
							<small class="text-muted">1 min ago</small>
						</p>
					</a>

					<!-- item-->
					<a href="javascript:void(0);" class="dropdown-item notify-item">
						<div class="notify-icon bg-info">
							<i class="mdi mdi-account-plus"></i>
						</div>
						<p class="notify-details">
							New user registered.
							<small class="text-muted">5 hours ago</small>
						</p>
					</a>

					<!-- item-->
					<a href="javascript:void(0);" class="dropdown-item notify-item">
						<div class="notify-icon">
							<img src="/assets/images/users/avatar-2.jpg" class="img-fluid rounded-circle" alt=""/>
						</div>
						<p class="notify-details">Cristina Pride</p>
						<p class="text-muted mb-0 user-msg">
							<small>Hi, How are you? What about our next meeting</small>
						</p>
					</a>

					<!-- item-->
					<a href="javascript:void(0);" class="dropdown-item notify-item">
						<div class="notify-icon bg-primary">
							<i class="mdi mdi-comment-account-outline"></i>
						</div>
						<p class="notify-details">
							Caleb Flakelar commented on Admin
							<small class="text-muted">4 days ago</small>
						</p>
					</a>

					<!-- item-->
					<a href="javascript:void(0);" class="dropdown-item notify-item">
						<div class="notify-icon">
							<img src="/assets/images/users/avatar-4.jpg" class="img-fluid rounded-circle" alt=""/>
						</div>
						<p class="notify-details">Karen Robinson</p>
						<p class="text-muted mb-0 user-msg">
							<small>Wow ! this admin looks good and awesome design</small>
						</p>
					</a>

					<!-- item-->
					<a href="javascript:void(0);" class="dropdown-item notify-item">
						<div class="notify-icon bg-info">
							<i class="mdi mdi-heart"></i>
						</div>
						<p class="notify-details">
							Carlos Crouch liked
							<b>Admin</b>
							<small class="text-muted">13 days ago</small>
						</p>
					</a>
				</div>

				<!-- All-->
				<a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item notify-all">
					View All
				</a>
			</div>
		</li>
		<li class="dropdown notification-list">
			<a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
				<span class="account-user-avatar">
					<img src="/assets/images/users/avatar-1.jpg" alt="user-image" class="rounded-circle"/>
				</span>
				<span class="account-user-name text-capitalize pt-1">{{auth.user.name}}
					{{auth.user.lastname}}</span>
			</a>
			<div
				class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
				<!-- item-->
				<div class="dropdown-header noti-title">
					<h6 class="text-overflow m-0">Welcome !</h6>
				</div>

				<!-- item-->
				<a href="{{path_for('auth.account')}}" class="dropdown-item notify-item">
					<i class="mdi mdi-account-circle mr-1"></i>
					<span>My Account</span>
				</a>

				<!-- item-->
				<a href="{{path_for('auth.logout')}}" class="dropdown-item notify-item">
					<i class="mdi mdi-logout mr-1"></i>
					<span>Logout</span>
				</a>
			</div>
		</li>
	</ul>
	<button class="button-menu-mobile open-left disable-btn">
		<i class="mdi mdi-menu"></i>
	</button>
	<div class="app-search dropdown d-none d-lg-block" id="live-search-site">
		<live-search-component></live-search-component>
	</div>
</div>
