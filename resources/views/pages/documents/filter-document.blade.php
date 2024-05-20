<x-app-layout pageTitle="Your Page Title">

    <x-header />

    <x-sidebar />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider/distribute/nouislider.min.css">

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            {{-- $tableName --}}
            <div class="row page-titles">

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
                            <h4>
                                Search Document
                            </h4>
                            <div class="tools">
                                <a href="javascript:void(0);" class="expand handle"><i
                                        class="fal fa-angle-down"></i></a>
                            </div>
                        </div>
                        <div class="cm-content-body  form excerpt">
                            <div class="card-body">
                                <form action="{{ url('/') }}/filter-document" method="GET">
                                    @csrf
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Select Category </label>
                                            <select id="single-select-abc1" class="form-select form-control" style="width:100%;" name="category">
                                                <option value="" selected>Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $category)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Select Document Type </label>
                                            <select id="single-select-abc2" class="form-select form-control"
                                                style="width:100%;" name="type">
                                                <option value="" selected>Select Document Type</option>
                                                @foreach ($doc_type as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('type') == $item->id ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $item->name)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Select State </label>
                                            <select class="form-select form-control" id="single-select-abc3"
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

                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Select District </label>
                                            <select class="form-select form-control" id="single-select-abc4"
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

                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Select Village </label>

                                            <select class="form-select form-control" id="single-select-abc5"
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
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Document Date (Start)</label>
                                            <div class="input-hasicon">
                                                <input name="start_date" type="date" class="form-control  solid"
                                                    value="{{ old('start_date') }}">
                                                <div class="icon"><i class="far fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Document Date (End)</label>
                                            <div class="input-hasicon">
                                                <input name="end_date" type="date" class="form-control  solid"
                                                    value="{{ old('end_date') }}">
                                                <div class="icon"><i class="far fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Locker Number</label>
                                            <input type="number" name="locker_no" class="form-control"
                                                placeholder="Enter Locker Number" value="{{ old('locker_no') }}">
                                        </div>
                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Minimum Area Size</label>
                                            <input name="area_range_start" class="form-control"
                                                placeholder="Enter Minimum Area Size" type="number"
                                                value="{{ old('area_range_start') }}">
                                        </div>
                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Maximum Area Size</label>
                                            <input name="area_range_end" class="form-control"
                                                placeholder="Enter Maximum Area Size" type="number"
                                                value="{{ old('area_range_end') }}">
                                        </div>
                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Select Area Unit (Optional)</label>
                                            <select class="form-control" id="area-unit-dropdown" name="area_unit">
                                                <option value="">Select Unit</option>
                                                <option value="Acres"
                                                    {{ request()->input('area_unit') == 'Acres' ? 'selected' : '' }}>
                                                    Acres and Cents</option>
                                                <option value="Square Feet"
                                                    {{ request()->input('area_unit') == 'Square Feet' ? 'selected' : '' }}>
                                                    Square Feet</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Court Case</label>
                                            {{-- <input name="court_case_no" class="form-control"
                                                placeholder="Enter Court Case Details" type="text"
                                                value="{{ old('court_case_no') }}"> --}}
                                                <select id="single-select-abc8" class="form-select form-control" style="width:100%;" name="court_case_no">
                                                    <option value="" selected>Select Court Case </option>
                                                    @foreach ($courtCaseNos as $court_case_no)
                                                        <option value="{{ $court_case_no }}" {{ old('court_case_no') == $court_case_no ? 'selected' : '' }}>
                                                            {{ ucwords(str_replace('_', ' ', $court_case_no)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Document No</label>
                                            {{-- <input name="doc_no" class="form-control"
                                                placeholder="Enter Document Number" type="number"
                                                value="{{ old('doc_no') }}"> --}}

                                                <select id="single-select-abc6" class="form-select form-control" style="width:100%;" name="doc_no">
                                                    <option value="" selected>Select Document No</option>
                                                    @foreach ($doc_nos as $doc_no)
                                                        <option value="{{ $doc_no }}" {{ old('doc_no') == $doc_no ? 'selected' : '' }}>
                                                            {{ ucwords(str_replace('_', ' ', $doc_no)) }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                        </div>
                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Survey No</label>
                                            {{-- <input name="survey_no" class="form-control"
                                                placeholder="Enter Survey Number" type="number"
                                                value="{{ old('survey_no') }}"> --}}

                                                <select id="single-select-abc7" class="form-select form-control" style="width:100%;" name="survey_no">
                                                    <option value="" selected>Select Survey No</option>
                                                    @foreach ($survey_nos as $survey_no)
                                                        <option value="{{ $survey_no }}" {{ old('survey_no') == $survey_no ? 'selected' : '' }}>
                                                            {{ ucwords(str_replace('_', ' ', $survey_no)) }}
                                                        </option>
                                                    @endforeach
                                                </select>


                                        </div>
                                       
                                       
                                    </div>




                                    <div class="card-footer">

                                        <div class="text-end">
                                            {{-- <a href="{{ url('/') }}/filter-document" class="btn-link"><button class="btn btn-dark"><i
                                                class="fas fa-filter"></i>&nbsp;Reset Filter</button></a> --}}
                                            <a href="{{ url('/') }}/filter-document"
                                                class="btn btn-dark">Reset</a>

                                            <button class="btn btn-secondary" type="submit"><i
                                                    class="fas fa-filter"></i>&nbsp;Filter</button>
                                        </div>
                                    </div>


                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Document</h4>
                            <button id="exportButton" class="btn btn-primary float-end"><i
                                    class="fas fa-file-export"></i>&nbsp;Export</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">

                                <table id="example3" class="display table-hover" style="min-width: 845px;font-size: 12px;">
                                    <thead>
                                        <tr>
                                            {{-- <th>
                                                    <div class="custom-control d-inline custom-checkbox ms-2">
                                                        <input type="checkbox" class="form-check-input" id="checkAll"
                                                            required="">
                                                        <label class="form-check-label" for="checkAll"></label>
                                                    </div>
                                                </th> --}}
                                            <th scope="col">Sl. No.</th>
                                            <th scope="col">Document Name</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Document Type</th>
                                            <th scope="col">Village</th>
                                            <th scope="col">District</th>
                                            <th scope="col">Area</th>
                                         

                                            <th scope="col">Status</th>
                                            @if ($user && $user->hasPermission('Main Document View '))
                                                <th scope="col">Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($documents as $index => $item)
                                            <tr>

                                                <th scope="row">{{ $index + 1 }}</th>
                                                <td scope="row">{{ $item->name }}</td>
                                                <td scope="row">{{ $item->category }}</td>
                                                <td scope="row">
                                                    {{ ucWords(str_replace('_', ' ', $item->document_type_name)) }}
                                                </td>

                                                <td>{{ $item->current_village ? $item->current_village : '--' }}
                                                </td>
                                                <td>{{ $item->current_district ? $item->current_district : '--' }}
                                                </td>
                                                <td>{{ $item->area ? $item->area : '--' }}
                                                    {{-- ({{ $item->unit ? ($item->unit === 'acres and cents' ? 'A&C' : 'SqFt') : '--' }}) --}}
                                                    {{ $item->unit }}
                                                </td>
                                           
                                                <td>
                                                    @php
                                                        $statusClasses = [
                                                            '0' => 'badge-danger text-danger',
                                                            '1' => 'badge-success text-success',
                                                            '2' => 'badge-warning text-warning',
                                                            '3' => 'badge-warning text-dark',
                                                        ];
                                                        $statusTexts = [
                                                            '0' => 'Pending',
                                                            '1' => 'Accepted',
                                                            '2' => 'Hold',
                                                            '3' => 'Feedback',
                                                        ];
                                                        $statusId = strval($item->status_id); // Convert to string to match array keys
                                                        $statusClass =
                                                            $statusClasses[$statusId] ??
                                                            'badge-secondary text-secondary'; // Default class if key doesn't exist
$statusText = $statusTexts[$statusId] ?? 'Unknown'; // Default text if key doesn't exist
                                                    @endphp

                                                    <span class="badge light {{ $statusClass }}">
                                                        <i class="fa fa-circle {{ $statusClass }} me-1"></i>
                                                        {{ $statusText }}
                                                    </span>
                                                </td>
                                                @if ($user && $user->hasPermission('Main Document View '))
                                                    <td>


                                                        @if ($item->status_id == 1)
                                                            <a href="{{ url('/') }}/review_doc/{{ $item->document_type_name }}/{{ $item->tableId }}"
                                                                style="padding: 0.25rem 0.5rem; font-size: 0.65rem;"
                                                                class="btn btn-primary">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                        @else
                                                            <a href="{{ url('/') }}/review_doc/{{ $item->document_type_name }}/{{ $item->tableId }}"
                                                                style="padding: 0.25rem 0.5rem; font-size: 0.65rem;"
                                                                class="btn btn-secondary">
                                                                <i class="fas fa-list-check"></i> Review
                                                            </a>
                                                        @endif

                                                        </a>
                                                    </td>
                                                @endif

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
    $("#single-select-abc5").select2();

    $(".single-select-abc5-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc6").select2();

    $(".single-select-abc6-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc7").select2();

    $(".single-select-abc7-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc8").select2();

    $(".single-select-abc8-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
</script>

{{-- <script src="/assets/vendor/nouislider/nouislider.min.js"></script>
<script src="/assets/js/plugins-init/nouislider-init.js"></script> --}}
<script>
    document.getElementById('exportButton').addEventListener('click', function() {
        var table = document.getElementById('example3'); // Your table ID
        var rows = table.querySelectorAll('tr');
        var csv = [];

        for (var i = 0; i < rows.length; i++) {
            var row = [],
                cols = rows[i].querySelectorAll('td, th');

            for (var j = 0; j < cols.length - 1; j++) {
                // Clean the text content from the cell and escape double quotes
                var data = cols[j].innerText.replace(/"/g, '""');
                data = '"' + data + '"';
                row.push(data);
            }
            csv.push(row.join(','));
        }

        downloadCSV(csv.join('\n'));
    });

    function downloadCSV(csv) {
        var csvFile;
        var downloadLink;

        // CSV file
        csvFile = new Blob([csv], {
            type: "text/csv"
        });

        // Download link
        downloadLink = document.createElement("a");

        // File name
        downloadLink.download = 'export.csv';

        // Create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);

        // Hide download link
        downloadLink.style.display = "none";

        // Add the link to DOM
        document.body.appendChild(downloadLink);

        // Click download link
        downloadLink.click();
    }
</script>
