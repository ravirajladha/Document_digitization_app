<x-app-layout>


    @include('layouts.header')
    @include('layouts.sidebar')

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="row page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">View Document</a></li>
                </ol>
            </div>


            <form action="{{ url('/') }}/view_doc_first_submit" method="get"
                enctype="multipart/form-data">
                @csrf
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card profile-card card-bx m-b30">
                                <div class="card-header">
                                    <h6 class="title">Select Document Type</h6>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Document type</label>

                                                <select class="default-select form-control wide"
                                                    aria-label="Default select example" name="type">
                                                    <option selected disabled>select</option>
                                                    @foreach ($doc_type as $item)
                                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-2 my-auto">
                                        <div class="text-end"><button class="btn btn-secondary"
                                                type="submit">Next</button>
                                        </div>
                                    </div> --}}
                                        <div class="card-footer">
                                            <a href="" class="btn-link"></a>

                                            <button class="btn btn-secondary" type="submit">Next</button>
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


    @include('layouts.footer')


</x-app-layout>
