<x-app-layout>
 

    @include('layouts.header')
    @include('layouts.sidebar')

<div class="content-body default-height">
    <!-- row -->
    <div class="container-fluid">

<div class="page-body">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3>
                        Update Password</h3>
                </div>
                <div class="col-12 col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">
                                <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Update Password</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ url('/')}}/update_password" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form theme-form projectcreate">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">Enter Old Password</label>
                                            <input type="text" class="form-control" name="old_pw"
                                                id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter old Password">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">Enter New Password</label>
                                            <input type="text" class="form-control" name="new_pw"
                                                id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter new Password">
                                        </div>
                                    </div>

                                    <div class="col-md-12 my-auto">
                                        <div class="text-end"><button class="btn btn-secondary"
                                                type="submit">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

</div>
</div>

@include('layouts.footer')


</x-app-layout>

