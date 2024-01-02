<x-app-layout>
 

    @include('layouts.header')
    @include('layouts.sidebar')

<div class="content-body default-height">
    <!-- row -->
    <div class="container-fluid">

<div class="page-body">
    <div class="container-fluid">
        <div class="page-body">
            <div class="row page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Add Fields</a></li>
                </ol>
            </div>
    </div>
    <form action="{{ url('/')}}/document_field" method="get" enctype="multipart/form-data">
        {{-- @csrf --}}
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form theme-form projectcreate">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">Document Type</label>
                                            <select class="form-select form-control" aria-label="Default select example"
                                                name="type">
                                                <option selected disabled>select</option>
                                                @foreach ($doc_type as $item)
                                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 my-auto">
                                        <div class="text-end"><button class="btn btn-secondary"
                                                type="submit">Next</button>
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
