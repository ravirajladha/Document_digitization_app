<x-app-layout>


    <x-header/>
    @include('layouts.sidebar')

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="row page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Document</a></li>
                </ol>
            </div>

            <form action="{{ url('/') }}/update_document" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Update Document</h4>
                                    <button type="button" class="btn btn-dark mb-2  me-2" id="toastr-success-top-center">Top
                                        Center</button>
               

                                </div>
                                <div class="card-body">
                                    <div class="basic-form">
                                        <div class="row">
                                            <input type="hidden" value="{{ $table_name }}" name="type">
                                            <input type="hidden" value="{{ $id }}" name="id">
                                            @foreach ($columns as $column)
                                                @if (!($column == 'id' || $column == 'created_at' || $column == 'updated_at' || $column == 'status'))
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            @if ($field_types->$column == 1)
                                                                <label for=""
                                                                    class="form-label">{{ $column }}</label>
                                                                <input type="text" class="form-control"
                                                                    name="{{ $column }}"
                                                                    value="{{ $document->$column }}" id=""
                                                                    aria-describedby="emailHelp" required>
                                                            @elseif($field_types->$column == 2)
                                                                <label for=""
                                                                    class="form-label">{{ $column }}</label>
                                                                <input type="number" class="form-control"
                                                                    name="{{ $column }}"
                                                                    value="{{ $document->$column }}" id=""
                                                                    aria-describedby="emailHelp" required>
                                                            @elseif($field_types->$column == 3)
                                                                <label for=""
                                                                    class="form-label">{{ $column }}
                                                                    @if ($document->$column)
                                                                        @php
                                                                            $file_paths = explode(',', $document->$column);
                                                                        @endphp
                                                                        @foreach ($file_paths as $path)
                                                                        <label class="form-label mb-0 me-2">
                                                                            <i class="fa-solid fa-eye"></i>
                                                                            Visible:
                                                                        </label>
                                                                        <span class="font-w500">Public</span>
                                                                        <a href="{{ url('/') }}/{{ $path }}" class="badge badge-primary light ms-3"    target="_blank"  role="button">View</a>
                                                                        @endforeach
                                                                    @endif
                                                                </label>
                                                                <input type="file" class="form-control"
                                                                    name="{{ $column }}[]"
                                                                    value="{{ $document->$column }}" id=""
                                                                    aria-describedby="emailHelp" accept="image/*"
                                                                    multiple>
                                                            @elseif($field_types->$column == 4)
                                                                <label for=""
                                                                    class="form-label">{{ $column }}
                                                                    @if ($document->$column)
                                                                        @php
                                                                            $file_paths = explode(',', $document->$column);
                                                                        @endphp
                                                                        @foreach ($file_paths as $path)
                                                                         

                                                                                <label class="form-label mb-0 me-2">
                                                                                    <i class="fa-solid fa-eye"></i>
                                                                                    Visible:
                                                                                </label>
                                                                                <span class="font-w500">Public</span>
                                                                                <a href="{{ url('/') }}/{{ $path }}" class="badge badge-primary light ms-3"    target="_blank"  role="button">View</a>

                                                                        @endforeach
                                                                    @endif
                                                                </label>
                                                                <input type="file" class="form-control"
                                                                    name="{{ $column }}[]"
                                                                    value="{{ $document->$column }}" id=""
                                                                    aria-describedby="emailHelp" accept=".pdf"
                                                                    multiple>
                                                            @elseif($field_types->$column == 5)
                                                                <label for=""
                                                                    class="form-label">{{ $column }}</label>
                                                                <input type="date" class="form-control"
                                                                    name="{{ $column }}"
                                                                    value="{{ $document->$column }}" id=""
                                                                    aria-describedby="emailHelp" required>
                                                            @elseif($field_types->$column == 6)
                                                                <label for=""
                                                                    class="form-label">{{ $column }}
                                                                    @if ($document->$column)
                                                                        @php
                                                                            $file_paths = explode(',', $document->$column);
                                                                        @endphp
                                                                        @foreach ($file_paths as $path)
                                                                          

                                                                                <label class="form-label mb-0 me-2">
                                                                                    <i class="fa-solid fa-eye"></i>
                                                                                    Visible:
                                                                                </label>
                                                                                <span class="font-w500">Public</span>
                                                                                <a href="{{ url('/') }}/{{ $path }}" class="badge badge-primary light ms-3"    target="_blank"  role="button">View</a>


                                                                        @endforeach
                                                                    @endif
                                                                </label>
                                                                <input type="file" class="form-control"
                                                                    name="{{ $column }}[]" id=""
                                                                    aria-describedby="emailHelp" accept="video/*"
                                                                    required multiple>
                                                            @endif
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
            </form>
        </div>

    </div>
    </div>

    @include('layouts.footer')


</x-app-layout>
