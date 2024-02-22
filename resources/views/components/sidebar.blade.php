        <!--**********************************
            Sidebar start
        ***********************************-->
        @php
            $user = Auth::user();
        @endphp


        <div class="dlabnav">
            <div class="dlabnav-scroll">
                <ul class="metismenu" id="menu">
                    <li><a href="/dashboard" aria-expanded="false">
                            <i class="fas fa-home"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>


                    </li>
                    {{-- @if (Auth::user()->type == 'admin') --}}
                    @if (
                        $user &&
                            ($user->hasPermission('Filter Document') ||
                                $user->hasPermission('Add Basic Document Form') ||
                                $user->hasPermission('View Assigned Documents') ||
                                $user->hasPermission('View Bulk Upload') ||
                                $user->hasPermission('View Profile') ||
                                $user->hasPermission('View Document Types ')))
                        <li>
                            <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                                <i class="fas fa-clone"></i>
                                <span class="nav-text">Document </span>
                            </a>
                            <ul aria-expanded="false">
                                @if ($user && $user->hasPermission('Filter Document'))
                                    <li><a href="{{ url('/') }}/filter-document">View Documents</a></li>
                                @endif
                                @if ($user && $user->hasPermission('Add Basic Document Form'))
                                    <li><a href="{{ url('/') }}/add_document_first">Add Document</a></li>
                                @endif
                                @if ($user && $user->hasPermission('View Assigned Documents'))
                                    <li><a href="{{ url('/') }}/assign-documents">Assign Document</a></li>
                                @endif
                                @if ($user && $user->hasPermission('View Bulk Upload'))
                                    <li><a href="{{ url('/') }}/bulk-upload-master-data">Bulk Upload</a></li>
                                @endif
                                @if ($user && $user->hasPermission('View Document Types '))
                                    <li><a href="{{ url('/') }}/document_type">Document Type</a></li>
                                @endif
                                {{-- <li><a href="{{ url('/')}}/add_fields_first">Document Field</a></li> --}}
                                {{-- <li><a href="{{ url('/')}}/view_doc_first">View Documents</a></li>		 --}}

                                {{-- <li><a href="{{ url('/')}}/profile">Change password</a></li>		 --}}

                            </ul>
                        </li>
                    @endif
                    {{-- <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
						<i class="fas fa-info-circle"></i>
							<span class="nav-text">Receivers</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('/')}}/receivers">Add Receiver</a></li>
                           
                         
                        </ul>
                    </li> --}}
                    @if ($user && $user->hasPermission('View Sets'))
                        <li><a href="{{ url('/') }}/set" aria-expanded="false">
                                <i class="fas fa-info-circle"></i>
                                <span class="nav-text">Sets</span>
                            </a>
                        </li>
                    @endif
                    @if ($user && $user->hasPermission('View Receivers'))
                        <li><a href="{{ url('/') }}/receivers" aria-expanded="false">
                                {{-- <i class="fas fa-user-circle"></i> --}}
                                <i class="fas fa-receipt"></i>
                                <span class="nav-text">Receivers</span>
                            </a>
                        </li>
                    @endif
                    @if ($user && $user->hasPermission('View Users'))
                        <li><a href="{{ url('/') }}/users" aria-expanded="false" disabled>
                                <i class="fas fa-user-circle"></i>
                                <span class="nav-text">Users</span>
                            </a>
                        </li>
                    @endif
                    @if ($user && $user->hasPermission('View Compliances'))
                        <li><a href="{{ url('/') }}/compliances" aria-expanded="false">
                                <i class="fas fa-procedures"></i>
                                <span class="nav-text">Compliances</span>
                            </a>
                        </li>
                    @endif
                    @if ($user && $user->hasPermission('Configure'))
                        <li><a href="{{ url('/') }}/data-sets" aria-expanded="false">
                                {{-- <i class="fas fa-scale-balanced"></i> --}}
                                <i class="fas fa-table"></i>
                                <span class="nav-text">Configure</span>
                            </a>
                        </li>
                    @endif
                    @if ($user && $user->hasPermission('View Profile'))
                        <li><a href="{{ url('/') }}/profile" aria-expanded="false">
                                {{-- <i class="fas fa-scale-balanced"></i> --}}
                                <i class="fas fa-tools"></i>
                                <span class="nav-text">Settings</span>
                            </a>
                        </li>
                    @endif
                    {{-- 				
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						@csrf
					</form>
					<li><a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" aria-expanded="false">
					
						<i class="fas fa-sign-out"></i>
							<span class="nav-text">Logout</span>
						</a>
                    </li> --}}


                    {{-- <li>
						<a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
							<i class="fas fa-tools"></i>
							<span class="nav-text">Settings </span>
						</a>
                        <ul aria-expanded="false">
							<li><a href="{{ url('/')}}/profile">Profile</a></li>	
							
							<li><a href="{{ url('/')}}/add_document_first" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>	
							
							<li class="nav-item dropdown notification_dropdown">
								<a class="nav-link bell dz-theme-mode" href="javascript:void(0);">
									<i id="icon-light" class="fas fa-sun">Day Mode</i> 
									<i id="icon-dark" class="fas fa-moon">Night Mode</i> 
								</a>
							</li>
							
						
						</ul>
                    </li> --}}

                    {{-- <li>
						<a  href="javascript:void(0);" aria-expanded="false">
						
							&nbsp;
						</a>
                        <ul aria-expanded="false">
							 <li><a href="{{ url('/')}}/document_type"></a></li>
							
						
						</ul>
                    </li> --}}

                </ul>
                <style>
                    .sidebar-container {
                        display: flex;
                        flex-direction: column;
                        height: 10vh;
                        /* Adjust the height as needed */
                        overflow-x: hidden;
                        /* Hide horizontal scrollbar */
                    }

                    .sidebar-footer {
                        /* margin-top: auto; */
                    }

                    .sidebar-content {
                        overflow-y: auto;
                    }
                </style>
                <div class="sidebar-container d-flex flex-column sidebar-footer">
                    <div class="sidebar-content overflow-auto sidebar-footer">
                        <div class="side-bar-profile sidebar-footer">

                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="side-bar-profile-img">
                                    <img src="/assets/images/avatar/avatar.jpg" alt="">
                                </div>
                                <div class="profile-info1">
                                    <h5>{{ Auth::user()->name }}</h5>
                                    <span>{{ Auth::user()->email }}</span>
                                </div>
                                <div class="profile-button">
                                    <i class="fas fa-caret-downd scale5 text-light"></i>
                                </div>
                            </div>
                            {{-- <div class="d-flex justify-content-between mb-2 progress-info">
						<span class="fs-12"><i class="fas fa-star text-orange me-2"></i>Task Progress</span>
						<span class="fs-12">20/45</span>
					</div>
					<div class="progress default-progress">
						<div class="progress-bar bg-gradientf progress-animated" style="width: 45%; height:8px;" role="progressbar">
							<span class="sr-only">45% Complete</span>
						</div>
					</div> --}}
                        </div>
                    </div>
                </div>
                <div class="sidebar-footer mt-auto">
                    
                    <div class="copyright">
                        {{-- <p>Kods © 2023 All Rights Reserved</p> --}}
                        <a href="https://kodstech.com/" target="_blank">
                            <p class="fs-12">Powered by Kods</p>
                        </a>
                    </div>
                </div>


            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->
