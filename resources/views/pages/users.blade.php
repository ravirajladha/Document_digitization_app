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
                                                @foreach ($users as $index => $item)
                                                    <tr>
                                                        <th scope="row">{{ $index+1 }}</th>
                                                        <td>{{ $item->name }}</td>
                                                        <td>{{ $item->phone }}</td>
                                                        <td>{{ $item->email }}</td>
                                                    
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
                        <form  action="{{ url('/') }}/register-user"  method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                      
                                       
                                            <div class="form-group mb-4">
                                                <label class="form-label" for="username">Username</label>
                                                <input type="text" class="form-control" placeholder="Enter username" id="username" name="name"
                                                    :value="old('name')" required autofocus autocomplete="name">
                                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    
                                            </div>
                                            <div class="form-group mb-4">
                                                <label class="form-label" for="email">Email</label>
                                                <input type="email" name="email" :value="old('email')" required autocomplete="username"
                                                    class="form-control" placeholder="hello@example.com" id="email">
                                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                            </div>
                                            <div class="form-group mb-4">
                                                <label class="form-label" for="email">Phone</label>
                                                <input type="phone" name="phone" :value="old('phone')" required autocomplete="phone"
                                                    class="form-control" placeholder="Enter Phone No" id="phone">
                                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                            </div>
                                            <div class="mb-sm-4 mb-3 position-relative">
                                                <label class="form-label" for="dlab-password">Password</label>
                                                <input type="password" name="password" required autocomplete="new-password" id="dlab-password"
                                                    class="form-control" value="123456">
                                                <span class="show-pass eye">
                                                    <i class="fa fa-eye-slash"></i>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                            </div>
                                            <div class="mb-sm-4 mb-3 position-relative">
                                                <label class="form-label" for="dlab-password">Confirm Password</label>
                                                <input type="password" type="password" name="password_confirmation" required autocomplete="new-password"
                                                    class="form-control" value="123456">
                                                <span class="show-pass eye">
                                                    <i class="fa fa-eye-slash"></i>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
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
                    <button type="submit" class="btn btn-primary">Submit Form</button>
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

