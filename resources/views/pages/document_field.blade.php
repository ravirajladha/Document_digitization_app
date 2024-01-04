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
                        Add Fields</h3>
                </div>
                <div class="col-12 col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Add Fields</a></li>
            </ol>
        </div>
        </div>

    </div>
    </div>
    <form action="{{ url('/')}}/add_document_field" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                        <h3>Document Type: {{$tableName}}</h3>
                            <div class="form theme-form projectcreate">
                                <div class="row">
                                  
                                    <input type="hidden" class="form-control" name="type"
                                                value="{{$tableName}}">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Fields</label>
                                           
                                            <input type="text" class="form-control" name="fields[]"
                                                id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter DocumentType" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Field Type</label>
                                            <select class="form-select form-control" aria-label="Default select example"
                                                name="field_type">
                                                <option selected disabled>--Select Any--</option>
                                                <option value="1">Text</option>
                                                <option value="2">Number</option>
                                                <option value="3">Image</option>
                                                <option value="4">Pdf Files</option>
                                                <option value="5">Date</option>
                                                <option value="6">Video</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class=" my-auto">
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table m-2">
                            <thead>
                                <tr>
                                    <th scope="col">Sl No.</th>
                                    <th scope="col">Field name</th>
                                    <th scope="col">Field type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($columnDetails as $index => $column)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $column->column_name }}</td>
                                        <td>
                                            @switch($column->data_type)
                                                @case(1)
                                                    Text
                                                    @break
                                                @case(2)
                                                    Number
                                                    @break
                                                @case(3)
                                                    Image
                                                    @break
                                                @case(4)
                                                    Pdf Files
                                                    @break
                                                @case(5)
                                                    Date
                                                    @break
                                                @case(6)
                                                    Video
                                                    @break
                                                @default
                                                    Unknown
                                            @endswitch
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
</div>
</div>
</div>

@include('layouts.footer')


</x-app-layout>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{ url('/')}}/assets_tags/jquery-tags-input.js"></script>
<script src="{{ url('/')}}/assets_tags/jquery-tags-input-init.js"></script>

<script>
    
</script>