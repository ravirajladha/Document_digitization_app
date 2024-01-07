<x-app-layout>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}

    <x-header/>
    @include('layouts.sidebar')

<div class="content-body default-height">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-body">
            <div class="row page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Set</a></li>
                </ol>
            </div>

            <form id="myAjaxForm"  action="{{ url('/')}}/add_set" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card profile-card card-bx m-b30">
                                <div class="card-header">
                                    <h6 class="title">Add Set</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form theme-form projectcreate">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Set </label>
                                                    <input type="text" class="form-control" name="name"
                                                        id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Set Name">
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a href="" class="btn-link"></a>
    
                                                <button class="btn btn-secondary" type="submit">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table m-2" id="yourTableId">
                                    
                                    <thead>
                                        <tr>
                                            <th scope="col">Sl no</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $index => $item)
                                            <tr>
                                                <th scope="row">{{ $index +1}}</th>
                                                <td>{{ $item->name }}</td>
                                                <td><Button class="btn btn-primary">Edit</Button></td>
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

@include('layouts.footer')


</x-app-layout>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function () {
        $('#myAjaxForm').on('submit', function (e) {
            e.preventDefault();  // prevent the form from 'submitting'

            var url = $(this).attr('action'); // get the target URL
            var formData = new FormData(this); // create a FormData object

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        toastr.success(response.success); // Display success toast
                    }
                    loadUpdatedSets();
                    $('#myAjaxForm')[0].reset();
                },
                error: function (error) {
                    console.log(error);
                    toastr.warning("Duplicate set found");
                    if (error.responseJSON && error.responseJSON.error) {
                        toastr.error(error.responseJSON.error); // Display error toast
                    }
                }
            });
        });
    });


    function loadUpdatedSets() {
    $.ajax({
        url: '/get-updated-sets',
        type: 'GET',
        success: function(sets) {
            var newTableContent = '';
            $.each(sets, function(index, set) {
                newTableContent += '<tr>' +
                                   '<th scope="row">' + (index + 1) + '</th>' +
                                   '<td>' + set.name + '</td>' +
                                   '<td><Button class="btn btn-primary">Edit</Button></td>' +
                                   '</tr>';
            });
            $('#yourTableId tbody').html(newTableContent);
        }
    });
}


</script>