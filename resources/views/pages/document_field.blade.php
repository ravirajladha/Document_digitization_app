<x-app-layout>


    <x-header />
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
                                    Document Dynamic Fields</h3>
                            </div>
                            <div class="col-12 col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Document Dynamic Fields</a></li>
                                </ol>
                            </div>
                        </div>

                    </div>
                </div>
            
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="title">Fields Detail</h4>
                                    <button type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal"
                                    data-bs-target="#addDocumentTypeModal">
                                    <i class="fas fa-plus"></i>&nbsp; Add Document Fields
                                </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                      
                                        <table id="example3" class="display" style="min-width: 845px">
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
    </div>

     <!-- Modal -->
     <div class="modal fade" id="addDocumentTypeModal" tabindex="-1" aria-labelledby="addDocumentTypeModalLabel"
     aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="addDocumentTypeModalLabel">Add Fields to Document: {{ $tableName }} </h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <form action="{{ url('/') }}/add_document_field" method="POST" enctype="multipart/form-data">
                 @csrf
                 <input type="hidden" class="form-control" name="type"
                 value="{{ ucwords($tableName) }}">
                 <div class="modal-body">
                      <div class="mb-3">
                            <label for="documentType" class="form-label">Fields Name</label>
                            <input type="text" class="form-control" name="fields[]"
                            id="exampleInputEmail1" aria-describedby="emailHelp"
                            placeholder="Enter Field Name" required>
                        </div>
                      <div class="mb-3">
                            <label for="documentType" class="form-label">Field Type</label>
                            <select class="form-select form-control"
                            aria-label="Default select example" name="field_type">
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
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button class="btn btn-primary" type="submit">Submit</button>
                 </div>
             </form>
         </div>
     </div>
 </div>

    @include('layouts.footer')


</x-app-layout>



<script></script>
