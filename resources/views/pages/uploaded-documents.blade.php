<x-app-layout>


    <x-header />
    <x-sidebar />

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active"><a href="/compliances">Uploaded Documents</a></li>

                    </ol>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModalCenter">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Document</h5>
                                
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="uploadForm" action="{{ route('upload.files') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="file" name="files[]" multiple>
                                    </div>
                                    <div id="progress" class="progress mb-3" style="display: none;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                        </div>
                                    </div>
                                    <div id="spinner" class="text-center" style="display: none;">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <div>Uploading...</div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <div class="bootstrap-popover d-inline-block float-end mb-2">
                                    <button type="button" class="btn btn-secondary btn-sm px-4 "
                                        data-bs-container="body" data-bs-toggle="popover"
                                        data-bs-placement="top"
                                        data-bs-content="The name of the document should match with the Temporary Index Id provided in the excal sheet followed by the respective document. Maximum 20 documents are allowed."
                                        title="Password Guidelines"> Info <i
                                            class="fas fa-info-circle"></i></button>
                                </div>
                                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
                                <button id="uploadButton" type="button" class="btn btn-primary">Upload</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>




                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table id="example3" class="display" style="min-width: 845px">
                                            @if ($user && $user->hasPermission('Add PDF'))
                                                <button type="button" class="btn btn-success mb-2 float-end btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModalCenter"> <i
                                                        class="fas fa-square-plus"></i>&nbsp;Add Documents</button>

                                            @endif
                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl. No.</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Type </th>
                                                    <th scope="col">Memory Size </th>
                                                    <th scope="col">Created At</th>
                                                    @if ($user && $user->hasPermission('Delete PDF'))
                                                    <th scope="col">Action</th>
                                                    @endif
                                                    
                                                    {{-- <th scope="col">Status </th> --}}
                                                    {{-- @if ($user && $user->hasPermission('Update Compliances Status'))
                                                    <th scope="col">Submit </th>
                                                    @endif
                                                    @if ($user && $user->hasPermission('Update Compliance Recurring Status'))
                                                    <th scope="col">Is Recurring Action</th>
@endif --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($fileInfoList as $index => $fileInfo)
                                                <tr>
                                                    <td>{{ $index+1 }}</td>
                                                
                                                  
                                                    


                                                    <td>{{ $fileInfo['name'] }}</td>
                                                    <td>{{ $fileInfo['extension'] }} </td>
                                                    <td>{{ round($fileInfo['size'] / (1024 * 1024), 2) }} MB</td>

                                                    <td>{{ $fileInfo['uploaded_date'] }}</td>
                                                    <td>
                                                        @if ($user && $user->hasPermission('Delete PDF'))
                                                        <div style="display: inline-block;">
                                                            <form action="{{ route('documents.delete', $fileInfo['name']) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this file?')"> <i class="fa fa-trash btn-danger"></i></button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                        <div style="display: inline-block;">
                                                            <a href="{{ url('/uploads/documents/' . $fileInfo['name']) }}" target="_blank" class="btn btn-primary">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </div>
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


    @include('layouts.footer')


</x-app-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




<script>
    // Function to show/hide loader and progress bar
    function toggleLoader(showLoader) {
        if (showLoader) {
            $('#spinner').show();
            $('#progress').show();
        } else {
            $('#spinner').hide();
            $('#progress').hide();
        }
    }

    // Function to update progress bar
    function updateProgressBar(progress) {
        $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress);
    }

    $(document).ready(function() {
        // On clicking upload button
        $('#uploadButton').click(function() {
            toggleLoader(true); // Show loader

            var formData = new FormData($('#uploadForm')[0]);

            // AJAX request to upload files
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    // Upload progress event
                    xhr.upload.addEventListener("progress", function(event) {
                        if (event.lengthComputable) {
                            var percentComplete = Math.round((event.loaded / event.total) * 100);
                            updateProgressBar(percentComplete); // Update progress bar
                        }
                    }, false);
                    return xhr;
                },
                type: 'POST',
                url: $('#uploadForm').attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    toggleLoader(false); // Hide loader
                    // Handle success response
                    console.log(response);
                    $('#exampleModalCenter').on('hidden.bs.modal', function () {
                        $(this).find('form')[0].reset(); // Clear the form
                    });
    toastr.success(response.success);
                },
                error: function(xhr, status, error) {
                    toggleLoader(false); // Hide loader
                    // Handle error response
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>