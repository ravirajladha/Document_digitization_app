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
                                                        $attribute == 'id'
                                                    ) && $value !== null)
                                                    <tr style="padding:0 0 0 0;">
                                                        <th style="padding: 5px;">
                                                            {{ ucwords(str_replace('_', ' ', $attribute)) }}</th>
                                                        <td style="padding: 5px;">{{ $value }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif

                                        <tr style="height: 20px;"></tr>
                                        @foreach ($columnMetadata as $meta)
                                            @if (!in_array($meta->column_name, ['id', 'created_at', 'updated_at', 'status']))
                                                @if (!in_array($meta->data_type, [3, 4, 6]))
                                                    @php
                                                        $columnName = ucWords(str_replace('_', ' ', $meta->column_name));
                                                        $value = $document->{$meta->column_name} ?? 'null';
                                                    @endphp
                                                    @if ($value !== 'null')
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
                        <div class="my-auto">
                            <div class="text-end d-flex justify-content-end align-items-center">
                                @if (Auth::user()->type == 'admin')
                                    <!-- Edit button -->
                                    <a class="btn btn-primary me-2"
                                        href="{{ url('/') }}/edit_document_basic_detail/{{ $document->doc_id }}"
                                        rel="noopener noreferrer">Edit</a>
                                @endif

                                <!-- Check if the document has not been accepted -->
                                @if ($document->status == 0)
                                    <!-- Accept button form -->
                                    <form action="{{ url('/') }}/update_document" method="post" class="d-inline">
                                        @csrf
                                        <input type="hidden" value="{{ $tableName }}" name="type">
                                        <input type="hidden" value="{{ $document->id }}" name="id">
                                        <button type="submit" class="btn btn-primary">Accept</button>
                                    </form>
                                @else
                                    <!-- Accepted indicator -->
                                    <button type="button" class="btn btn-success" disabled>Accepted</button>
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
