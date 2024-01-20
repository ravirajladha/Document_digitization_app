<x-app-layout>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}

    <x-header />
    @include('layouts.sidebar')

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Users</a></li>
                    </ol>
                </div>


                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Users</h4>
                                    <button type="button" class="btn btn-success mb-2 float-end"
                                    data-bs-toggle="modal" data-bs-target="#exampleModalCenter1"><i
                                        class="fas fa-plus-square"></i>&nbsp;Add
                                    User</button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        {{-- <h4>Receivers</h4> --}}
                                       

                                        {{-- <div class="table-responsive"> --}}
                                        {{-- <table id="example3" class="display" style="min-width: 845px"> --}}
                                        <table id="example3" class="display">

                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl no</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Phone</th>
                                                  
                                                    <th scope="col">Email Id</th>
                                                
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Action</th>
                                               
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $index => $item)
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td>Test</td>
                                                        <td>8545858547</td>
                                               
                                                        <td>test@gmail.com</td>
                                                     
                                                      
                                                  
                                                    
                                                        <td><span class="badge bg-success">Active</span></td>

                                                        <!-- Assuming you have a relation to get the receiver type name -->
                                                        <td>
                                                            <button class="btn btn-primary btn-sm edit-btn">
                                                               <i class="fas fa-pencil-square"></i>&nbsp;Edit</button>
                                                        </td>
                                                      

                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <style>
        .table tbody tr td:last-child, .table thead tr th:last-child {
text-align: left;
}
th {
font-weight: bold; /* Makes header text bolder */
color: #333; /* Darker color for header text */
}

.module-name {
font-weight: bold; /* Makes module name text bolder */
color: #333; /* Darker color for module name */
}
      </style>

    {{-- add receiver modal form starts --}}

    {{-- <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Large modal</button>
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg"> --}}


    <div class="modal fade" id="exampleModalCenter1">
        <div class="modal-dialog modal-dialog-lg modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form theme-form projectcreate">
                        <form id="myAjaxForm" action="" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                      
                                    <div class="col-md-6">
                                        <label for="receiverName" class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" id="receiverName"
                                            placeholder="Enter  Name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="receiverEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" id="receiverEmail"
                                            placeholder="Enter  Email">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="receiverPhone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="phone" id="receiverPhone"
                                            placeholder="Enter  Phone Number" pattern="\d{0,10}$"
                                            title="Please enter a valid phone number with up to 10 digits."
                                            maxlength="10">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="receiverCity" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="city" id="receiverCity"
                                            placeholder="Enter Password">
                                    </div>
                           
                              @php
                              $modules = ['Document', 'Bulk Upload', 'Document Field', 'Document Type','Sets', 'Bulk Upload', 'Receivers', 'Users','Compliances','Receiver Type'];
                              @endphp
                                    <div class="mb-3">
                                        <label for="receiverType" class="form-label">Permissions & Role</label>
                                            <div class="table-responsive">
                                                <table class="table  table-responsive-sm">
                                                    <tbody style="padding:0 0 0 0;">
                                                <tr>
                                                    <th>Module</th>
                                                    <th>Create</th>
                                                    <th>Read</th>
                                                    <th>Update</th>
                                                    <th>Delete</th>
                                                </tr>
                                                <tr>

                                                    @foreach ($modules as $module)
                                                    <tr>
                                                        <td class="module-name">{{ $module }}</td>
                                                        <td><input type="checkbox" class="form-check-input" name="{{ strtolower($module) }}_create"></td>
                                                        <td><input type="checkbox" class="form-check-input" name="{{ strtolower($module) }}_read"></td>
                                                        <td><input type="checkbox" class="form-check-input" name="{{ strtolower($module) }}_update"></td>
                                                        <td><input type="checkbox" class="form-check-input" name="{{ strtolower($module) }}_delete"></td>
                                                    </tr>
                                                @endforeach
                                                </tr>
                                                <!-- Repeat for other modules -->
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                           
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="#" class="btn btn-primary">Submit Form</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
  
    {{-- edit receiver modal starts --}}
    {{-- assign document to individual receiver starts --}}

 


 

    @include('layouts.footer')


</x-app-layout>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

