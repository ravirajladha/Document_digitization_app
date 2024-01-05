<x-app-layout>
 

    <x-header/>
    @include('layouts.sidebar')

<div class="content-body default-height">
    <!-- row -->
    <div class="container-fluid">
        <div class="page-body">
            <div class="row page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Document Type</a></li>
                </ol>
            </div>

            <form action="{{ url('/')}}/add_document_type" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card profile-card card-bx m-b30">
                                <div class="card-header">
                                    <h6 class="title">Add Document Type</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form theme-form projectcreate">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Document Type</label>
                                                    <input type="text" class="form-control" name="type"
                                                        id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Document Type">
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a href="" class="btn-link"></a>
    
                                                <button class="btn btn-secondary" type="submit">Submit</button>
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
                                <div class="table-responsive">
                                    <table id="example5" class="display" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th scope="col">Sl no</th>
                                            <th scope="col">Document type</th>
                                            <th scope="col">Number of Document </th>
                                        
                                            <th scope="col">Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($doc_types as $index => $item)
                                            <tr>
                                                <th scope="row">{{ $index +1}}</th>
                                                <td>{{ $item->name }}</td> 
                                                 <td>{{ isset($doc_counts[$item->id]) ? $doc_counts[$item->id] : 0 }}</td>
                                                 <td>
                                                    <a href="/document_field/{{ $item->name  }}"><button class="btn btn-success">Add Field</button></a>
                                                    <a href="/view_doc/{{ $item->name  }}"><button class="btn btn-primary">View</button></a></td>
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

@include('layouts.footer')


</x-app-layout>