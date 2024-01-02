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
                                                    {{-- <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Document Name</label>
                                            <input type="text" class="form-control" name="document_name"
                                                id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                        </div>
                                    </div> --}}
                                                    {{-- {{ dd($document,$columns    , $document_data) }} --}}
                                                    {{-- {{ dd($columns) }} --}}
                                                    @foreach ($columns as $column)
                                                 
                                                    @if (!in_array($column, ['id', 'document_name', 'doc_id', 'created_at', 'updated_at', 'status', 'doc_type']))
                                                    {{-- {{ dd($column) }} --}}
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="{{ $column }}" class="form-label">{{ ucfirst(str_replace('_', ' ', $column)) }}</label>
                                                                {{-- {{ dd($document->$column) }} --}}
                                                                @switch($document->$column)
                                                              
                                                                    @case(1) {{-- Text input --}}
                                                                        <input type="text" class="form-control"
                                                                               name="{{ $column }}"
                                                                               id="{{ $column }}"
                                                                               value="{{ old($column, $document_data->$column ?? '') }}" required>
                                                                        @break
                                                                    @case(2) {{-- Numeric input --}}
                                                                        <input type="number" class="form-control"
                                                                               name="{{ $column }}"
                                                                               id="{{ $column }}"
                                                                               value="{{ old($column, $document_data->$column ?? '') }}" required>
                                                                        @break
                                                                    @case(3) {{-- File input for images --}}
                                                                        {{-- Here you might want to show the existing images --}}
                                                                          {{-- Display current image if available --}}
                                                                          @if ($document_data->$column)
                                                                          <a href="{{ asset($document_data->$column) }}" target="_blank"><i class="fa fa-eye"></i></a>
                                                                      @else
                                                                      <i class="fa fa-eye-slash"></i>
                                                                      @endif
                                                                        <input type="file" class="form-control"
                                                                               name="{{ $column }}[]"
                                                                               id="{{ $column }}"
                                                                               accept="image/*" multiple>
                                                                        @break
                                                                    @case(4) {{-- File input for PDFs --}}
                                                                        {{-- Here you might want to show the existing PDFs --}}
                                                                        @if ($document_data->$column)
                                                                        <a href="{{ asset($document_data->$column) }}" target="_blank"><i class="fa fa-eye"></i></a>
                                                                    @else
                                                                    <i class="fa fa-eye-slash"></i>

                                                                    @endif
                                                                        <input type="file" class="form-control"
                                                                               name="{{ $column }}[]"
                                                                               id="{{ $column }}"
                                                                               accept=".pdf" multiple>
                                                                        @break
                                                                    @case(5) {{-- Date input --}}
                                                                        <input type="date" class="form-control"
                                                                               name="{{ $column }}"
                                                                               id="{{ $column }}"
                                                                               value="{{ old($column, $document_data->$column ?? '') }}" required>
                                                                        @break
                                                                    @case(6) {{-- File input for videos --}}
                                                                        {{-- Here you might want to show the existing videos --}}
                                                                        @if ($document_data->$column)
                            <a href="{{ asset($document_data->$column) }}" target="_blank"><i class="fa fa-eye"></i></a>
                        @else
                        <i class="fa fa-eye-slash"></i>

                        @endif
                                                                        <input type="file" class="form-control"
                                                                               name="{{ $column }}[]"
                                                                               id="{{ $column }}"
                                                                               accept="video/*" multiple>
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

        @include('layouts.footer')


</x-app-layout>
