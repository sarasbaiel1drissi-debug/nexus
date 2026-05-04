<!DOCTYPE html>
<html lang="en">
	<head>
		<title>@yield('page') | {{ config('app.name') }}</title>
		<meta charset="utf-8" />		
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>

        @yield('page-css')
	</head>
	<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<div id="kt_app_header" class="app-header">
					<div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
						<div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show sidebar menu">
							<div class="btn btn-icon btn-color-white btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
								<i class="ki-duotone ki-abstract-14 fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</div>
						</div>
						<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-5 me-lg-0">
							<a href="../../demo46/dist/index.html">
								<img alt="Logo" src="{{asset ('assets/media/logos/demo46.svg')}}" class="d-none d-sm-block" />
								<img alt="Logo" src="{{asset ('assets/media/logos/demo46-small.svg')}}" class="d-block d-sm-none" />
							</a>
						</div>
						<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
							<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
								@include('include.menu')
							</div>
							<div class="app-navbar flex-shrink-0">
								<div class="d-flex align-items-center ms-1 ms-lg-3">
									<a href="#" class="btn btn-flex flex-center btn-primary h-35px h-md-40px" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">Upgrade
									<span class="d-none d-sm-block ps-1">Plan</span></a>
								</div>
								<div class="app-navbar-item ms-1 ms-lg-3">
									<div class="btn btn-icon btn-custom btn-color-white btn-active-color-primary w-35px h-35px w-md-40px h-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<i class="ki-duotone ki-picture fs-1">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</div>
									<div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">
										<div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('assets/media/misc/menu-header-bg.jpg')">
											<h3 class="text-white fw-semibold px-9 mt-10 mb-6">
                                                Notifications
											    {{--<span class="fs-8 opacity-75 ps-3">24 reports</span>  --}}
                                            </h3>
											<ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
												<li class="nav-item">
													<a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#kt_topbar_notifications_1">Alerts</a>
												</li>
												<li class="nav-item">
													<a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab" href="#kt_topbar_notifications_2">Updates</a>
												</li>
											</ul>
										</div>
										<div class="tab-content">
											<div class="tab-pane fade" id="kt_topbar_notifications_1" role="tabpanel">
												<div class="scroll-y mh-325px my-5 px-8">
													
												</div>
												<div class="py-3 text-center border-top">
													<a href="../../demo46/dist/pages/user-profile/activity.html" class="btn btn-color-gray-600 btn-active-color-primary">View All
													<i class="ki-duotone ki-arrow-right fs-5">
														<span class="path1"></span>
														<span class="path2"></span>
													</i></a>
												</div>
											</div>
											<div class="tab-pane fade show active" id="kt_topbar_notifications_2" role="tabpanel">
												<div class="d-flex flex-column px-9">
													<div class="pt-10 pb-0">
														<h3 class="text-dark text-center fw-bold">Get Pro Access</h3>
														<div class="text-center text-gray-600 fw-semibold pt-1">Outlines keep you honest. They stoping you from amazing poorly about drive</div>
														<div class="text-center mt-5 mb-9">
															<a href="#" class="btn btn-sm btn-primary px-6" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">Upgrade</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="app-navbar-item ms-5" id="kt_header_user_menu_toggle">
									<div class="cursor-pointer symbol symbol-35px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<img class="symbol symbol-35px symbol-md-40px" src="{{asset ('assets/media/avatars/300-3.jpg')}}" alt="user" />
									</div>
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
										<div class="menu-item px-3">
											<div class="menu-content d-flex align-items-center px-3">
												<div class="symbol symbol-50px me-5">
													<img alt="Logo" src="{{asset ('assets/media/avatars/300-3.jpg')}}" />
												</div>
												<div class="d-flex flex-column">
													<div class="fw-bold d-flex align-items-center fs-5">Max Smith
													<span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span></div>
													<a href="#" class="fw-semibold text-muted text-hover-primary fs-7">max@kt.com</a>
												</div>
											</div>
										</div>
										<div class="separator my-2"></div>
										<div class="menu-item px-5">
											<a href="../../demo46/dist/account/overview.html" class="menu-link px-5">My Profile</a>
										</div>
										<div class="menu-item px-5">
											<a href="../../demo46/dist/apps/projects/list.html" class="menu-link px-5">
												<span class="menu-text">My Projects</span>
												<span class="menu-badge">
													<span class="badge badge-light-danger badge-circle fw-bold fs-7">3</span>
												</span>
											</a>
										</div>
										<div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
											<a href="#" class="menu-link px-5">
												<span class="menu-title">My Subscription</span>
												<span class="menu-arrow"></span>
											</a>
											<div class="menu-sub menu-sub-dropdown w-175px py-4">
												<div class="menu-item px-3">
													<a href="../../demo46/dist/account/referrals.html" class="menu-link px-5">Referrals</a>
												</div>
												<div class="menu-item px-3">
													<a href="../../demo46/dist/account/billing.html" class="menu-link px-5">Billing</a>
												</div>
												<div class="menu-item px-3">
													<a href="../../demo46/dist/account/statements.html" class="menu-link px-5">Payments</a>
												</div>
												<div class="menu-item px-3">
													<a href="../../demo46/dist/account/statements.html" class="menu-link d-flex flex-stack px-5">Statements
													<span class="ms-2 lh-0" data-bs-toggle="tooltip" title="View your statements">
														<i class="ki-duotone ki-information-5 fs-5">
															<span class="path1"></span>
															<span class="path2"></span>
															<span class="path3"></span>
														</i>
													</span></a>
												</div>
												<div class="separator my-2"></div>
												<div class="menu-item px-3">
													<div class="menu-content px-3">
														<label class="form-check form-switch form-check-custom form-check-solid">
															<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
															<span class="form-check-label text-muted fs-7">Notifications</span>
														</label>
													</div>
												</div>
											</div>
										</div>
										<div class="menu-item px-5">
											<a href="../../demo46/dist/account/statements.html" class="menu-link px-5">My Statements</a>
										</div>
										<div class="separator my-2"></div>
										<div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
											<a href="#" class="menu-link px-5">
												<span class="menu-title position-relative">Mode
												<span class="ms-5 position-absolute translate-middle-y top-50 end-0">
													<i class="ki-duotone ki-night-day theme-light-show fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
														<span class="path4"></span>
														<span class="path5"></span>
														<span class="path6"></span>
														<span class="path7"></span>
														<span class="path8"></span>
														<span class="path9"></span>
														<span class="path10"></span>
													</i>
													<i class="ki-duotone ki-moon theme-dark-show fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</span></span>
											</a>
											<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
												<div class="menu-item px-3 my-0">
													<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
														<span class="menu-icon" data-kt-element="icon">
															<i class="ki-duotone ki-night-day fs-2">
																<span class="path1"></span>
																<span class="path2"></span>
																<span class="path3"></span>
																<span class="path4"></span>
																<span class="path5"></span>
																<span class="path6"></span>
																<span class="path7"></span>
																<span class="path8"></span>
																<span class="path9"></span>
																<span class="path10"></span>
															</i>
														</span>
														<span class="menu-title">Light</span>
													</a>
												</div>
												<div class="menu-item px-3 my-0">
													<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
														<span class="menu-icon" data-kt-element="icon">
															<i class="ki-duotone ki-moon fs-2">
																<span class="path1"></span>
																<span class="path2"></span>
															</i>
														</span>
														<span class="menu-title">Dark</span>
													</a>
												</div>
												<div class="menu-item px-3 my-0">
													<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
														<span class="menu-icon" data-kt-element="icon">
															<i class="ki-duotone ki-screen fs-2">
																<span class="path1"></span>
																<span class="path2"></span>
																<span class="path3"></span>
																<span class="path4"></span>
															</i>
														</span>
														<span class="menu-title">System</span>
													</a>
												</div>
											</div>
										</div>
										<div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
											<a href="#" class="menu-link px-5">
												<span class="menu-title position-relative">Language
												<span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">English
												<img class="w-15px h-15px rounded-1 ms-2" src="{{asset ('assets/media/flags/united-states.svg')}}" alt="" /></span></span>
											</a>
											<div class="menu-sub menu-sub-dropdown w-175px py-4">
												<div class="menu-item px-3">
													<a href="../../demo46/dist/account/settings.html" class="menu-link d-flex px-5 active">
													<span class="symbol symbol-20px me-4">
														<img class="rounded-1" src="{{asset ('assets/media/flags/united-states.svg')}}" alt="" />
													</span>English</a>
												</div>
												<div class="menu-item px-3">
													<a href="../../demo46/dist/account/settings.html" class="menu-link d-flex px-5">
													<span class="symbol symbol-20px me-4">
														<img class="rounded-1" src="{{asset ('assets/media/flags/spain.svg')}}" alt="" />
													</span>Spanish</a>
												</div>
												<div class="menu-item px-3">
													<a href="../../demo46/dist/account/settings.html" class="menu-link d-flex px-5">
													<span class="symbol symbol-20px me-4">
														<img class="rounded-1" src="{{asset ('assets/media/flags/germany.svg')}}" alt="" />
													</span>German</a>
												</div>
												<div class="menu-item px-3">
													<a href="../../demo46/dist/account/settings.html" class="menu-link d-flex px-5">
													<span class="symbol symbol-20px me-4">
														<img class="rounded-1" src="{{asset ('assets/media/flags/japan.svg')}}" alt="" />
													</span>Japanese</a>
												</div>
												<div class="menu-item px-3">
													<a href="../../demo46/dist/account/settings.html" class="menu-link d-flex px-5">
													<span class="symbol symbol-20px me-4">
														<img class="rounded-1" src="{{asset ('assets/media/flags/france.svg')}}" alt="" />
													</span>French</a>
												</div>
											</div>
										</div>
										<div class="menu-item px-5 my-1">
											<a href="../../demo46/dist/account/settings.html" class="menu-link px-5">Account Settings</a>
										</div>
										<div class="menu-item px-5">
											<a href="../../demo46/dist/authentication/layouts/corporate/sign-in.html" class="menu-link px-5">Sign Out</a>
										</div>
									</div>
								</div>
								<div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
									<div class="btn btn-icon btn-color-white btn-active-color-primary w-30px h-30px w-md-35px h-md-35px" id="kt_app_header_menu_toggle">
										<i class="ki-duotone ki-text-align-left fs-2 fs-md-1 fw-bold">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
										</i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="app-wrapper d-flex" id="kt_app_wrapper">
					<div class="app-container container-fluid">
						<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="auto" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
							<div class="app-sidebar-navbar flex-grow-1 hover-scroll-overlay-y" id="kt_app_sidebar_primary_navbar" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_primary_footer" data-kt-scroll-wrappers="#kt_app_sidebar_primary_navbar" data-kt-scroll-offset="5px">
								<div class="app-navbar flex-column flex-center py-4">
									<a href="../../demo46/dist/pages/user-profile/overview.html" class="btn btn-icon btn-default mx-auto mb-4">
										<div class="symbol symbol-40px">
											<img src="{{asset ('assets/media/avatars/300-2.jpg')}}" class="" alt="" />
										</div>
									</a>
									<a href="../../demo46/dist/account/billing.html" class="btn btn-icon btn-default mx-auto mb-4 active">
										<div class="symbol symbol-40px">
											<img src="{{asset ('assets/media/avatars/300-7.jpg')}}" class="" alt="" />
										</div>
									</a>
									<a href="../../demo46/dist/account/activity.html" class="btn btn-icon btn-default mx-auto mb-4">
										<div class="symbol symbol-40px">
											<img src="{{asset ('assets/media/avatars/300-10.jpg')}}" class="" alt="" />
										</div>
									</a>
									<a href="../../demo46/dist/account/overview.html" class="btn btn-icon btn-default mx-auto mb-4">
										<div class="symbol symbol-40px">
											<img src="{{asset ('assets/media/avatars/300-1.jpg')}}" class="" alt="" />
										</div>
									</a>
									<a href="../../demo46/dist/account/settings.html" class="btn btn-icon btn-default mx-auto mb-4">
										<div class="symbol symbol-40px">
											<img src="{{asset ('assets/media/avatars/300-3.jpg')}}" class="" alt="" />
										</div>
									</a>
									<a href="../../demo46/dist/apps/projects/users.html" class="btn btn-icon btn-default mx-auto mb-4">
										<div class="symbol symbol-40px">
											<img src="{{asset ('assets/media/avatars/300-21.jpg')}}" class="" alt="" />
										</div>
									</a>
									<a href="../../demo46/dist/apps/projects/targets.html" class="btn btn-icon btn-default mx-auto mb-4">
										<div class="symbol symbol-40px">
											<img src="{{asset ('assets/media/avatars/300-6.jpg')}}" class="" alt="" />
										</div>
									</a>
									<a href="../../demo46/dist/apps/projects/files.html" class="btn btn-icon btn-default mx-auto mb-4">
										<div class="symbol symbol-40px">
											<img src="{{asset ('assets/media/avatars/300-13.jpg')}}" class="" alt="" />
										</div>
									</a>
									<a href="../../demo46/dist/apps/user-management/users/list.html" class="btn btn-icon btn-default mx-auto mb-4">
										<div class="symbol symbol-40px">
											<img src="{{asset ('assets/media/avatars/300-20.jpg')}}" class="" alt="" />
										</div>
									</a>
									<div class="separator mb-4 border-gray-300 mx-5"></div>
									<div class="app-navbar-item flex-center">
										<a href="../../demo46/dist/apps/calendar.html" class="btn btn-icon btn-color-gray-600 bg-gray-200 btn-active-primary w-40px h-40px btn-accent">
											<i class="ki-duotone ki-plus fs-1"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_toolbar" class="app-toolbar">
									<div class="d-flex flex-stack flex-row-fluid">
										<div class="d-flex flex-column flex-row-fluid">
											@yield('breadcrump')
											<div class="page-title d-flex align-items-center me-3">
												<h1 class="page-heading d-flex text-dark fw-bolder fs-1 flex-column justify-content-center my-0">@yield('page')</h1>
											</div>
										</div>
										<div class="d-flex align-items-center gap-3 gap-lg-5">
											@yield('actions')
										</div>
									</div>
								</div>
								<div id="kt_app_content" class="app-content">

									@if(session('flash.message'))
										<div class="alert alert-dismissable alert-{{ session('flash.type') }} p-3">
											<span>{{ session('flash.message') }}</span>
											<button type="button" class="btn-close me-2" data-bs-dismiss="alert" aria-label="Close"></button>
										</div>
									@endif

									@if($errors->any())
										<div class="alert alert-danger">
											<ul class="mb-0">
												@foreach ($errors->all() as $error)
												<li>{{ $error }}</li>
												@endforeach
											</ul>
										</div>
									@endif

									<div class="card">
										


                                        @yield('content')



									</div>
								</div>
							</div>
							<div id="kt_app_footer" class="app-footer d-flex flex-column flex-md-row align-items-center flex-center flex-md-stack">
								<div class="text-dark order-2 order-md-1">
									<span class="text-gray-400 fw-semibold me-1">2023&copy;</span>
									<a href="https://keenthemes.com" target="_blank" class="text-gray-400 text-hover-primary">Keenthemes</a>
								</div>
								<ul class="menu menu-gray-400 menu-hover-primary fw-semibold order-1">
									<li class="menu-item">
										<a href="https://keenthemes.com" target="_blank" class="menu-link px-2">About</a>
									</li>
									<li class="menu-item">
										<a href="https://devs.keenthemes.com" target="_blank" class="menu-link px-2">Support</a>
									</li>
									<li class="menu-item">
										<a href="https://1.envato.market/EA4JP" target="_blank" class="menu-link px-2">Purchase</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js')}}"></script>
		<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
        @yield('page-js')
		<script>$("#kt_datatable_zero_configuration").DataTable();</script>
	</body>
</html>