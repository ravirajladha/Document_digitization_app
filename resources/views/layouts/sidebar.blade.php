        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="dlabnav">
            <div class="dlabnav-scroll">
				<ul class="metismenu" id="menu">
                    <li><a  href="/dashboard" aria-expanded="true">
							<i class="fas fa-home"></i>
							<span class="nav-text">Dashboard</span>
						</a>
                       

                    </li>
                    @if (Auth::user()->type == "admin")
					<li>
						<a class="has-arrow" href="javascript:void(0);" aria-expanded="true">
							<i class="fas fa-chart-line"></i>
							<span class="nav-text">Document  <span class="badge badge-xs badge-danger ms-2">New</span></span>
						</a>
                        <ul aria-expanded="true">
							 <li><a href="{{ url('/')}}/document_type">Document Type</a></li>
							<li><a href="{{ url('/')}}/add_fields_first">Document Field</a></li>
							<li><a href="{{ url('/')}}/add_document_first">Add Document</a></li>	
							<li><a href="{{ url('/')}}/view_doc_first">Documents</a></li>		
							<li><a href="{{ url('/')}}/set">Set</a></li>		
							<li><a href="{{ url('/')}}/change_password">Change password</a></li>		
						
						</ul>
                    </li>
                    @else
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="true">
						<i class="fas fa-info-circle"></i>
							<span class="nav-text">Document</span>
						</a>
                        <ul aria-expanded="true">
                            <li><a href="{{ url('/')}}/reviewer/view_doc_first">View Document</a></li>
                            {{-- <li><a href="edit-profile.html">Edit Profile</a></li>
							<li><a href="post-details.html">Post Details</a></li>
                            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Email</a>
                                <ul aria-expanded="false">
                                    <li><a href="email-compose.html">Compose</a></li>
                                    <li><a href="email-inbox.html">Inbox</a></li>
                                    <li><a href="email-read.html">Read</a></li>
                                </ul>
                            </li> --}}
                         
                        </ul>
                    </li>
                   @endif
                   
                 
                </ul>
				<div class="side-bar-profile">
				
					<div class="d-flex align-items-center justify-content-between mb-3">
						<div class="side-bar-profile-img">
							<img src="/assets/images/user.jpg" alt="">
						</div>
						<div class="profile-info1">
							<h5>{{ Auth::user()->name }}</h5>
							<span>{{ Auth::user()->email }}</span>
						</div>
						<div class="profile-button">
							<i class="fas fa-caret-downd scale5 text-light"></i>
						</div>
					</div>	
					<div class="d-flex justify-content-between mb-2 progress-info">
						<span class="fs-12"><i class="fas fa-star text-orange me-2"></i>Task Progress</span>
						<span class="fs-12">20/45</span>
					</div>
					<div class="progress default-progress">
						<div class="progress-bar bg-gradientf progress-animated" style="width: 45%; height:8px;" role="progressbar">
							<span class="sr-only">45% Complete</span>
						</div>
					</div>
				</div>
				
				<div class="copyright">
					<p>Kods © 2023 All Rights Reserved</p>
					<p class="fs-12">Made  by Kods</p>
				</div>

                
			</div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->
		