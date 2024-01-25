<x-app-layout>
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
                        <div class="col-xl-12">
        
                            <div class="filter cm-content-box box-primary">
                                <div class="content-title SlideToolHeader">
                              <h4>
                                        Add User
                                    </h4>
                                    <div class="tools">
                                        <a href="javascript:void(0);" class="expand handle"><i
                                                class="fal fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="cm-content-body  form excerpt">
                                    <div class="card-body">
                                        <form  action="{{ url('/') }}/register-user"  method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        
<div class="row">
                                        <div class="form-group mb-4  col-md-4 col-xl-4">
                                            <label class="form-label" for="username">Username</label>
                                            <input type="text" class="form-control" placeholder="Enter username" id="username" name="name"
                                                :value="old('name')" required autofocus autocomplete="name">
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                
                                        </div>
                                        <div class="form-group mb-4 col-md-4 col-xl-4">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="email" name="email" :value="old('email')" required autocomplete="username"
                                                class="form-control" placeholder="hello@example.com" id="email">
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>
                                        <div class="form-group mb-4 col-md-4 col-xl-4">
                                            <label class="form-label" for="usersPhone"  for="email">Phone</label>
                                            {{-- <input type="phone" name="phone" :value="old('phone')" required autocomplete="phone"
                                                class="form-control" placeholder="Enter Phone No" id="phone"> --}}

                                                <input type="text" class="form-control" id="usersPhone" :value="old('phone')" name="phone" 
                                                placeholder="Enter Receiver's Phone Number" pattern="\d{0,10}$"
                                                title="Please enter a valid phone number with up to 10 digits."
                                                maxlength="10">



                                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                        </div>
                                        <div class="mb-sm-4 mb-3 position-relative col-md-12 col-xl-12">
                                            <label class="form-label" for="dlab-password">Password</label>
                                            <input type="password" name="password" required autocomplete="new-password" id="dlab-password"
                                                class="form-control" value="123456">
                                            <span class="show-pass eye">
                                                <i class="fa fa-eye-slash"></i>
                                                <i class="fa fa-eye"></i>
                                            </span>
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>
                                        <div class="mb-sm-4 mb-3 position-relative col-md-12 col-xl-12">
                                            <label class="form-label" for="dlab-password">Confirm Password</label>
                                            <input type="password" type="password" name="password_confirmation" required autocomplete="new-password"
                                                class="form-control" value="123456">
                                            <span class="show-pass eye">
                                                <i class="fa fa-eye-slash"></i>
                                                <i class="fa fa-eye"></i>
                                            </span>
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>
                                        <div class="mb-sm-4 mb-3 position-relative">
                                            <div class="mb-4">
                                                <h4 class="card-title">Permissions</h4>
                                                <p>The selected page and operations will be allowed for the corresponding user.</p>
                                            </div>
                                            <select class="select2-width-75"  multiple="multiple"  
                                            style="width:100%;" name="permissions[]">
                                            <option value=""  disabled selected>Select Document Type</option>
                                            {{-- this option was giving to show all the documents, but due to complexity cant be given now --}}
                                            {{-- <option value="all" {{ old('type') == 'all' ? 'selected' : '' }}>
                                            Show All Documents</option> --}}
                                            @foreach ($permissions as $permission)
                                            <option value="{{ $permission->id }}">
                                                {{ $permission->display_name }} - {{ ['1' => 'Add', '2' => 'Read', '3' => 'Update'][$permission->action] }}
                                            </option>
                                            @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('permission')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <a href="" class="btn-link"></a>
                                        <div class="text-end"><button class="btn btn-success"
                                                type="submit"><i class="fas fa-filter"></i>&nbsp;Submit</button>
                                        </div>
                                    </div>
                                </form>
                                    </div> 
 </div>  </div>  </div>  </div>



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
                                                        <a href="/users/{{ $item->id }}/edit">
                                                            <button class="btn btn-primary btn-sm edit-btn">
                                                               <i class="fas fa-pencil-square"></i>&nbsp;Edit</button></a>
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



    </div>
    </div>
    </div>
  
    {{-- edit receiver modal starts --}}
    {{-- assign document to individual receiver starts --}}

 


 

    @include('layouts.footer')


</x-app-layout>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}


{{-- template for crud operation for each users --}}

{{-- @php
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
                 
              </tbody>
              </table>
          </div>
      </div>
       --}}

       <script>
        $("#single-select-abc1").select2();
    
        $(".single-select-abc1-placeholder").select2({
            placeholder: "Select Permissions",
            allowClear: true
        });
       </script>