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
                                                    <label for="documentType" class="form-label">Document Type</label>
                                                    <select class="form-control" id="documentType" name="document_type" onchange="fetchDocuments(this.value)">
                                                        <option value="">Select Document Type</option>
                                                        @foreach($documentTypes as $type)
                                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="document" class="form-label">Document</label>
                                                    <select class="form-control" id="document" name="document_id">
                                                        <option value="">Select Document</option>
                                                        <!-- Options will be populated based on Document Type selection -->
                                                    </select>
                                                </div>
                                        
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="receiverType" class="form-label">Receiver Type</label>
                                                    <select class="form-control" id="receiverType" name="receiver_type" onchange="fetchReceivers(this.value)">
                                                        <option value="">Select Receiver Type</option>
                                                        @foreach($receiverTypes as $type)
                                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="receiver" class="form-label">Receiver</label>
                                                    <select class="form-control" id="receiver" name="receiver_id">
                                                        <option value="">Select Receiver</option>
                                                        <!-- Options will be populated based on Receiver Type selection -->
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a href="" class="btn-link"></a>
    
                                                <button class="btn btn-secondary" type="submit">Assign Document</button>
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
                                        {{-- @foreach ($doc_types as $index => $item)
                                            <tr>
                                                <th scope="row">{{ $index +1}}</th>
                                                <td>{{ $item->name }}</td> 
                                                 <td>{{ isset($doc_counts[$item->id]) ? $doc_counts[$item->id] : 0 }}</td>
                                                 <td>
                                                    <a href="/document_field/{{ $item->name  }}"><button class="btn btn-success">Add Field</button></a>
                                                    <a href="/view_doc/{{ $item->name  }}"><button class="btn btn-primary">View</button></a></td>
                                            </tr>
                                        @endforeach --}}
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
<script>
    function fetchDocuments(typeId) {
        // JavaScript to fetch and populate specific documents based on type
    }

    function fetchReceivers(typeId) {
        // JavaScript to fetch and populate specific receivers based on type
    }
</script>