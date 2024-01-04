<x-app-layout>


    @include('layouts.header')
    @include('layouts.sidebar')

    <div class="content-body default-height">
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
                                    <tbody>
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
                                                    ))
                                                    <tr>
                                                        <th>{{ ucwords(str_replace('_', ' ', $attribute)) }}</th>
                                                        <td>{{ $value ?? 'null' }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach


                                        @endif

                                        @foreach ($columnMetadata as $meta)
                                        <tr>
                                            @if (!in_array($meta->column_name, ['id', 'created_at', 'updated_at', 'status']))
                                                @if (!in_array($meta->data_type, [3, 4, 6]))
                                                    @php
                                                        $columnName = ucWords(str_replace('_', ' ', $meta->column_name));
                                                        $value = $document->{$meta->column_name} ?? 'null'; // If no value, 'null' will be displayed
                                                    @endphp
                                                    <th>{{ $columnName }}</th>
                                                    <td>{{ $value }}</td>
                                                @endif
                                            @endif
                                        </tr>
                                    @endforeach
                                    

                                    </tbody>
                                </table>
                            </div>
                        </div>










                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h3>File</h3>
                                <div class="row">
                                    @foreach ($columnMetadata as $column)
                                        @if (!($column->column_name == 'id' || $column->column_name == 'created_at' || $column->column_name == 'updated_at' || $column->column_name == 'status'))
                                            @php
                                                $columnName = ucWords(str_replace('_', ' ', $column->column_name));
                                                $defaultImagePath = asset('/assets/sample/image.jpg'); // Set the path to your default image
                                                $defaultPdfPath = asset('/assets/sample/pdf.pdf'); // Set the path to your default PDF
                                                $defaultVideoPath = asset('/assets/sample/video.mp4'); // Set the path to your default video
                                            @endphp
                                            @if ($column->data_type == 3 || $column->data_type == 4 || $column->data_type == 6)
                                                <h4 class="mt-2">{{ $columnName }}</h4>
                                            @endif
                                            @if ($column->data_type == 3)
                                                <img src="{{                         $document->{$column->column_name}
 ? url(                        $document->{$column->column_name}
) : $defaultImagePath }}"
                                                    alt="{{ $columnName }}">
                                            @elseif($column->data_type == 4)
                                                <iframe
                                                    src="{{                         $document->{$column->column_name}
 ? url(                        $document->{$column->column_name}
) : $defaultPdfPath }}"
                                                    width="100%" height="600"></iframe>
                                            @elseif($column->data_type == 6)
                                                <video width="100%" height="500" controls>
                                                    <source
                                                        src="{{                         $document->{$column->column_name}
                                                        ? url(                        $document->{$column->column_name}
) : $defaultVideoPath }}"
                                                        type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endif
                                        @endif
                                    @endforeach

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
            </div>
        </div>

    </div>


    @include('layouts.footer')


</x-app-layout>

