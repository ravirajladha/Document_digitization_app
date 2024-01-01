<x-app-layout>
 

    @include('layouts.header')
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
                                <table class="table m-2">
                                    <thead>
                                        <tr>
                                            <th scope="col">Sl no</th>
                                            <th scope="col">Document type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($doc_types as $index => $item)
                                            <tr>
                                                <th scope="row">{{ $index +1}}</th>
                                                <td>{{ $item->type }}</td>
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