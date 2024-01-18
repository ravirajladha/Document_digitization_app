<x-app-layout>


    <x-header />
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
                                <li class="breadcrumb-item active"><a href="javascript:void(0)">Bulk Upload Master
                                        Data</a>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Bulk Upload Detailed File</h4>
                                        {{-- <a href="/sample.csv" download="sample.csv">
                                            <button type="button" class="btn btn-dark btn-sm float-end">
                                                <i class="fas fa-download"></i>&nbsp; Download Sample CSV File
                                            </button>
                                        </a> --}}

                                    </div>
                                    <div class="card-body">
                                        <div class="basic-form">
                                            <form action="{{ url('/') }}/bulk-upload-child-document-data"
                                                method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">

                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">Bulk Upload (in csv file
                                                            format)</label>
                                                        <div class="fallback">
                                                            <input name="document" type="file" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <button type="submit" class="btn btn-primary">Submit</button>  </div> --}}

                                                <div class="card-footer">
                                                    <a href="" class="btn-link"></a>
                                                    <div class="text-end"> <button type="submit"
                                                            class="btn btn-success">Submit</button> </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>


                        <div class="row">

                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Bulk Upload Master File</h4>
                                        <a href="/assets/sample/sample.csv" download="sample.csv">
                                            <button type="button" class="btn btn-dark btn-sm float-end">
                                                <i class="fas fa-download"></i>&nbsp; Download Sample CSV File
                                            </button>
                                        </a>

                                    </div>
                                    <div class="card-body">
                                        <div class="basic-form">
                                            <form action="{{ url('/') }}/bulk-upload-master-document-data"
                                                method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">

                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">Bulk Upload (in csv file
                                                            format)</label>
                                                        <div class="fallback">
                                                            <input name="document" type="file" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <button type="submit" class="btn btn-primary">Submit</button>  </div> --}}

                                                <div class="card-footer">
                                                    <a href="" class="btn-link"></a>
                                                    <div class="text-end"> <button type="submit"
                                                            class="btn btn-success">Submit</button> </div>
                                                </div>
                                            </form>

                                        </div>
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
