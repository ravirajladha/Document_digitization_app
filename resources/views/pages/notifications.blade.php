<x-app-layout>


    <x-header />
    @include('layouts.sidebar')

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active"><a href="/notifications">Notifications</a></li>

                    </ol>
                </div>


         




                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table id="example3" class="display" style="min-width: 845px">
                                            <button type="button" class="btn btn-success mb-2 float-end btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#exampleModalCenter"> <i
                                                    class="fas fa-square-plus"></i>&nbsp;Notifications</button>

                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl no</th>
                                              
                                                    <th scope="col">Message </th>
                                                
                                                    <th scope="col"> Date</th>
                                           

                                                    {{-- <th scope="col">Status </th> --}}

                                                    {{-- <th scope="col">Action </th> --}}




                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($notifications as $index => $item)
                                                    <tr data-item-id="{{ $item->id }}">
                                                        <th scope="row">{{ $index + 1 }}</th>

                                                        <td>{{ $item->message }}</td>
                                                        {{-- <td>{{ ucwords(str_replace('_', ' ', $item->documentType->name)) }}
                                                        </td> --}}
                                                        {{-- <td>{{ $item->document->name }}</td> --}}
                                                        <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                                        {{-- <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td> --}}


                                                        {{-- <td> {!! $item->is_recurring
                                                            ? '<span class="badge bg-success">Yes</span>'
                                                            : '<span class="badge bg-warning text-dark">Not</span>' !!}</td> --}}
                                                       

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
    // Fetch documents based on the selected document type
    function fetchDocuments(documentTypeId) {
        $.ajax({
            url: '/get-documents/' + documentTypeId,
            type: 'GET',
            success: function(response) {
                var documentSelect = $('#document');
                documentSelect.empty();

                // Check if the response has documents
                if (response.documents && response.documents.length > 0) {
                    $.each(response.documents, function(key, document) {
                        documentSelect.append(new Option(document.name, document.id));
                    });
                } else {
                    // If there are no documents, show an alert and add a default 'No documents' option
                    alert('No documents available for this document type.');
                    documentSelect.append(new Option('No documents available', ''));
                }
            },
            error: function(xhr, status, error) {
                // Handle any Ajax errors here
                alert('An error occurred while fetching the documents.');
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('myAjaxForm');
        var submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function() {
            // Disable the submit button
            submitBtn.disabled = true;
        });
    });
</script>
<script>
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            const action = this.getAttribute('data-action');
            // console.log(itemId);
            Swal.fire({
                title: `Are you sure you want to ${action} this item?`,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, do it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the action (e.g., sending AJAX request to the server)
                    // Replace `your_route_here` with the actual route
                    // Add necessary data or headers as per your requirement
                    fetch(`/status-change-compliance/${itemId}/${action}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                status: action
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire(
                                'Updated!',
                                `The item has been ${action}ed.`,
                                'success'
                            );
                            updateTableRow(itemId, data.newStatus);
                            // Optionally, refresh the page or update the DOM as needed
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            })
        });
    });
</script>
<script>
    //just after any ajax changes is made,this will udpate the table
    function updateTableRow(itemId, newStatus) {
        console.log(itemId);
        const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
        const statusCell = row.querySelector('.status-cell');
        const actionCell = row.querySelector('.action-cell');

        // Update the status cell based on the new status
        switch (newStatus) {
            case 0: // Pending
                // statusCell.innerHTML = '<span class="badge bg-warning text-dark">Pending</span>';
                actionCell.innerHTML = `
                <button class="btn btn-sm btn-success toggle-status"
                        data-id="${itemId}"
                        data-action="settle"><i class="fas fa-thumbs-up"></i></button>
                <button class="btn btn-sm btn-danger toggle-status"
                        data-id="${itemId}"
                        data-action="cancel"><i class="fas fa-plus-cancel"></i></button>`;
                break;
            case 1: // Settled
                // statusCell.innerHTML = '<span class="badge bg-success">Settled</span>';
                actionCell.innerHTML = '<span class="badge bg-success">Settled</span>'; // Remove action buttons
                break;
            case 2: // Cancelled
                // statusCell.innerHTML = '<span class="badge bg-danger">Cancelled</span>';
                actionCell.innerHTML = '<span class="badge bg-danger">Cancelled</span>'; // Remove action buttons
                break;
            default:
                console.error('Unknown status');
        }
    }
</script>
