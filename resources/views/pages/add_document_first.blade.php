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
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Add Document</a></li>
        </ol>
    </div>
    <form action="{{url('/')}}/add-document-data" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                        
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">Document Type</label>
                                            <select class="form-select form-control" aria-label="Default select example"
                                                name="type">
                                                <option selected disabled>Select</option>
                                                @foreach ($doc_type as $item)
                                                    <option value="{{ $item->type }}">{{ $item->type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <a href="" class="btn-link"> </a>

                                        <button class="btn btn-secondary" type="submit">Submit</button>
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
