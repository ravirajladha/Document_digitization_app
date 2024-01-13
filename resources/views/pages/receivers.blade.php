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
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Data Set</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Receivers</a></li>
                    </ol>
                </div>


                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <button type="button" class="btn btn-success mb-2 float-end"
                                            data-bs-toggle="modal" data-bs-target="#exampleModalCenter1"><i class="fas fa-plus-square"></i>&nbsp;Add
                                            Receiver</button>

                                        {{-- <div class="table-responsive"> --}}
                                        {{-- <table id="example5" class="display" style="min-width: 845px"> --}}
                                        <table id="example5" class="display">

                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl no</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Phone</th>
                                                    <th scope="col">City</th>
                                                    <th scope="col">Email Id</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Count</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Action</th>
                                                    <th scope="col">Assign</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $index => $item)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td>{{ $item->name }}</td>
                                                        <td>{{ $item->phone }}</td>
                                                        <td>{{ $item->city }}</td>
                                                        <td>{{ $item->email }}</td>
                                                        <td>{{ optional($item->receiverType)->name }}</td>
                                                        <td> <a
                                                                href="/user-assign-documents/{{ $item->id }}">{{ $item->document_assignments_count }}</a>
                                                        </td>
                                                        <td>{!! $item->status
                                                            ? '<span class="badge bg-success">Active</span>'
                                                            : '<span class="badge bg-warning text-dark">Inactive</span>' !!}</td>

                                                        <!-- Assuming you have a relation to get the receiver type name -->
                                                        <td>
                                                            <button class="btn btn-primary edit-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalCenter"
                                                                data-receiver-id="{{ $item->id }}"
                                                                data-receiver-name="{{ $item->name }}"
                                                                data-receiver-phone="{{ $item->phone }}"
                                                                data-receiver-city="{{ $item->city }}"
                                                                data-receiver-email="{{ $item->email }}"
                                                                data-receiver-type-id="{{ $item->receiver_type_id }}"
                                                                data-receiver-status="{{ $item->status }}"><i class="fas fa-pencil-square"></i>&nbsp;Edit</button>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-success assign-doc-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#assignDocumentModal"
                                                                data-receiver-id="{{ $item->id }}"
                                                                data-receiver-type-id="{{ $item->receiver_type_id }}"><i class="fas fa-plus"></i>&nbsp;Assign
                                                                Doc</button>

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


    {{-- add receiver modal form starts --}}
    <div class="modal fade" id="exampleModalCenter1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Receiver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form theme-form projectcreate">
                        <form id="myAjaxForm" action="{{ route('addReceiver') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="receiverName" class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" id="receiverName"
                                            placeholder="Enter Receiver's Name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="receiverEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" id="receiverEmail"
                                            placeholder="Enter Receiver's Email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="receiverPhone" class="form-label">Phone</label>
                                        <input type="number" class="form-control" name="phone" id="receiverPhone"
                                            placeholder="Enter Receiver's Phone Number">
                                    </div>
                                    <div class="mb-3">
                                        <label for="receiverCity" class="form-label">City</label>
                                        <input type="text" class="form-control" name="city" id="receiverCity"
                                            placeholder="Enter Receiver's City">
                                    </div>
                                    <div class="mb-3">
                                        <label for="receiverType" class="form-label">Receiver
                                            Type</label>
                                        <select class="form-control" id="receiverType" name="receiver_type_id">
                                            <option selected value="">Select Receiver Type</option>
                                            @foreach ($receiverTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    {{-- edit receiver modal starts --}}
    {{-- assign document to individual receiver starts --}}

    <div class="modal fade" id="assignDocumentModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Receiver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Update Form -->
                    <form action="{{ url('/') }}/assign-documents-to-receiver" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="receiverId" name="id">
                        <!-- Hidden fields inside the form -->
                        <input type="hidden" id="modalReceiverId" name="receiver_id">
                        <input type="hidden" id="modalReceiverTypeId" name="receiver_type">
                        <input type="hidden"  name="location" value="user">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="documentType" class="form-label">Document
                                    Type</label>
                                <select class="form-control" id="documentType" name="document_type"
                                    onchange="fetchDocuments(this.value)" required>
                                    <option value="">Select Document Type
                                    </option>
                                    @foreach ($documentTypes as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="document" class="form-label">Document</label>
                                <select class="form-control" id="document" name="document_id" required>
                                    <option value="">Select Document</option>
                                    <!-- Options will be populated based on Document Type selection -->
                                </select>
                            </div>

                        </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    {{-- assign document to individual receiver ends --}}
    <div class="modal fade" id="exampleModalCenter">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Receiver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Update Form -->
                    <form id="updateReceiverForm">
                        <input type="hidden" id="receiverId" name="id">
                        <div class="mb-3">
                            <label for="receiverName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="receiverName" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="receiverPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="receiverPhone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="receiverCity" class="form-label">City</label>
                            <input type="text" class="form-control" id="receiverCity" name="city">
                        </div>
                        <div class="mb-3">
                            <label for="receiverEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="receiverEmail" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="receiverType" class="form-label">Receiver
                                Type</label>
                            <select class="form-control" id="receiverType" name="receiver_type_id">
                                @foreach ($receiverTypes as $type)
                                    <option value="{{ $type->id }}">
                                        {{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="receiverStatus" class="form-label">Status</label>
                            <select class="form-control" id="receiverStatus" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitUpdateForm()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    {{-- edit receiver modal ends --}}

    @include('layouts.footer')


</x-app-layout>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myAjaxForm').on('submit', function(e) {
            e.preventDefault(); // prevent the form from 'submitting'

            var url = $(this).attr('action'); // get the target URL
            var formData = new FormData(this); // create a FormData object

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false, // tell jQuery not to process the data
                contentType: false, // tell jQuery not to set contentType
                success: function(response) {

                    if (response.success) {
                        toastr.success(response.success); // Display success toast
                    }
                    loadUpdatedReceivers();
                    $('#myAjaxForm')[0].reset();
                },
                error: function(error) {
                    console.log(error);
                    toastr.warning("Duplicate set found");
                    if (error.responseJSON && error.responseJSON.error) {
                        toastr.error(error.responseJSON.error); // Display error toast
                    }
                }
            });
        });
    });


    // Update the receiver list
    function loadUpdatedReceivers() {
        $.ajax({
            url: '/get-updated-receivers', // Make sure this URL is defined in your routes
            type: 'GET',
            success: function(receivers) {
                var newTableContent = '';
                var assignDocButton = '<button class="btn btn-success assign-doc-btn" data-bs-toggle="modal" data-bs-target="#assignDocumentModal" data-receiver-id="' + receiver.id + '" data-receiver-type-id="' + receiver.receiver_type_id + '"><i class="fas fa-plus"></i>&nbsp;Assign Doc</button>';
                $.each(receivers, function(index, receiver) {
                    var statusBadge = receiver.status ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-warning text-dark">Inactive</span>';
                    newTableContent += '<tr>' +
                        '<th scope="row">' + (index + 1) + '</th>' +
                        '<td>' + receiver.name + '</td>' +
                        '<td>' + receiver.phone + '</td>' +
                        '<td>' + receiver.city + '</td>' +
                        '<td>' + receiver.email + '</td>' +

                        '<td>' + receiver.receiver_type_name + '</td>' +
                        '<td>' + receiver.document_assignments_count + '</td>' +

                        '<td>' + statusBadge + '</td>' +
                        // Make sure you have the receiver type name available
                        '<td><Button class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" data-receiver-id="' +
                        receiver.id + '" data-receiver-name="' + receiver.name +
                        '" data-receiver-phone="' + receiver.phone + '" data-receiver-city="' +
                        receiver.city + '" data-receiver-email="' + receiver.email +
                        '" data-receiver-type-id="' + receiver.receiver_type_id +
                        '" data-receiver-status="' + receiver.status + '">Edit</Button></td>' +

                        '<td>' + assignDocButton + '</td>' +

                        '</tr>';
                });
                $('#example5 tbody').html(newTableContent);
            }
        });
    }
    // Pre-fill the update modal form when the Edit button is clicked
    $(document).ready(function() {
        $('#example5').on('click', '.edit-btn', function() {
            var receiverId = $(this).data('receiver-id');
            var receiverName = $(this).data('receiver-name');
            var receiverPhone = $(this).data('receiver-phone');
            var receiverCity = $(this).data('receiver-city');
            var receiverEmail = $(this).data('receiver-email');
            var receiverTypeId = $(this).data('receiver-type-id');
            var receiverStatus = $(this).data('receiver-status');

            // Update the form fields
            $('#updateReceiverForm #receiverId').val(receiverId);
            $('#updateReceiverForm #receiverName').val(receiverName);
            $('#updateReceiverForm #receiverPhone').val(receiverPhone);
            $('#updateReceiverForm #receiverCity').val(receiverCity);
            $('#updateReceiverForm #receiverEmail').val(receiverEmail);
            $('#updateReceiverForm #receiverStatus').val(receiverStatus);
        });
    });

    // Submit the updated receiver form
    function submitUpdateForm() {
        var formData = $('#updateReceiverForm').serialize();
        console.log(formData);
        // AJAX call to update the receiver
        $.ajax({
            url: '/update-receiver', // Replace with your server's update URL
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#exampleModalCenter').modal('hide');
                toastr.success(response.success);
                loadUpdatedReceivers(); // Update the receivers list
            },
            error: function(error) {
                toastr.error('An error occurred.');
                console.error(error);
            }
        });
    }

    function fetchDocuments(documentTypeId) {
        $.ajax({
            url: '/get-documents/' + documentTypeId,
            type: 'GET',
            success: function(response) {
                var documentSelect = $('#document');
                documentSelect.empty();
                $.each(response.documents, function(key, document) {
                    documentSelect.append(new Option(document.name, document.id));
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const assignDocButtons = document.querySelectorAll('.assign-doc-btn');
        assignDocButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const receiverId = button.getAttribute('data-receiver-id');
                const receiverTypeId = button.getAttribute('data-receiver-type-id');

                // Set the receiver's ID and type in the hidden fields
                document.getElementById('modalReceiverId').value = receiverId;
                document.getElementById('modalReceiverTypeId').value = receiverTypeId;
            });
        });
    });
</script>
