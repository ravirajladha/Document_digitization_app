<x-app-layout>
 

    <x-header/>
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
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Basic Fields Detail</a></li>
                </ol>
            </div>
    </div>
    
        <div class="container-fluid">
            <div class="row">
               
                    <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Basic Document Form</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form action="{{ url('/')}}/bulk-upload-master-document-data" method="post" enctype="multipart/form-data">
@csrf
                                    <div class="row">
                                        
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">Bulk Uplaod (in csv file format)</label>
                                            <div class="fallback">
                                                <input name="document" type="file"  class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Next</button>
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
</div>

@include('layouts.footer')


</x-app-layout>

