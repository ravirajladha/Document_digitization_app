<x-app-layout>


    <x-header />
    <x-sidebar />

    <div class="content-body default-height ">
        <!-- row -->
        <div class="container-fluid">
            <div class="row page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>

                    <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Document Details</a></li>
                </ol>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Data</h4>


                                @if ($user && $user->hasPermission('Update Basic Document Detail') && $master_data->status_id != 1)
                                    <a class="btn btn-primary float-end"
                                        href="{{ url('/') }}/edit_document_basic_detail/{{ $document->doc_id }}"
                                        rel="noopener noreferrer">Edit</a>
                                @endif
                            </div>
                            <div class="card-body">
                                <style>
                                    .table-responsive {
                                        width: 100%;
                                        /* Adjust the width as needed */
                                    }

                                    .table {
                                        overflow-x: auto;
                                    }
                                </style>
                                <div class="table-responsive">
                                    <table class="table table-striped table-responsive-sm">
                                        <tbody style="padding:0 0 0 0;">
                                            @if ($master_data)
                                                @php
                                                    $latitude = null;
                                                    $longitude = null;
                                                @endphp
                                                {{-- @dd($master_data->getAttributes()) --}}
                                                @foreach ($master_data->getAttributes() as $attribute => $value)
                                                    @if (
                                                        !(
                                                            $attribute == 'created_by' ||
                                                            $attribute == 'created_at' ||
                                                            $attribute == 'updated_at' ||
                                                            $attribute == 'status_id' ||
                                                            $attribute == 'set_id' ||
                                                            $attribute == 'batch_id' ||
                                                            $attribute == 'document_type' ||
                                                            $attribute == 'rejection_timestamp' ||
                                                            $attribute == 'bulk_uploaded' ||
                                                            $attribute == 'physically' ||
                                                            $attribute == 'temp_id' ||
                                                            $attribute == 'id'
                                                        ) &&
                                                            $value !== null &&
                                                            $value !== '')
                                                        @php
                                                            if ($attribute === 'document_type_name') {
                                                                $value = ucWords(str_replace('_', ' ', $value));
                                                            }
                                                            if ($attribute === 'unit') {
                                                                if ($value == 1) {
                                                                    $value = 'Acres and Cents';
                                                                } elseif ($value == 2) {
                                                                    $vlaue = 'Square Feet';
                                                                }
                                                            }
                                                            // Check for latitude and longitude

                                                            if ($attribute === 'longitude') {
                                                                $longitude = $value;
                                                            }
                                                            if ($attribute === 'latitude') {
                                                                $latitude = $value;
                                                            }
                                                        @endphp
                                                        @php
                                                            $truncatedValue =
                                                                strlen($value) > 35 ? substr($value, 0, 35) : $value;
                                                        @endphp
                                                        <tr style="white-space: nowrap; overflow: hidden;">
                                                            <th style="padding: 5px;">
                                                                {{ ucwords(str_replace('_', ' ', $attribute)) }}</th>

                                                            <td style="padding: 5px;">
                                                                @if (strlen($value) > 35)
                                                                    {{ $truncatedValue }}
                                                                    <span data-bs-toggle="modal"
                                                                        data-bs-target="#{{ $attribute }}Modal"
                                                                        style="cursor: pointer; text-decoration: underline;">
                                                                        ...
                                                                    </span>
                                                                @else
                                                                    {{ $value }}
                                                                @endif


                                                            </td>
                                                        </tr>

                                                        {{-- Remove comment to debug latitude and longitude --}}
                                                        <div class="modal fade" id="{{ $attribute }}Modal"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="{{ $attribute }}ModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="{{ $attribute }}ModalLabel">
                                                                            {{ ucwords(str_replace('_', ' ', $attribute)) }}
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        {{ $value }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        {{-- {{ dd($latitude) }} --}}
                                                        @if ($latitude !== null && $longitude !== null)
                                                            <tr>
                                                                <th style="padding: 5px;">Location</th>
                                                                <td style="padding: 5px;">
                                                                    <a href="https://www.google.com/maps/search/{{ $latitude }},{{ $longitude }}"
                                                                        target="_blank" class="btn btn-primary"><i
                                                                            class="fa fa-location"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                @endforeach


                                            @endif

                                            <tr style="height: 20px;"></tr>
                                            @php
                                                $normalColumns = [];
                                                $specialColumns = [];
                                            @endphp

                                            @foreach ($columnMetadata as $meta)
                                                @if (!in_array($meta->column_name, ['id', 'created_at', 'updated_at', 'status']))
                                                    @if (!in_array($meta->data_type, [3, 4, 6]))
                                                        @php
                                                            $columnName = ucwords(
                                                                str_replace('_', ' ', $meta->column_name),
                                                            );
                                                            $value = $document->{$meta->column_name} ?? null;
                                                            $truncatedValue =
                                                                strlen($value) > 35 ? substr($value, 0, 35) : $value;
                                                            $modalTarget = $meta->column_name . 'Modal';
                                                            $isSpecial = $meta->special == 1; // Ensure you're comparing values, not types
                                                        @endphp

                                                        @if ($isSpecial)
                                                            @php
                                                                $specialColumns[] = [
                                                                    'name' => $columnName,
                                                                    'value' => $value,
                                                                    'modalTarget' => $modalTarget,
                                                                ];
                                                            @endphp
                                                        @else
                                                            @php
                                                                $normalColumns[] = [
                                                                    'name' => $columnName,
                                                                    'value' => $value,
                                                                    'truncatedValue' => $truncatedValue,
                                                                    'modalTarget' => $modalTarget,
                                                                ];
                                                            @endphp
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach

                                            @foreach ($normalColumns as $column)
                                                @if ($column['value'] !== null && $column['value'] !== '')
                                                    <tr style="padding:0 0 0 0;">
                                                        <th style="padding: 5px;">{{ $column['name'] }}</th>
                                                        <td>
                                                            @if (strlen($column['value']) > 35)
                                                                {{ $column['truncatedValue'] }}
                                                                <span data-bs-toggle="modal"
                                                                    data-bs-target="#{{ $column['modalTarget'] }}"
                                                                    style="cursor: pointer; text-decoration: underline; padding: 0;">
                                                                    ...
                                                                </span>
                                                            @else
                                                                {{ $column['value'] }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <div class="modal fade" id="{{ $column['modalTarget'] }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="{{ $column['modalTarget'] }}Label"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="{{ $column['modalTarget'] }}Label">
                                                                        {{ $column['name'] }}</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    {{ $column['value'] }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h3>File</h3>
                                <div class="row">
                                    @php $counter = 0; @endphp
                                    @foreach ($columnMetadata as $column)
                                        @if (
                                            !(
                                                $column->column_name == 'id' ||
                                                $column->column_name == 'created_at' ||
                                                $column->column_name == 'updated_at' ||
                                                $column->column_name == 'status'
                                            ))
                                            @php
                                                $columnName = ucWords(str_replace('_', ' ', $column->column_name));
                                                $value = $document->{$column->column_name} ?? null;
                                            @endphp
                                            @if ($value !== null)
                                                @if ($column->data_type == 3 || $column->data_type == 4 || $column->data_type == 6)
                                                    <h4 class="mt-2">{{ $columnName }}</h4>
                                                    @php $counter++; @endphp
                                                @endif
                                                @if ($column->data_type == 3)
                                                    <img src="{{ $document->{$column->column_name} ? url($document->{$column->column_name}) : $defaultImagePath }}"
                                                        alt="{{ $columnName }}" oncontextmenu="return false;">
                                                    @php $counter++; @endphp
                                                @elseif($column->data_type == 4)
                                                    <div class="pointer-events: auto;">
                                                        <iframe
                                                            src="{{ $document->{$column->column_name} ? url($document->{$column->column_name}) : $defaultPdfPath }}"
                                                            width="100%" height="600"
                                                            oncontextmenu="return false;"></iframe>
                                                    </div>
                                                    {{-- #toolbar=0 --}}
                                                    @php $counter++; @endphp
                                                @elseif($column->data_type == 6)
                                                    <video width="100%" height="500" controls
                                                        controlsList="nodownload">
                                                        <source
                                                            src="{{ $document->{$column->column_name} ? url($document->{$column->column_name}) : $defaultVideoPath }}"
                                                            type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                    @php $counter++; @endphp
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                    @if ($document->pdf_file_path)
                                        @php
                                            // Get the file extension
                                            $extension = strtolower(
                                                pathinfo($document->pdf_file_path, PATHINFO_EXTENSION),
                                            );
                                        @endphp

                                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg']))
                                            <h4 class="mt-2">Image File</h4>
                                            <div>
                                                <img src="{{ url($document->pdf_file_path) }}" width="100%"
                                                    alt="Document Image">
                                            </div>
                                        @elseif($extension === 'pdf')
                                            <h4 class="mt-2">PDF File</h4>
                                            <div class="pointer-events: auto;">
                                                <iframe src="{{ url($document->pdf_file_path) }}" width="100%"
                                                    height="600" frameborder="0" oncontextmenu="return false;">
                                                </iframe>
                                            </div>
                                        @else
                                            <div class="col-lg-12">
                                                <p>No files to display.</p>
                                            </div>
                                        @endif
                                    @elseif ($counter == 0)
                                        <div class="col-lg-12">
                                            <p>No files to display.</p>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(function() {
                        $('[data-toggle="tooltip"]').tooltip()
                    })
                </script>

                @php
                    $hasNonEmptyValue = false;
                    foreach ($specialColumns as $specialColumn) {
                        if (!empty($specialColumn['value'])) {
                            $hasNonEmptyValue = true;
                            break; // Exit the loop early if any non-empty value is found
                        }
                    }
                @endphp

                @if ($hasNonEmptyValue)
                    <div class="card">
                        <div class="card-header">Insights</div>
                        <div class="card-body">
                            <div class="row">

                                @foreach ($specialColumns as $specialColumn)
                                    @if ($specialColumn['value'] !== null && $specialColumn['value'] !== '')
                                        <div class="col-xl-4 col-lg-4 col-xxl-4 col-sm-4">
                                            <div class="card text-white bg-dark">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex justify-content-between"><span
                                                            class="mb-0 mt-0 text-white" style="font-size: 20px;padding-right: 30px;">{{ $specialColumn['name'] }}</span><strong
                                                            class="text-white">{{ $specialColumn['value'] }}</strong>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                    </div>
                @endif

                @if ($user && $user->hasPermission('Update Document Status'))

                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Doc Verification</h5>
                                    <div class="bootstrap-popover d-inline-block float-end">
                                        <button type="button" class="btn btn-primary btn-sm px-4 "
                                            data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top"
                                            data-bs-content="Three stages: Pending, Hold,
                                            Approve. To keep the document on hold, message is mandatory. Once approved, the
                                            document status can't be changed."
                                            title="Verification Guidelines"><i
                                                class="fas fa-info-circle"></i></button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    {{-- Status Form --}}
                                    @if ($document->status == 0 || $document->status == 2 || $document->status == 3)
                                        <form action="{{ url('/') }}/update_document" method="post"
                                            class="mb-3">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $document->id }}">
                                            <input type="hidden" name="type" value="{{ $tableName }}">
                                            <div class="form-group">
                                                <select id="single-select" name="status"
                                                    onchange="handleStatusChange(this)" class="form-select">
                                                    <option value="0"
                                                        {{ $document->status == 0 ? 'selected' : '' }}>
                                                        Pending</option>
                                                    <option value="1">Approve</option>
                                                    <option value="2"
                                                        {{ $document->status == 2 ? 'selected' : '' }}>
                                                        Hold</option>
                                                    <option value="3"
                                                        {{ $document->status == 3 ? 'selected' : '' }}>
                                                        Reviewer Feedback</option>
                                                </select>
                                                <input type="hidden" id="holdReason" name="holdReason">

                                            </div>
                                        </form>
                                    @else
                                        <div class="alert alert-success">
                                            <strong>Approved</strong>

                                        </div>
                                    @endif

                                    {{-- Rejection Message --}}
                                    @if ($document->status == 2 && $master_data->rejection_message)
                                        <div class="alert alert-dark">
                                            <strong>Hold Reason:</strong> {{ $master_data->rejection_message }}
                                            <div><small>
                                                    {{ \Carbon\Carbon::parse($master_data->rejection_timestamp)->format('m/d/Y') }}</small>
                                            </div>
                                        </div>
                                    @elseif($document->status == 0)
                                        <div class="alert alert-primary">
                                            <strong>Current Status : Pending</strong>

                                        </div>
                                    @elseif ($document->status == 3 && $master_data->rejection_message)
                                        <div class="alert alert-dark">
                                            <strong>Reviewer Feedback:</strong> {{ $master_data->rejection_message }}
                                            <div><small>
                                                    {{ \Carbon\Carbon::parse($master_data->rejection_timestamp)->format('m/d/Y') }}</small>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- Document Status Logs --}}
                                    @if ($document->status != 1)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Serial Number</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                        <th>Reason</th>
                                                        <th>Created By</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($document_logs as $index => $log)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                <span class="badge bg-{{ 
                                                                    $log->status == 0 ? 'danger' : 
                                                                    ($log->status == 1 ? 'success' : 
                                                                    ($log->status == 2 ? 'dark' : 
                                                                    'warning')) 
                                                                }}">
                                                                    @if ($log->status == 0)
                                                                        Pending
                                                                    @elseif ($log->status == 1)
                                                                        Approved
                                                                    @elseif ($log->status == 2)
                                                                        Hold
                                                                    @elseif ($log->status == 3)
                                                                        Reviewer Feedback
                                                                    @endif
                                                                </span>
                                                                
                                                            </td>
                                                            <td>{{ date('H:i:s d/m/Y ', strtotime($log->created_at)) }}
                                                            </td>
                                                            <td>{{ $log->message ? $log->message : 'N/A' }}</td>
                                                            <td>{{ $log->creator_name }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif


                                </div>

                            </div>
                        </div>
                    </div>
                @endif


                <div class="container-fluid">
                    <div class="row">
                        {{-- {{ dd($matchingData) }} --}}
                        @foreach ($matchingData as $data)
                            @if (!empty($data))
                                <div class="col-xl-6 col-lg-12 col-sm-12">
                                    <div class="card overflow-hidden">
                                        <div class="text-center p-3 overlay-box "
                                            style="background-image: url(images/big/img1.jpg);">
                                            {{-- <div class="profile-photo">
                                                <img src="images/profile/profile.png" width="100"
                                                    class="img-fluid rounded-circle" alt="">
                                            </div> --}}
                                            <h6 class="mt-3 mb-1 text-white">Common Document</h6>

                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between"><span
                                                    class="mb-0">Document Name</span> <strong
                                                    class="text-muted">{{ $data->document_name }} </strong></li>
                                            <li class="list-group-item d-flex justify-content-between"><span
                                                    class="mb-0">Document Type</span> <strong
                                                    class="text-muted">{{ ucwords(str_replace('_', ' ', $data->doc_type)) }}
                                                </strong></li>
                                        </ul>
                                        <div class="card-footer border-0 mt-0">
                                            <a href="{{ url('/') }}/review_doc/{{ $data->doc_type }}/{{ $data->id }}"
                                                target="_blank" type="button" class="btn btn-primary btn-block">View
                                                <i class="fa fa-eye"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- compliance data --}}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-header" style="padding:0 0 0 0">
                                    <h5>Compliances</h5>


                                    @if ($user && $user->hasPermission('Add Compliances') && $master_data->status_id == 1)
                                        <button type="button" class="btn btn-success mb-2 float-end btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#exampleModalCenter"> <i
                                                class="fas fa-square-plus"></i>&nbsp;Add Compliance</button>
                                    @endif

                                </div>

                                <div class="table-responsive" style="padding:7px;">
                                    <table id="example3" class="display" style="min-width: 845px;">

                                        <thead>
                                            <tr>
                                                <th scope="col">Sl. No.</th>
                                                <th scope="col">Name</th>
                                                {{-- <th scope="col">Document Name </th>
                                <th scope="col">Document Type </th> --}}
                                                <th scope="col">Due Date</th>
                                                <th scope="col">Is Recurring </th>
                                                {{-- <th scope="col">Status </th> --}}
                                                <th scope="col">Action </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($compliances as $index => $item)
                                                <tr data-item-id="{{ $item->id }}">
                                                    <th scope="row">{{ $index + 1 }}</th>

                                                    <td>{{ $item->name }}</td>
                                                    {{-- <td>{{ $item->documentType->name }}</td>
                                    <td>{{ $item->document->name }}</td> --}}
                                                    <td>{{ date('d-m-Y', strtotime($item->due_date)) }}</td>


                                                    <td> {!! $item->is_recurring
                                                        ? '<span class="badge bg-success">Yes</span>'
                                                        : '<span class="badge bg-warning text-dark">Not</span>' !!}</td>

                                                    <td class="action-cell">
                                                        <!-- Action buttons based on status -->
                                                        @if ($item->status == 0)
                                                            <!-- Show buttons only if status is Pending -->
                                                            <button class="btn btn-sm btn-success toggle-status"
                                                                data-id="{{ $item->id }}" data-action="settle"><i
                                                                    class="fas fa-thumbs-up"></i></button>
                                                            <button class="btn btn-sm btn-danger toggle-status"
                                                                data-id="{{ $item->id }}" data-action="cancel"><i
                                                                    class="fas fa-cancel"></i></button>
                                                        @elseif($item->status == 1)
                                                            <span class="badge bg-success">Settled</span>
                                                        @elseif($item->status == 2)
                                                            <span class="badge bg-danger">Cancelled</span>
                                                        @else
                                                            <span class="badge bg-success">Unknown data</span>
                                                        @endif
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
                {{-- compliance data end --}}
                {{--        compliances modal start --}}


                <div class="modal fade" id="exampleModalCenter">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Compliances</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form theme-form projectcreate">
                                    <form id="myAjaxForm" action="{{ url('/') }}/create-compliances"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <input type="text" hidden value="{{ $document_id }}"
                                                name="document_id" required>
                                            <input type="text" hidden value="{{ $doc_type->id }}"
                                                name="document_type" required>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="document" class="form-label">Name</label>
                                                    <input class="form-control" type="text" name="name"
                                                        placeholder="Enter name for the Compliance" required required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="document" class="form-label">Due Date</label>
                                                    <input class="form-control" type="date" name="due_date"
                                                        required required>
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <div class="col-sm-6">Is Recurring ?</div>
                                                <div class="col-sm-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="is_recurring"
                                                            type="checkbox" value="1">
                                                        <label class="form-check-label">
                                                            Yes
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light"
                                    data-bs-dismiss="modal">Close</button>
                                <div id="loader" style="display: none;">
                                    Loading...
                                </div>
                                <button type="submit" class="btn btn-success" id="submitBtn">Submit Form</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{--        compliances modal end --}}

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-header">
                                    <h4>Assigned Documents</h4>
                                    @if ($user && $user->hasPermission('Assign Document') && $master_data->status_id == 1)
                                        <button type="button" class="btn btn-success mb-2 float-end btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#exampleModalCenter1"> <i
                                                class="fas fa-square-plus"></i>&nbsp;Assign Document</button>
                                    @endif
                                </div>
                                <div class="table-responsive">
                                    <table id="example3" class="display" style="min-width: 845px">


                                        <thead>
                                            <tr>
                                                <th scope="col">Sl. No.</th>
                                                <th scope="col">Receiver Name</th>
                                                <th scope="col">Receiver Type</th>
                                                <th scope="col">Document Name </th>
                                                <th scope="col">Document Type </th>
                                                <th scope="col">Expires At </th>

                                                <th scope="col">Email Viewed </th>
                                                <th scope="col">Status </th>
                                                @if ($user && $user->hasPermission('Update Document Assignment Status'))
                                                    <th scope="col">Action </th>
                                                @endif


                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($documentAssignments as $index => $item)
                                                <tr>
                                                    <th scope="row">{{ $index + 1 }}</th>
                                                    <td>{{ $item->receiver->name }}</td>
                                                    <td>{{ $item->receiverType->name }}</td>
                                                    <td>{{ ucwords(str_replace('_', ' ', $item->documentType->name)) }}
                                                    </td>
                                                    <td>{{ $item->document->name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->expires_at)->format('M d, Y, g:i A') }}
                                                    </td>

                                                    <td> {!! $item->first_viewed_at
                                                        ? '<span class="badge bg-success">Yes</span>'
                                                        : '<span class="badge bg-warning text-dark">Not Yet</span>' !!}</td>
                                                    <td> {!! $item->status
                                                        ? '<span class="badge bg-success">Active</span>'
                                                        : '<span class="badge bg-warning text-dark">Inactive</span>' !!}</td>


                                                    @if ($user && $user->hasPermission('Update Document Assignment Status'))
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-sm {{ $item->status ? 'btn-danger' : 'btn-success' }}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#confirmationModal"
                                                                data-action="{{ route('documents.assigned.toggleStatus', $item->id) }}">
                                                                {{ $item->status ? 'Deactivate' : 'Activate' }}
                                                            </button>
                                                        </td>
                                                    @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="exampleModalCenter1">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Assign Document</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="form theme-form projectcreate">
                                    <form id="myAjaxForm" action="{{ url('/') }}/assign-documents-to-receiver"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">

                                            <input type="text" hidden value="{{ $document_id }}"
                                                name="document_id" required>
                                            <input type="text" hidden value="{{ $doc_type->id }}"
                                                name="document_type" required>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="receiverType" class="form-label">Receiver
                                                        Type</label>
                                                    <select class="form-control" id="receiverType"
                                                        name="receiver_type" onchange="fetchReceivers(this.value)"
                                                        required>
                                                        <option value="">Select Receiver Type</option>
                                                        @foreach ($receiverTypes as $type)
                                                            <option value="{{ $type->id }}">{{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="receiver" class="form-label">Receiver</label>
                                                    <select class="form-control" id="receiver" name="receiver_id"
                                                        required>
                                                        <option value="">Select Receiver</option>
                                                        <!-- Options will be populated based on Receiver Type selection -->
                                                    </select>
                                                </div>
                                            </div>



                                        </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light"
                                    data-bs-dismiss="modal">Close</button>
                                <div id="loader" style="display: none;">
                                    Loading...
                                </div>
                                <button type="submit" class="btn btn-success" id="submitBtn">Submit Form</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('layouts.footer')

</x-app-layout>
<div class="modal fade" id="confirmationModal">
    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to <span id="actionType">activate/deactivate</span> this document assignment?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function handleStatusChange(select) {
        if (select.value == "2") { // Assuming '2' is the value for 'Hold'
            const reason = window.prompt("Please enter the reason for holding: (* Mandatory)");
            if (reason) {
                document.getElementById('holdReason').value = reason;
                select.form.submit();
            } else {
                select.value = "{{ $document->status }}"; // Revert back to the original value if no reason is provided
            }
        } else if (select.value == "3") {
            const reason = window.prompt("Please enter the feedback: (* Mandatory)");
            if (reason) {
                document.getElementById('holdReason').value = reason;
                select.form.submit();
            } else {
                select.value = "{{ $document->status }}"; // Revert back to the original value if no reason is provided
            }
        } else {
            select.form.submit();
        }
    }
</script>

<script>
    function fetchReceivers(receiverTypeId) {
        $.ajax({
            url: '/get-receivers/' + receiverTypeId,
            type: 'GET',

            success: function(response) {
                console.log(response); // Console the response for debugging

                var receiverSelect = $('#receiver');
                receiverSelect.empty();
                $.each(response.receivers, function(key, receiver) {
                    receiverSelect.append(new Option(receiver.name, receiver.id));
                });
            },
            error: function(xhr, status, error) {
                console.error("Error: ", error); // Console error if AJAX request fails
                console.error("Status: ", status);
                console.error("Response: ", xhr.responseText);
            }
        });
    }
</script>
{{-- compliance scripts
     --}}
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#confirmationModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var action = button.data('action'); // Extract info from data-* attributes
            var actionType = button.text().trim();
            var modal = $(this);

            // Update the modal's content.
            modal.find('.modal-body #actionType').text(actionType.toLowerCase());
            modal.find('#confirmBtn').off('click').on('click', function() {
                // Get CSRF token from meta tag
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                // Submit the form with the action set to the button's data-action attribute
                $('<form method="POST" action="' + action + '">' +
                    '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                    '</form>').appendTo('body').submit(); +
                '<input type="hidden" name="_method" value="POST">'
            });
        });
    });
</script>
