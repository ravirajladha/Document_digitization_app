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

                <form id="myAjaxForm" action="{{ route('addReceiver') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card profile-card card-bx m-b30">
                                    <div class="card-header">
                                        <h6 class="title">Add Receiver</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form theme-form projectcreate">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="receiverName" class="form-label">Name</label>
                                                        <input type="text" class="form-control" name="name"
                                                            id="receiverName" placeholder="Enter Receiver's Name">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="receiverEmail" class="form-label">Email</label>
                                                        <input type="email" class="form-control" name="email"
                                                            id="receiverEmail" placeholder="Enter Receiver's Email">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="receiverPhone" class="form-label">Phone</label>
                                                        <input type="text" class="form-control" name="phone"
                                                            id="receiverPhone"
                                                            placeholder="Enter Receiver's Phone Number">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="receiverCity" class="form-label">City</label>
                                                        <input type="text" class="form-control" name="city"
                                                            id="receiverCity" placeholder="Enter Receiver's City">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="receiverType" class="form-label">Receiver
                                                            Type</label>
                                                        <select class="form-control" id="receiverType"
                                                            name="receiver_type_id">
                                                            @foreach ($receiverTypes as $type)
                                                                <option value="{{ $type->id }}">{{ $type->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
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
                <!-- Button trigger modal -->
                {{-- <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">Modal centered</button> --}}
                <!-- Modal -->
                {{-- <div class="modal fade" id="exampleModalCenter">
             <div class="modal-dialog modal-dialog-centered" role="document">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title">Modal title</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal">
                         </button>
                     </div>
                     <div class="modal-body">
                         <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                     </div>
                     <div class="modal-footer">
                         <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                         <button type="button" class="btn btn-primary">Save changes</button>
                     </div>
                 </div>
             </div>
         </div> --}}
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
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
                                                    <th scope="col">Action</th>
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
                                                        <td>{{ optional($item->receiverType)->name }}</td> <!-- Assuming you have a relation to get the receiver type name -->
                                                        <td>
                                                            <button class="btn btn-primary edit-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#exampleModalCenter"
                                                                    data-receiver-id="{{ $item->id }}"
                                                                    data-receiver-name="{{ $item->name }}"
                                                                    data-receiver-phone="{{ $item->phone }}"
                                                                    data-receiver-city="{{ $item->city }}"
                                                                    data-receiver-email="{{ $item->email }}"
                                                                    data-receiver-type-id="{{ $item->receiver_type_id }}">Edit</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            
                                        </table>

                                        <!-- Modal (outside the loop) -->
                                        <!-- Modal -->
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
                                                                <label for="receiverType" class="form-label">Receiver Type</label>
                                                                <select class="form-control" id="receiverType" name="receiver_type_id">
                                                                    @foreach($receiverTypes as $type)
                                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                                    @endforeach
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
                                        

                                        {{-- modal end --}}
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
            $.each(receivers, function(index, receiver) {
                newTableContent += '<tr>' +
                    '<th scope="row">' + (index + 1) + '</th>' +
                    '<td>' + receiver.name + '</td>' +
                    '<td>' + receiver.phone + '</td>' +
                    '<td>' + receiver.city + '</td>' +
                    '<td>' + receiver.email + '</td>' +
                    '<td>' + receiver.receiver_type_name + '</td>' + // Make sure you have the receiver type name available
                    '<td><Button class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" data-receiver-id="' + receiver.id + '" data-receiver-name="' + receiver.name + '" data-receiver-phone="' + receiver.phone + '" data-receiver-city="' + receiver.city + '" data-receiver-email="' + receiver.email + '" data-receiver-type-id="' + receiver.receiver_type_id + '">Edit</Button></td>' +
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

        // Update the form fields
        $('#updateReceiverForm #receiverId').val(receiverId);
        $('#updateReceiverForm #receiverName').val(receiverName);
        $('#updateReceiverForm #receiverPhone').val(receiverPhone);
        $('#updateReceiverForm #receiverCity').val(receiverCity);
        $('#updateReceiverForm #receiverEmail').val(receiverEmail);
        $('#updateReceiverForm #receiverType').val(receiverTypeId);
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
</script>
