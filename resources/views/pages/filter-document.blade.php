<x-app-layout>
    {{-- <link rel="stylesheet" href="/assets/vendor/nouislider/nouislider.min.css"> --}}
    <x-header />


    @include('layouts.sidebar')
  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider/distribute/nouislider.min.css">
    <style>
        .noUi-connect {
    background: #f2f5f1;
    height:50%;
}
.noUi-base, .noUi-connects  {
    background: #f5f3f1;
    height:50%;
}
.noUi-horizontal .noUi-handle {
    width: 20px;
    height: 20px;
    right: -17px;
    top: -2px;
}
    </style>
    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            {{-- $tableName --}}
            <div class="row page-titles">
                {{-- <span> <form action="{{ url('/') }}/add-document-data" method="POST" enctype="multipart/form-data" style="none">
                    @csrf
                    <input type="hidden" name="type" value="222">
                    <button class="btn btn-secondary" type="submit">Add Document</button>
                </form></span>  --}}
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0)">View Document</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">All Document</a></li>
                </ol>

            </div>
{{-- new card start --}}

<div class="row">
    <div class="col-xl-12">
        
                <div class="filter cm-content-box box-primary">
                    <div class="content-title SlideToolHeader">
                        <div class="cpa">
                            Search Document
                        </div>
                        <div class="tools">
                            <a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a>
                        </div>
                    </div>
                    <div class="cm-content-body  form excerpt">
                        <div class="card-body">
                            <form action="{{ url('/') }}/filter-document" method="GET">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Select Document Type </label>
                                        <select id="single-select-abc1" class="form-select form-control"
                                            style="width:100%;" name="type">
                                            <option value="" selected>Select Document Type</option>
                                            <option value="all" {{ old('type') == 'all' ? 'selected' : '' }}>
                                                Show All Documents</option>
                                            @foreach ($doc_type as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('type') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Select State </label>
                                  
                                        <select class="form-select form-control" id="single-select-abc2"
                                            name="state" aria-label="State select">
                                            <option value="" selected>Select State</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state }}"
                                                    {{ old('state') == $state ? 'selected' : '' }}>
                                                    {{ $state }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Select District </label>

                                        <select class="form-select form-control" id="single-select-abc3"
                                            name="district" aria-label="District select">
                                            <option value="" selected>Select District</option>
                                            @foreach ($districts as $district)
                                                <option value="{{ $district }}"
                                                    {{ old('district') == $district ? 'selected' : '' }}>
                                                    {{ $district }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Select Village </label>
                                
                                        <select class="form-select form-control" id="single-select-abc4"
                                            name="village" aria-label="Village select">
                                            <option value="" selected>Select Village</option>
                                            @foreach ($villages as $village)
                                                <option value="{{ $village }}"
                                                    {{ old('village') == $village ? 'selected' : '' }}>
                                                    {{ $village }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Locker No</label>
                                        <input type="number" name="locker_no" class="form-control"
                                            placeholder="Enter Locker Number" value="{{ old('locker_no') }}">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Old Locker No</label>
                                        <input type="number" name="old_locker_no" class="form-control"
                                            placeholder="Enter Old Locker Number"
                                            value="{{ old('old_locker_no') }}">
                                    </div>

                                    {{-- <div class="mb-3 col-md-6">
                                        <label for="pagesRange" class="form-label">Number of Pages (1 -
                                            100)</label>
                                        <input type="range" id="pagesRange" name="number_of_pages" min="1"
                                            max="100"
                                            value="{{ old('number_of_pages') !== null ? old('number_of_pages') : 'null' }}"
                                            oninput="if(this.value !== 'null') { this.nextElementSibling.value = this.value; } else { this.nextElementSibling.value = ''; }"
                                            class="form-range">
                                        <output>{{ old('number_of_pages') !== null ? old('number_of_pages') : 'null' }}</output>
                                    </div> --}}
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Document Date (Start)</label>
                                        <div class="input-hasicon">
                                            <input name="start_date" type="date"
                                                class="form-control bt-datepicker solid"
                                                value="{{ old('start_date') }}">
                                            <div class="icon"><i class="far fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Document Date (End)</label>
                                        <div class="input-hasicon">
                                            <input name="end_date" type="date"
                                                class="form-control bt-datepicker solid"
                                                value="{{ old('end_date') }}">
                                            <div class="icon"><i class="far fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    {{-- <div class="mb-3 col-md-6">
                                        <label class="form-label">Number of pages</label>
                                        <div id="page-range-slider" class="slider"></div>
                                        <input type="hidden" id="number-of-pages-start"
                                            name="number_of_pages_start"
                                            value="{{ old('number_of_pages_start') }}">
                                        <input type="hidden" id="number-of-pages-end" name="number_of_pages_end"
                                            value="{{ old('number_of_pages_end') }}">
                                        <div id="number-of-pages-values">
                                            <span id="number-of-pages-min">Start Page: </span>
                                            <span id="number-of-pages-max">End Page: </span>
                                        </div>

                                    </div> --}}
                                    <div class="mb-3 col-md-12 col-xl-12">
                                        <label class="form-label">Area Size (in acres)</label>

                                        <div id="area-range-slider" class="slider"></div>
                                        <input type="hidden" id="area-range-start" name="area_range_start"
                                            value="{{ old('area_range_start') }}">
                                        <input type="hidden" id="area-range-end" name="area_range_end"
                                            value="{{ old('area_range_end') }}">
                                        <div id="area-range-values">
                                            <span id="area-range-min">Start Area: </span>
                                            <span id="area-range-max">End Area: </span>
                                        </div>


                                        <label class="form-label">Select Area Unit (Optional)</label>
                                        <select class="form-control" id="area-unit-dropdown" name="area_unit">
                                            <option value="">Select Unit</option>
                                            <option value="1">Acres and Cents</option>
                                            <option value="2">Square Feet</option>
                                          
                                        </select>

                                    </div>
                                    <div class="card-footer">
                                        <a href="" class="btn-link"></a>
                                        <div class="text-end"><button class="btn btn-secondary"
                                                type="submit">Filter</button>
                                        </div>
                                    </div>


                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Document</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example3" class="display" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            {{-- <th>
                                                    <div class="custom-control d-inline custom-checkbox ms-2">
                                                        <input type="checkbox" class="form-check-input" id="checkAll"
                                                            required="">
                                                        <label class="form-check-label" for="checkAll"></label>
                                                    </div>
                                                </th> --}}
                                            <th scope="col">Sl no</th>
                                            <th scope="col">Document Name</th>
                                            <th scope="col">Document Type</th>
                                            <th scope="col">Village</th>
                                            <th scope="col">District</th>

                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($documents as $index => $item)
                                            <tr>
                                                {{-- <td>
                                                        <div class="form-check custom-checkbox ms-2">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="customCheckBox2" required="">
                                                            <label class="form-check-label"
                                                                for="customCheckBox2"></label>
                                                        </div>
                                                    </td> --}}
                                                <th scope="row">{{ $index + 1 }}</th>
                                                <td scope="row">{{ $item->name }}</td>
                                                <td scope="row">{{ $item->document_type_name }}</td>

                                                <td>{{ $item->current_village ? $item->current_village : '--' }}
                                                </td>
                                                <td>{{ $item->current_district ? $item->current_district : '--' }}
                                                </td>

                                                <td>
                                                    @php
                                                        $statusClasses = ['0' => 'badge-danger text-danger', '1' => 'badge-success text-success', '2' => 'badge-warning text-warning'];
                                                        $statusTexts = ['0' => 'Pending', '1' => 'Accepted', '2' => 'Hold'];
                                                        $statusId = strval($item->status_id); // Convert to string to match array keys
        $statusClass = $statusClasses[$statusId] ?? 'badge-secondary text-secondary'; // Default class if key doesn't exist
        $statusText = $statusTexts[$statusId] ?? 'Unknown'; // Default text if key doesn't exist
                                                    @endphp
                                                
                                                    <span class="badge light {{ $statusClass }}">
                                                        <i class="fa fa-circle {{ $statusClass }} me-1"></i>
                                                        {{ $statusText }}
                                                    </span>
                                                </td>
                                                
                                                <td>
                                                    <a href="{{ url('/') }}/review_doc/{{ $item->document_type_name }}/{{ $item->tableId }}"
                                                       type="button" class="btn btn-primary">
                                                        {{ $item->status_id == 1 ? 'View' : 'Review' }}
                                                    </a>
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
    {{-- <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div> --}}
    <script src="https://cdn.jsdelivr.net/npm/nouislider/distribute/nouislider.min.js"></script>

    {{-- <script src="/assets/vendor/nouislider/nouislider.min.js"></script>
<script src="/assets/vendor/wnumb/wNumb.js"></script>
<script src="/assets/js/plugins-init/nouislider-init.js"></script> --}}
    @include('layouts.footer')


</x-app-layout>
<script>
    // // Assuming you have an element with ID 'pagesRange' for the pages slider
    // var pagesSlider = document.getElementById('pagesRange');
    // var pagesOutput = pagesSlider.nextElementSibling; // The <output> element after the slider
    // pagesOutput.value = pagesSlider.value; // Display the default slider value

    // // Update the current slider value (each time you drag the slider handle)
    // pagesSlider.oninput = function() {
    //     pagesOutput.value = this.value;
    // }
</script>
<script>
    document.querySelector('form').onsubmit = function() {
        document.getElementById('preloader').style.display = 'block'; // Show loader
    };

    window.onload = function() {
        document.getElementById('preloader').style.display = 'none'; // Hide loader on page load
    };
</script>
<style>
    #loader {
        display: none;
        position: fixed;
        z-index: 999;
        height: 2em;
        width: 2em;
        overflow: visible;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }
</style>

<script>
    // Initialize the Number of Pages slider
    // var pagesSlider = document.getElementById('page-range-slider');
    // var pagesStartInput = document.getElementById('number-of-pages-start');
    // var pagesEndInput = document.getElementById('number-of-pages-end');
    // var pagesValues = [
    //     document.getElementById('number-of-pages-min'),
    //     document.getElementById('number-of-pages-max')
    // ];

    // noUiSlider.create(pagesSlider, {
    //     start: [{{ old('number_of_pages_start', 1) }}, {{ old('number_of_pages_end', 100) }}],
    //     connect: true,
    //     range: {
    //         'min': 1,
    //         'max': 100
    //     }
    // });

    // pagesSlider.noUiSlider.on('update', function(values, handle) {
    //     pagesValues[handle].innerHTML = handle ? "End Page: " + parseInt(values[handle]) : "Start Page: " +
    //         parseInt(values[handle]);
    //     handle ? pagesEndInput.value = parseInt(values[handle]) : pagesStartInput.value = parseInt(values[
    //         handle]);
    // });

    // Initialize the Area Range slider
    var areaSlider = document.getElementById('area-range-slider');
var areaStartInput = document.getElementById('area-range-start');
var areaEndInput = document.getElementById('area-range-end');
var areaValues = [
    document.getElementById('area-range-min'),
    document.getElementById('area-range-max')
];

noUiSlider.create(areaSlider, {
    start: [{{ old('area_range_start', 0.1) }}, {{ old('area_range_end', 100) }}],
    connect: true,
    range: {
        'min': 0.1,
        'max': 100
    },
    step: 0.1, // Add a step property for increments of 0.1
    format: {
        // Custom formatting can be added for decimal handling
        to: function(value) {
            return value.toFixed(1); // Keep one decimal
        },
        from: function(value) {
            return Number(value);
        }
    }
});

areaSlider.noUiSlider.on('update', function(values, handle) {
    areaValues[handle].innerHTML = handle ? "End Area: " + values[handle] : "Start Area: " + values[handle];
    handle ? areaEndInput.value = values[handle] : areaStartInput.value = values[handle];
});
</script>

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

<script src="/assets/vendor/nouislider/nouislider.min.js"></script>
<script src="/assets/js/plugins-init/nouislider-init.js"></script>

