
@php
$user = Auth::user();
@endphp

<div class="nav-header">
    <a href="/dashboard" class="brand-logo">
		<img class="logo-abbr" width="100%" height="55" src="/assets/logo/logo.jpg" alt="Sri Ahobila Mutt Logo">
    
      
    </a>
    <div class="nav-control">
        <div class="hamburger">
			<span class="line"></span><span class="line"></span><span class="line"></span>
		</div>
    </div>
</div>

<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">
                    <div class="dashboard_bar">
                    
                   
                            <x-page-title />
                    

                    </div>
                </div>
                <style>
                    /* .navbar-expand .navbar-nav .dropdown-menu{
                        position:inherit;
                    } */
                </style>
                <ul class="navbar-nav header-right">
                    
                    {{-- <li class="nav-item d-flex align-items-center">
                        <div class="input-group search-area">
                            <input type="text" class="form-control" placeholder="Search here...">
                            <span class="input-group-text"><a href="javascript:void(0)"><i class="flaticon-381-search-2"></i></a></span>
                        </div>
                        <div class="input-group search-area">
                        <select class="default-select form-control wide"
                        aria-label="Default select example" name="type">
                        <option selected disabled>select</option>
                        @foreach ($doc_types as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                    </li> --}}
                    {{-- <li class="nav-item d-flex align-items-center">
                        <form action="{{ url('/') }}/view_doc_first_submit" method="get" enctype="multipart/form-data"
                        onsubmit="return validateForm()">
                      @csrf
                      <div class="input-group search-area">
                          <select class="default-select form-control wide" 
                                  aria-label="Default select example" name="type" id="docTypeDropdown"
                                  style="z-index: 9999;" required>
                              <option selected disabled>Select Doc type</option>
                              @foreach ($doc_types as $item)
                                  <option value="{{ $item->name }}">{{ $item->name }}</option>
                              @endforeach
                          </select>
                          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                      </div>
                  </form>
                  
                  <script>
                      function validateForm() {
                          var dropdown = document.getElementById('docTypeDropdown');
                          if (dropdown.value == null || dropdown.value == "") {
                              alert("Please select a document type.");
                              return false;
                          }
                          return true; 
                      }
                  </script>
                  
                    </li> --}}
                    {{-- <div class="input-group">
                        <select class="default-select form-control wide bleft" >
                            <option selected>Choose...</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div> --}}

                    
                    {{-- <li class="nav-item dropdown notification_dropdown">
                        <a class="nav-link bell dz-theme-mode" href="javascript:void(0);">
                            <i id="icon-light" class="fas fa-sun"></i>
                            <i id="icon-dark" class="fas fa-moon"></i>
                        </a>
                    </li> --}}
                    @if($user && ($user->hasPermission('View Compliance Notifications') || $user->hasPermission('View Recipient Notifications')))
                    <li class="nav-item dropdown notification_dropdown">
                        <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" title="Notifications">
                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M23.3333 19.8333H23.1187C23.2568 19.4597 23.3295 19.065 23.3333 18.6666V12.8333C23.3294 10.7663 22.6402 8.75902 21.3735 7.12565C20.1068 5.49228 18.3343 4.32508 16.3333 3.80679V3.49996C16.3333 2.88112 16.0875 2.28763 15.6499 1.85004C15.2123 1.41246 14.6188 1.16663 14 1.16663C13.3812 1.16663 12.7877 1.41246 12.3501 1.85004C11.9125 2.28763 11.6667 2.88112 11.6667 3.49996V3.80679C9.66574 4.32508 7.89317 5.49228 6.6265 7.12565C5.35983 8.75902 4.67058 10.7663 4.66667 12.8333V18.6666C4.67053 19.065 4.74316 19.4597 4.88133 19.8333H4.66667C4.35725 19.8333 4.0605 19.9562 3.84171 20.175C3.62292 20.3938 3.5 20.6905 3.5 21C3.5 21.3094 3.62292 21.6061 3.84171 21.8249C4.0605 22.0437 4.35725 22.1666 4.66667 22.1666H23.3333C23.6428 22.1666 23.9395 22.0437 24.1583 21.8249C24.3771 21.6061 24.5 21.3094 24.5 21C24.5 20.6905 24.3771 20.3938 24.1583 20.175C23.9395 19.9562 23.6428 19.8333 23.3333 19.8333Z" fill="#717579"/>
                                <path d="M9.9819 24.5C10.3863 25.2088 10.971 25.7981 11.6766 26.2079C12.3823 26.6178 13.1838 26.8337 13.9999 26.8337C14.816 26.8337 15.6175 26.6178 16.3232 26.2079C17.0288 25.7981 17.6135 25.2088 18.0179 24.5H9.9819Z" fill="#717579"/>
                            </svg>
                            <span class="badge light text-white bg-warning rounded-circle">{{ $notificationsCount }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div id="DZ_W_Notification1" class="widget-media dlab-scroll p-3" style="height:380px;">
                 
                  


                                <ul class="timeline">
									{{-- <h6 class="mb-1">No Notifications Present</h6> --}}
									@foreach ($notifications as $item)
									{{-- <option value="{{ $item->name }}">{{ $item->name }}</option> --}}
									<li>
                                        @if($item->compliance_id !=null)
                                        <a  href="/notifications" >
                                            @else
                                        <a  href="/notifications?type=document_assignment" >
                                            @endif

									<div class="timeline-panel">
                                        @if($item->compliance_id !=null)
										<div class="media me-2 media-info">
											CMP
										</div>
                                        @else
                                        <div class="media me-2 media-info">
											REC
										</div>
                                        @endif
										<div class="media-body">
											<h6 class="mb-1">{{ $item->message }}</h6>
											<small class="d-block">{{ $item->created_at->format('j F, Y, g:i a') }}</small>

										</div>
									</div>
                                </a> 
								</li>
								@endforeach
                                    {{-- <li>
                                    <li>
                                        <div class="timeline-panel">
                                            <div class="media me-2 media-info">
                                                KG
                                            </div>
                                            <div class="media-body">
                                                <h6 class="mb-1">Resport created successfully</h6>
                                                <small class="d-block">29 July 2024 - 02:26 PM</small>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="timeline-panel">
                                            <div class="media me-2 media-success">
                                                <i class="fa fa-home"></i>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="mb-1">New Reviewer Added</h6>
                                                <small class="d-block">29 July 2024 - 02:26 PM</small>
                                            </div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <div class="timeline-panel">
                                            <div class="media me-2 media-danger">
                                                KG
                                            </div>
                                            <div class="media-body">
                                                <h6 class="mb-1">Reviewer Status Pending </h6>
                                                <small class="d-block">29 Jan 2024 - 02:26 PM</small>
                                            </div>
                                        </div>
                                    </li> --}}
                                 
                                </ul>
                          
                            </div>
                            <a class="all-notification" href="/notifications" title="Click to see All Notifications">See all notifications <i class="ti-arrow-end"></i></a>
                        </div>
                    </li>      @endif
                    {{-- <li class="nav-item dropdown notification_dropdown">
                        <a class="nav-link bell-link " href="javascript:void(0);">
                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M27.076 6.24662C26.962 5.48439 26.5787 4.78822 25.9955 4.28434C25.4123 3.78045 24.6679 3.50219 23.8971 3.5H4.10289C3.33217 3.50219 2.58775 3.78045 2.00456 4.28434C1.42137 4.78822 1.03803 5.48439 0.924011 6.24662L14 14.7079L27.076 6.24662Z" fill="#717579"/>
                            <path d="M14.4751 16.485C14.3336 16.5765 14.1686 16.6252 14 16.6252C13.8314 16.6252 13.6664 16.5765 13.5249 16.485L0.875 8.30025V21.2721C0.875926 22.1279 1.2163 22.9484 1.82145 23.5536C2.42659 24.1587 3.24707 24.4991 4.10288 24.5H23.8971C24.7529 24.4991 25.5734 24.1587 26.1786 23.5536C26.7837 22.9484 27.1241 22.1279 27.125 21.2721V8.29938L14.4751 16.485Z" fill="#717579"/>
                        </svg>
                            <span class="badge light text-white bg-danger rounded-circle">76</span>
                        </a>
                    </li>	 --}}
                    
                    
                    {{-- <li class="nav-item dropdown notification_dropdown">
                        <a class="nav-link " href="javascript:void(0);" data-bs-toggle="dropdown">
                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.1666 5.83331H20.9999V3.49998C20.9999 3.19056 20.877 2.89381 20.6582 2.67502C20.4394 2.45623 20.1427 2.33331 19.8333 2.33331C19.5238 2.33331 19.2271 2.45623 19.0083 2.67502C18.7895 2.89381 18.6666 3.19056 18.6666 3.49998V5.83331H9.33325V3.49998C9.33325 3.19056 9.21034 2.89381 8.99154 2.67502C8.77275 2.45623 8.47601 2.33331 8.16659 2.33331C7.85717 2.33331 7.56042 2.45623 7.34163 2.67502C7.12284 2.89381 6.99992 3.19056 6.99992 3.49998V5.83331H5.83325C4.90499 5.83331 4.01476 6.20206 3.35838 6.85844C2.702 7.51482 2.33325 8.40506 2.33325 9.33331V10.5H25.6666V9.33331C25.6666 8.40506 25.2978 7.51482 24.6415 6.85844C23.9851 6.20206 23.0948 5.83331 22.1666 5.83331Z" fill="#717579"/>
                                <path d="M2.33325 22.1666C2.33325 23.0949 2.702 23.9851 3.35838 24.6415C4.01476 25.2979 4.90499 25.6666 5.83325 25.6666H22.1666C23.0948 25.6666 23.9851 25.2979 24.6415 24.6415C25.2978 23.9851 25.6666 23.0949 25.6666 22.1666V12.8333H2.33325V22.1666Z" fill="#717579"/>
                            </svg>
                            <span class="badge light text-white bg-success rounded-circle">!</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div id="DZ_W_TimeLine02" class="widget-timeline dlab-scroll style-1 ps ps--active-y p-3 height370">
                                <ul class="timeline">
                                    <li>
                                        <div class="timeline-badge primary"></div>
                                        <a class="timeline-panel text-muted" href="javascript:void(0);">
                                            <span>10 minutes ago</span>
                                            <h6 class="mb-0">Youtube, a video-sharing website, goes live <strong class="text-primary">$500</strong>.</h6>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="timeline-badge info">
                                        </div>
                                        <a class="timeline-panel text-muted" href="javascript:void(0);">
                                            <span>20 minutes ago</span>
                                            <h6 class="mb-0">New order placed <strong class="text-info">#XF-2356.</strong></h6>
                                            <p class="mb-0">Quisque a consequat ante Sit amet magna at volutapt...</p>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="timeline-badge danger">
                                        </div>
                                        <a class="timeline-panel text-muted" href="javascript:void(0);">
                                            <span>30 minutes ago</span>
                                            <h6 class="mb-0">john just buy your product <strong class="text-warning">Sell $250</strong></h6>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="timeline-badge success">
                                        </div>
                                        <a class="timeline-panel text-muted" href="javascript:void(0);">
                                            <span>15 minutes ago</span>
                                            <h6 class="mb-0">StumbleUpon is acquired by eBay. </h6>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="timeline-badge warning">
                                        </div>
                                        <a class="timeline-panel text-muted" href="javascript:void(0);">
                                            <span>20 minutes ago</span>
                                            <h6 class="mb-0">Mashable, a news website and blog, goes live.</h6>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="timeline-badge dark">
                                        </div>
                                        <a class="timeline-panel text-muted" href="javascript:void(0);">
                                            <span>20 minutes ago</span>
                                            <h6 class="mb-0">Mashable, a news website and blog, goes live.</h6>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li> --}}
                    
                    <li class="nav-item dropdown  header-profile">
                        <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" title="Settings">
                            <img src="/assets/images/avatar/avatar.jpg" width="56" alt="">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                    @if($user && $user->hasPermission('View Profile'))
                            <a href="/profile" class="dropdown-item ai-icon">
                                <svg id="icon-settings" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a1.998 1.998 0 1 1-2.829 2.829l-.06-.06a1.65 1.65 0 0 0-1.82.33h-2.8a1.65 1.65 0 0 0-1.82-.33l-.06.06a1.998 1.998 0 1 1-2.829-2.829l.06-.06a1.65 1.65 0 0 0-.33-1.82v-2.8a1.65 1.65 0 0 0 .33-1.82l-.06-.06a1.998 1.998 0 1 1 2.829-2.829l.06.06a1.65 1.65 0 0 0 1.82-.33h2.8a1.65 1.65 0 0 0 1.82.33l.06-.06a1.998 1.998 0 1 1 2.829 2.829l-.06.06a1.65 1.65 0 0 0 .33 1.82v2.8z"></path>
                                </svg>
                                <span class="ms-2">Settings</span>
                            </a>
                          @endif
                            {{-- <a href="email-inbox.html" class="dropdown-item ai-icon">
                                <svg id="icon-inbox" xmlns="http://www.w3.org/2000/svg" class="text-success" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                <span class="ms-2">Inbox </span>
                            </a> --}}
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								@csrf
							</form>
							
							<a href="javascript:;" class="dropdown-item ai-icon"
							   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
								<svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
									<polyline points="16 17 21 12 16 7"></polyline>
									<line x1="21" y1="12" x2="9" y2="12"></line>
								</svg>
								<span class="ms-2">Logout</span>
							</a>
							
							{{-- <form method="POST" action="{{ route('logout') }}">
								@csrf
	
								<x-dropdown-link :href="route('logout')"
										onclick="event.preventDefault();
													this.closest('form').submit();">
									{{ __('Log Out') }}
								</x-dropdown-link>
							</form> --}}
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
<!--**********************************
    Header end ti-comment-alt
***********************************-->