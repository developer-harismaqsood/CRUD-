<template>
	<div>
		<!-- Main sidebar -->
		<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">

			<!-- Sidebar content -->
			<div class="sidebar-content">

				<!-- Sidebar header -->
				<div class="sidebar-section">
					<div class="sidebar-section-body d-flex justify-content-center">
						<h5 class="sidebar-resize-hide flex-grow-1 my-auto">Navigation</h5>

						<div>
							<button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
								<i class="ph-arrows-left-right"></i>
							</button>

							<button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
								<i class="ph-x"></i>
							</button>

							
						</div>
					</div>
				</div>
				<!-- /sidebar header -->


				<!-- Main navigation -->
				<div class="sidebar-section">

					<ul class="nav nav-sidebar" data-nav-type="accordion">
						
						<li class="nav-item">
							<a href="dashboard" class="nav-link">
								<i class="ph-house"></i>
								<span>Dashboard</span>
							</a>
						</li>
					</ul>

					<ul class="nav nav-sidebar" data-nav-type="accordion">
						<!-- Main -->
						<li class="nav-item-header pt-0">
							<div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Management</div>
							<i class="ph-dots-three sidebar-resize-show"></i>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link">
								<i class="ph-microsoft-teams-logo"></i>
								<span>MANAGEMENT</span>
							</a>
							<ul class="nav-group-sub collapse">	
								<li class="nav-item ml-5"><a href="company/company-management" class="nav-link">Compay Managment</a></li>
								<li class="nav-item ml-5"><a href="empolyee/empolyee-management" class="nav-link">Empolyee Managment</a></li>
								<li class="nav-item ml-5"><a href="user/user-management" class="nav-link">User Managment</a></li>
							</ul>
						</li>
					</ul>

				</div>
				<!-- /main navigation -->

			</div>
			<!-- /sidebar content -->
		</div>
		
	</div>
</template>