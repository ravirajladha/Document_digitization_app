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
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Add Document</a></li>
            </ol>
        </div>
        </div>

    </div>
    <form action="{{ url('/')}}/add_document" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                        <h3>Document Type: {{$table_name}}</h3>
                            <div class="form theme-form projectcreate">
                                <div class="row">
                                    <input type="hidden" value="{{$table_name}}" name="type">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Document Name</label>
                                            <input type="text" class="form-control" name="document_name"
                                                id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                        </div>
                                    </div>
                                    @foreach ($columns as $column)
                                    @if (!($column == 'id' || $column == 'created_at' || $column == 'updated_at' || $column == 'status' || $column == 'document_name' || $column == 'doc_type'))
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label">{{$column}}</label>
                                            @if ($document->$column == 1)
                                            <input type="text" class="form-control" name="{{$column}}"
                                                id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                            @elseif($document->$column == 2)
                                            <input type="number" class="form-control" name="{{$column}}"
                                            id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                            @elseif($document->$column == 3)
                                            <input type="file" class="form-control" name="{{$column}}[]"
                                            id="exampleInputEmail1" aria-describedby="emailHelp" accept="image/*" required multiple>
                                            @elseif($document->$column == 4)
                                            <input type="file" class="form-control" name="{{$column}}[]"
                                            id="exampleInputEmail1" aria-describedby="emailHelp" accept=".pdf" required multiple >
                                            @elseif($document->$column == 5)
                                            <input type="date" class="form-control" name="{{$column}}"
                                            id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                            @elseif($document->$column == 6)
                                            <input type="file" class="form-control" name="{{$column}}[]"
                                            id="exampleInputEmail1" aria-describedby="emailHelp" accept="video/*" required multiple>
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
        </div>
    </form>
</div>
</div>
</div>

@include('layouts.footer')


</x-app-layout>
