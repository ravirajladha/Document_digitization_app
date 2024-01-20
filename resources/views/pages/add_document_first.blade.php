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
    <form action="{{ url('/')}}/add-document-data" method="post" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid">
            <div class="row">
               
                    <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Basic Document Form</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                              

                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Select Document Type <span
                                                class="text-danger">*</span></label>
                                            <select class="form-select form-control" id="single-select-abc1" aria-label="Default select example"
                                            name="type" required>
                                            <option selected disabled>Select Document Type</option>
                                            @foreach ($doc_type as $item)
                                                <option value="{{ $item->id }}|{{ $item->name }}">{{ ucWords(str_replace('_', ' ', $item->name)) }}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" placeholder="Enter Name">
                                        </div>
                                        {{-- <div class="mb-3 col-md-6"> --}}
                                            {{-- <label class="form-label">Temp Id</label> --}}
                                            <input type="text" name="temp_id"  hidden class="form-control" placeholder="Enter temp id">
                                        {{-- </div> --}}
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Document Present At</label>
                                            <input type="text" name="location" class="form-control" placeholder="Enter Document Present At">
                                        </div>
                                        
                                   
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Number of Pages</label>
                                            <input type="number" name="number_of_page" class="form-control" placeholder="Enter Number of Pages">
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Current State</label>
                                            <select id="single-select-abc2" name="current_state"  class="default-select form-control ">
                                                <option selected disabled>Choose State...</option>
                                                @foreach($states as $state)
                                                <option value="{{ $state->name }}">{{ $state->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">State</label>
                                            <select id="single-select-abc3" name="state"  class="default-select form-control wide">
                                                <option selected disabled>Choose State...</option>
                                                @foreach($states as $state)
                                                <option value="{{ $state->name }}">{{ $state->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Alternate State</label>
                                            <select id="single-select-abc4" name="alternate_state" class="default-select form-control wide">
                                                <option selected disabled>Choose State...</option>
                                                @foreach($states as $state)
                                                <option value="{{ $state->name }}">{{ $state->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Current District</label>
                                            <input type="text" name="current_district" class="form-control" placeholder="Enter Current District">
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">District</label>
                                            <input type="text" name="district" class="form-control" placeholder="Enter District">
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Alternate District</label>
                                            <input type="text" name="alternate_district" class="form-control" placeholder="Enter Alternate District">
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Current Taluk</label>
                                            <input type="text" name="current_taluk" class="form-control" placeholder="Enter Current Taluk">
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Taluk</label>
                                            <input type="text" name="taluk" class="form-control" placeholder="Enter Taluk">
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Alternate Taluk</label>
                                            <input type="text" name="alternate_taluk"  class="form-control" placeholder="Enter Alternate Taluk">
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Current Village</label>
                                            <input type="text" name="current_village" class="form-control" placeholder="Enter Current Village">
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Village</label>
                                            <input type="text" name="village" class="form-control" placeholder="Enter Village">
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Alternate Village</label>
                                            <input type="text" name="alternate_village" class="form-control" placeholder="Enter Alternate Village">
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Issued Date</label>
                                            <input type="date" name="issued_date" class="form-control">
                                        </div>
                                        
                                        {{-- <div class="mb-3 col-md-6">
                                            <label class="form-label">Document Sub Type</label>
                                            <input type="text" name="document_sub_type" class="form-control" placeholder="Enter Document Sub Type">
                                        </div>
                                         --}}
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Current Town</label>
                                            <input type="text" name="current_town" class="form-control" placeholder="Enter Current Town">
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Town</label>
                                            <input type="text" name="town" class="form-control" placeholder="Enter Town">
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Alternate Town</label>
                                            <input type="text" name="alternate_town" class="form-control" placeholder="Enter Alternate Town">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Locker ID</label>
                                            <input type="number" name="locker_id"  class="form-control" placeholder="Enter Locker ID">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Old Locker Number</label>
                                            <input type="text" name="old_locker_number" class="form-control" placeholder="Enter Old Locker Number">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Physically Checked</label>
                                            <input type="text" name="physically" class="form-control" placeholder="Enter physically">
                                        </div>
                                        {{-- <div class="mb-3 col-md-6">
                                            <label class="form-label">Status Description</label>
                                            <input type="text" name="status_description" class="form-control" placeholder="Enter status_description ">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Review</label>
                                            <input type="text" name="review" class="form-control" placeholder="Enter review">
                                        </div> --}}
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Set</label>
                                            <select class="select2-width-75" name="set[]" multiple="multiple" style="width: 75%">
                                                <option selected disabled>Choose Set...</option>

                                                @foreach($sets as $set)
                                                <option value="{{ $set->id }}">{{ $set->name }}</option>
                                              @endforeach
                                            </select>
                                        </div>
                                        
                                   
                                        
                                    </div>
                                   
                                    {{-- <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox">
                                            <label class="form-check-label">
                                                Check me out
                                            </label>
                                        </div>
                                    </div> --}}

                                    <div class="card-footer">
                                        <a href="" class="btn-link"></a>
                                        <div class="text-end"><button class="btn btn-primary"
                                                type="submit">Next</button>
                                        </div>
                                    </div>


                                    {{-- <button type="submit" class="btn btn-primary">Next</button> --}}
                          
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

<script>
    $("#single-select-abc1").select2();

    $(".single-select-abc1-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc2").select2();

    $(".single-select-abc2-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc3").select2();

    $(".single-select-abc3-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc4").select2();

    $(".single-select-abc4-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
</script>