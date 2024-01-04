<x-app-layout>


    @include('layouts.header')
    @include('layouts.sidebar')

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">

            <div class="page-body">
                <div class="container-fluid">


                    <div class="row page-titles">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <h3>
                                    Add Document</h3>
                            </div>
                            <div class="col-12 col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Add Document1</a>
                                    </li>
                                </ol>
                            </div>
                        </div>

                    </div>
                    <form action="{{ url('/') }}/add_document" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3>Document Type: {{ $table_name }}</h3>
                                            <div class="form theme-form projectcreate">
                                                <div class="row">
                                                    <input type="hidden" value="{{ $table_name }}" name="type">
                                                    <input type="hidden" value="{{ $document_data->doc_id }}"
                                                        name="master_doc_id">

                                                    @foreach ($columnMetadata as $meta)
                                                        @if (!in_array($meta->column_name, ['id', 'document_name', 'doc_id', 'created_at', 'updated_at', 'status', 'doc_type']))
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="{{ $meta->column_name }}"
                                                                        class="form-label">{{ ucfirst(str_replace('_', ' ', $meta->column_name)) }}</label>
                                                                    @switch($meta->data_type)
                                                                        @case(1)
                                                                            {{-- Text input --}}
                                                                            <input type="text" class="form-control"
                                                                                name="{{ $meta->column_name }}"
                                                                                id="{{ $meta->column_name }}"
                                                                                value="{{ old($meta->column_name, $documentData->{$meta->column_name} ?? '') }}"
                                                                                required>
                                                                        @break

                                                                        @case(2)
                                                                            {{-- Numeric input --}}
                                                                            <input type="number" class="form-control"
                                                                                name="{{ $meta->column_name }}"
                                                                                id="{{ $meta->column_name }}"
                                                                                value="{{ old($meta->column_name, $documentData->{$meta->column_name} ?? '') }}"
                                                                                required>
                                                                        @break

                                                                        @case(3)
                                                                            {{-- File input for images --}}
                                                                            @if ($documentData->{$meta->column_name})
                                                                                <a href="{{ asset($documentData->{$meta->column_name}) }}"
                                                                                    target="_blank"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                            @else
                                                                                <i class="fa fa-eye-slash"></i>
                                                                            @endif
                                                                            <input type="file" class="form-control"
                                                                                name="{{ $meta->column_name }}[]"
                                                                                id="{{ $meta->column_name }}" accept="image/*"
                                                                                multiple>
                                                                        @break

                                                                        @case(4)
                                                                            {{-- File input for images --}}
                                                                            @if ($documentData->{$meta->column_name})
                                                                                <a href="{{ asset($documentData->{$meta->column_name}) }}"
                                                                                    target="_blank"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                            @else
                                                                                <i class="fa fa-eye-slash"></i>
                                                                            @endif
                                                                            <input type="file" class="form-control"
                                                                                name="{{ $meta->column_name }}[]"
                                                                                id="{{ $meta->column_name }}" accept=".pdf"
                                                                                multiple>
                                                                        @break

                                                                        @case(5)
                                                                            {{-- File input for images --}}
                                                                            <input type="date" class="form-control"
                                                                                name="{{ $meta->column_name }}"
                                                                                id="{{ $meta->column_name }}"
                                                                                value="{{ old($meta->column_name, $documentData->{$meta->column_name} ?? '') }}"
                                                                                required>
                                                                                @break
                                                                        @case(6)
                                                                            {{-- File input for images --}}
                                                                            @if ($documentData->{$meta->column_name})
                                                                                <a href="{{ asset($documentData->{$meta->column_name}) }}"
                                                                                    target="_blank"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                            @else
                                                                                <i class="fa fa-eye-slash"></i>
                                                                            @endif
                                                                            <input type="file" class="form-control"
                                                                                name="{{ $meta->column_name }}[]"
                                                                                id="{{ $meta->column_name }}" accept="video/*"
                                                                                multiple>
                                                                        @break
                                                                    @endswitch
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach



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
    </div>

    @include('layouts.footer')


</x-app-layout>



{{-- ... --}}

{{-- ... --}}
