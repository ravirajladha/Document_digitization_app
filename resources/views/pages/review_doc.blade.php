<x-app-layout>


    <x-header />
    @include('layouts.sidebar')

    <div class="content-body default-height ">
        <!-- row -->
        <div class="container-fluid">
            <div class="row page-titles">
                <ol class="breadcrumb">
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
                                @if (Auth::user()->type == 'admin')
                            
                                    <a class="btn btn-primary float-end" href="{{ url('/') }}/edit_document_basic_detail/{{ $document->doc_id }}" rel="noopener noreferrer">Edit</a>
                           
                                @endif
                            </div>
                            <div class="card-body">

                                <table class="table m-2">
                                    <tbody style="padding:0 0 0 0;">
                                        @if ($master_data)
                                            @foreach ($master_data->getAttributes() as $attribute => $value)
                                                @if (
                                                    !(
                                                        $attribute == 'created_by' ||
                                                        $attribute == 'created_at' ||
                                                        $attribute == 'updated_at' ||
                                                        $attribute == 'status_id' ||
                                                        $attribute == 'set_id' ||
                                                        $attribute == 'batch_id' ||
                                                        // $attribute == 'rejection_message' ||
                                                        $attribute == 'rejection_timestamp' ||
                                                        $attribute == 'bulk_uploaded' ||
                                                        $attribute == 'physically' ||
                                                        
                                                        $attribute == 'id'
                                                    ) && $value !== null &&  $value !=="" )
                                                    <tr style="padding:0 0 0 0;">
                                                        <th style="padding: 5px;">
                                                            {{ ucwords(str_replace('_', ' ', $attribute)) }}</th>
                                                        <td style="padding: 5px;">{{ $value }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif

                                        <tr style="height: 20px;"></tr>
                                        {{-- {{dd($columnMetadata)}} --}}
                                        @foreach ($columnMetadata as $meta)
                                            @if (!in_array($meta->column_name, ['id', 'created_at', 'updated_at', 'status']))
                                                @if (!in_array($meta->data_type, [3, 4, 6]) )
                                                    @php
                                                        $columnName = ucWords(str_replace('_', ' ', $meta->column_name));
                                                        $value = $document->{$meta->column_name} ?? null;
                                                    @endphp
                                                    @if ($value !== null)
                                                        {{-- Add this check --}}
                                                        <tr style="padding:0 0 0 0;">
                                                            <th style="padding: 5px;">{{ $columnName }}</th>
                                                            <td style="padding: 5px;">{{ $value }}</td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    {{-- <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h3>File</h3>
                                <div class="row">
                                    @php $counter = 0; @endphp
                                    @foreach ($columnMetadata as $column)
                                        @if (!($column->column_name == 'id' || $column->column_name == 'created_at' || $column->column_name == 'updated_at' || $column->column_name == 'status'))
                                            @php
                                                $columnName = ucWords(str_replace('_', ' ', $column->column_name));
                                                $defaultImagePath = asset('/assets/sample/image.jpg'); 
                                                $defaultPdfPath = asset('/assets/sample/pdf.pdf'); 
                                                $defaultVideoPath = asset('/assets/sample/video.mp4');  
                                            @endphp
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
                                             
                                                @php $counter++; @endphp
                                            @elseif($column->data_type == 6)
                                                <video width="100%" height="500" controls controlsList="nodownload">
                                                    <source
                                                        src="{{ $document->{$column->column_name} ? url($document->{$column->column_name}) : $defaultVideoPath }}"
                                                        type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>

                                                @php $counter++; @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                    @if ($counter == 0)
                                        <div class="col-lg-12">
                                            <p>No files to display.</p>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div> --}}




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
                                    <h4 class="mt-2">PDF File</h4>
                                    <div class="pointer-events: auto;">
                                        <iframe
                                            src="{{ url($document->pdf_file_path) }}"
                                            width="100%" height="600"
                                            frameborder="0"
                                            oncontextmenu="return false;">
                                        </iframe>
                                    </div>
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

             




                <div class="row mb-5">
                    <div class="col-lg-12">
                        <div class="card">
                
                            {{-- Edit Button --}}
                            @if (Auth::user()->type == 'admin')
                            <div class="card-header">
                               Document Verification <i style="font-size:12px;">Three stage: Pending, Hold, Accept. To keep the document on hold, message is mandatory. Once accepted, the document status can't be changed</i>
                            </div>
                            @endif
                
                            <div class="card-body">
                                {{-- Status Form --}}
                                @if ($document->status == 0 || $document->status == 2)
                                <form action="{{ url('/') }}/update_document" method="post" class="mb-3">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $document->id }}">
                                    <input type="hidden" name="type" value="{{ $tableName }}">
                
                                    <div class="form-group">
                                        <select id="single-select" name="status" onchange="handleStatusChange(this)" class="form-select">
                                            <option value="0" {{ $document->status == 0 ? 'selected' : '' }}>Pending</option>
                                            <option value="1">Accept</option>
                                            <option value="2" {{ $document->status == 2 ? 'selected' : '' }}>Hold</option>
                                        </select>
                                    <input type="hidden" id="holdReason" name="holdReason">

                                    </div>
                                </form>
                                @else
                                <div class="alert alert-success">
                                    <strong>Accepted</strong>
                                  
                                </div>
                                @endif
                
                                {{-- Rejection Message --}}
                                @if ($document->status == 2 && $master_data->rejection_message)
                                <div class="alert alert-warning">
                                    <strong>Hold Reason:</strong> {{ $master_data->rejection_message }}
                                    <div><small>{{ $master_data->rejection_timestamp }}</small></div>
                                </div>
                                @elseif($document->status ==0)
                                <div class="alert alert-primary">
                                    <strong>Status : Pending</strong> 
                                   
                                </div>
                               
                                @endif
                            </div>
                
                        </div>
                    </div>
                </div>
                

              

                <div class="container-fluid">
                    <div class="row">
                        {{-- {{ dd($matchingData) }} --}}
                        @foreach ($matchingData as $data)
                            @if (!empty($data))
                                <div class="col-xl-4 col-lg-12 col-sm-12">
                                    <div class="card overflow-hidden">
                                        <div class="text-center p-3 overlay-box "
                                            style="background-image: url(images/big/img1.jpg);">
                                            <div class="profile-photo">
                                                <img src="images/profile/profile.png" width="100"
                                                    class="img-fluid rounded-circle" alt="">
                                            </div>
                                            <h6 class="mt-3 mb-1 text-white">Common Document</h6>

                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between"><span
                                                    class="mb-0">Document Name</span> <strong
                                                    class="text-muted">{{ $data->document_name }} </strong></li>
                                            <li class="list-group-item d-flex justify-content-between"><span
                                                    class="mb-0">Document Type</span> <strong
                                                    class="text-muted">{{ $data->doc_type }} </strong></li>
                                        </ul>
                                        <div class="card-footer border-0 mt-0">
                                            <a href="{{ url('/') }}/review_doc/{{ $data->doc_type }}/{{ $data->id }}"
                                                target="_blank" type="button"
                                                class="btn btn-primary btn-block">View</a>
                                            {{-- <button class="btn btn-primary btn-block">
                                        <i class="fa fa-bell-o"></i> View							
                                    </button>							 --}}

                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>


    @include('layouts.footer')


</x-app-layout>
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
        } else {
            select.form.submit();
        }
    }

    // function handleStatusChange(select) {
    // if (select.value == "2") { // If 'Hold' is selected
    //     const reason = window.prompt("Please enter the reason for holding:");
    //     if (reason) {
    //         document.getElementById('holdReason').value = reason;
    //         select.form.submit();
    //     } else {
    //         select.value = select.dataset.previous; // Revert to previous value if no reason given
    //     }
    // } else {
    //     select.form.submit();
    // }
// }



</script>
