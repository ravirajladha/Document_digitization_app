<x-app-layout>
    <x-header />
    <x-sidebar/>

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
                                        @if (isset($editUser)) Update User @else Add User @endif
                                    </h4>
                                    <div class="tools">
                                        <a href="javascript:void(0);" class="expand handle"><i
                                                class="fal fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="cm-content-body  form excerpt">
                                    <div class="card-body">

                                        @if (isset($editUser))
                                        <form action="{{ route('users.update', $editUser->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                    @else
                                        <form action="{{ url('/register-user') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                    @endif
                                    
                                        <div class="row">
                                            {{-- Username field --}}
                                            <div class="form-group mb-4 col-md-4 col-xl-4">
                                                <label class="form-label" for="username">Username</label>
                                                <input type="text" class="form-control" placeholder="Enter username" id="username" name="name"
                                                       value="{{ old('name', isset($editUser) ? $editUser->name : '') }}" required autofocus autocomplete="name">
                                                @error('name')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                    
                                            {{-- Email field --}}
                                            <div class="form-group mb-4 col-md-4 col-xl-4">
                                                <label class="form-label" for="email">Email</label>
                                                <input type="email" name="email" class="form-control" placeholder="hello@example.com" id="email"
                                                       value="{{ old('email', isset($editUser) ? $editUser->email : '') }}" required autocomplete="username">
                                                @error('email')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                    
                                            {{-- Phone field --}}
                                            <div class="form-group mb-4 col-md-4 col-xl-4">
                                                <label class="form-label" for="usersPhone">Phone</label>
                                                <input type="text" class="form-control" id="usersPhone" name="phone"
                                                       placeholder="Enter Receiver's Phone Number" pattern="\d{0,10}"
                                                       title="Please enter a valid phone number with up to 10 digits."
                                                       value="{{ old('phone', isset($editUser) ? $editUser->phone : '') }}" maxlength="10">
                                                @error('phone')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                    
                                            {{-- Password fields --}}
                                            {{-- Note: Password fields should not be pre-populated for security reasons --}}
                                            <div class="mb-sm-4 mb-3 position-relative col-md-12 col-xl-12">
                                                <label class="form-label" for="dlab-password">Password</label>
                                                <input type="password" name="password"  @if (!isset($editUser)) required @endif autocomplete="new-password" id="dlab-password" class="form-control">
                                                <span class="show-pass eye">
                                                    <i class="fa fa-eye-slash"></i>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                                @error('password')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-sm-4 mb-3 position-relative col-md-12 col-xl-12">
                                                <label class="form-label" for="password_confirmation">Confirm Password</label>
                                                <input type="password" name="password_confirmation"  @if (!isset($editUser)) required @endif autocomplete="new-password" class="form-control">
                                                <span class="show-pass eye">
                                                    <i class="fa fa-eye-slash"></i>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                                @error('password_confirmation')
                                                <div class="alert alert-danger">{{ $message  }}</div>
                                                @enderror
                                            </div>
                                                {{-- <div class="mb-sm-4 mb-3 position-relative">
                                                    <div class="mb-4">
                                                        <h4 class="card-title">Permissions</h4>
                                                        <p>The selected page and operations will be allowed for the
                                                            corresponding user.</p>
                                                    </div>
                                                    <select class="select2-width-75" multiple="multiple"
                                                        style="width:100%;" name="permissions[]">
                                                        <option value="" disabled selected>Select Document Type
                                                        </option>
                                                    
                                                        @foreach ($permissions as $permission)
                                                            <option value="{{ $permission->id }}">
                                                                {{ $permission->display_name }} -
                                                                {{ ['1' => 'Add', '2' => 'Read', '3' => 'Update'][$permission->action] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('permission')" class="mt-2" />
                                                </div> --}}
                                            </div>






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
                                                                <th>Update Status</th>
                                                            </tr>
                                                            <tr>

{{-- {{ $permissions }} --}}
                                                            <tr>
                                                                <td class="module-name">Document</td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[4]" value="4"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 4)) checked @endif>

                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[3]" value="3"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 3)) checked @endif>

                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[5]" value="5"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 5)) checked @endif>

                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[6]" value="6"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 6)) checked @endif>

                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="module-name">Document Type</td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[21]" value="21"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 21)) checked @endif>

                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[20]" value="20"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 20)) checked @endif>

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Document Field</td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[23]" value="23"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 23)) checked @endif>

                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[22]" value="22"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 22)) checked @endif>

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Bulk Upload</td>
                                                                <td></td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[25]" value="25"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 25)) checked @endif>

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Sets</td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[8]" value="8"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 8)) checked @endif>

                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[7]" value="7"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 7)) checked @endif>

                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[9]" value="9"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 9)) checked @endif>

                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Receivers</td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[15]" value="15"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 15)) checked @endif>

                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[14]" value="14"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 14)) checked @endif>

                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[16]" value="16"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 16)) checked @endif>

                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Assign Document</td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[19]" value="19"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 19)) checked @endif>

                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[19]" value="19"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 19)) checked @endif>

                                                                </td>
                                                                <td></td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[18]" value="18"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 18)) checked @endif>

                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Compliances</td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[27]" value="27"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 27)) checked @endif>

                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[26]" value="26"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 26)) checked @endif>

                                                                </td>
                                                                <td></td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[28]" value="28"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 28)) checked @endif>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Configure</td>
                                                                <td></td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[24]" value="24"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 24)) checked @endif>

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Profile</td>
                                                                <td></td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[1]" value="1"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 1)) checked @endif>

                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[6]" value="6"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 6)) checked @endif>

                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Notifications</td>
                                                                <td></td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[29]" value="29"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 29)) checked @endif>

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Filter Doc</td>
                                                                <td></td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[10]" value="10"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 10)) checked @endif>

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">View Documents By Document Type</td>
                                                                <td></td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[12]" value="12"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 12)) checked @endif>

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">View Documents from Sets</td>
                                                                <td></td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[13]" value="13"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 13)) checked @endif>

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">View Documents from Sets</td>
                                                                <td></td>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="permissions[13]" value="13"
                                                                        @if (isset($editUser) && $editUser->permissions->contains('id', 13)) checked @endif>

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>

                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>



                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="" class="btn-link"></a>
                                    <div class="text-end"><button class="btn btn-success" type="submit"><i
                                                class="fas fa-filter"></i>&nbsp;Submit</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>




                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Users Detail</h4>
                                    {{-- <button type="button" class="btn btn-success mb-2 float-end"
                                        data-bs-toggle="modal" data-bs-target="#exampleModalCenter1"><i
                                            class="fas fa-plus-square"></i>&nbsp;Add
                                        User</button> --}}


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
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td>{{ $item->name }}</td>
                                                        <td>{{ $item->phone }}</td>
                                                        <td>{{ $item->email }}</td>

                                                        <td><span class="badge bg-success">Active</span></td>

                                                        <!-- Assuming you have a relation to get the receiver type name -->
                                                        <td>
                                                            <a href="{{ route('users.edit', $item->id) }}">
                                                                <button class="btn btn-primary btn-sm edit-btn">
                                                                    <i
                                                                        class="fas fa-pencil-square"></i>&nbsp;Edit</button></a>
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
        .table tbody tr td:last-child,
        .table thead tr th:last-child {
            text-align: left;
        }

        th {
            font-weight: bold;
            /* Makes header text bolder */
            color: #333;
            /* Darker color for header text */
        }

        .module-name {
            font-weight: bold;
            /* Makes module name text bolder */
            color: #333;
            /* Darker color for module name */
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
                          <td><input type="checkbox" class="form-check-input" name="create"></td>
                          <td><input type="checkbox" class="form-check-input" name="read"></td>
                          <td><input type="checkbox" class="form-check-input" name="update"></td>
                          <td><input type="checkbox" class="form-check-input" name="delete"></td>
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
