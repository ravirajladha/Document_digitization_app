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
                                        <div class="mb-3 col-md-6 col-xl-6">
                                            <label class="form-label">Document Name</label>
                                            <input name="doc_name" class="form-control"
                                                placeholder="Enter Document Name" type="text"
                                                value="{{ old('doc_name') }}">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label"> State</label>
                                            <select class="form-select form-control" id="single-select-abctest3"
                                                name="state" aria-label="State select">
                                                <option value="" selected>Select State</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state }}"
                                                        {{ old('state') == $state ? 'selected' : '' }}>
                                                        {{ $state }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div id="loader-3" class="loader" style="display:none;"></div>
                                        </div>

                                        <div class="mb-3 col-md-4">
                                            <label class="form-label"> District</label>
                                            <select class="form-select form-control" id="single-select-abctest4"
                                                name="district" aria-label="District select">
                                                <option value="" selected>Select District</option>
                                            </select>
                                            <div id="loader-4" class="loader" style="display:none;"></div>
                                        </div>

                                        <div class="mb-3 col-md-4">
                                            <label class="form-label"> Village</label>
                                            <select class="form-select form-control" id="single-select-abctest5"
                                                name="village" aria-label="Village select">
                                                <option value="" selected>Select Village</option>
                                            </select>
                                            <div id="loader-5" class="loader" style="display:none;"></div>
                                        </div>


                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Document Date (Start)</label>
                                            <div class="input-hasicon">
                                                <input name="start_date" type="date" class="form-control  solid"
                                                    value="{{ old('start_date') }}">
                                                <div class="icon"><i class="far fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Document Date (End)</label>
                                            <div class="input-hasicon">
                                                <input name="end_date" type="date" class="form-control  solid"
                                                    value="{{ old('end_date') }}">
                                                <div class="icon"><i class="far fa-calendar"></i></div>
                                            </div>
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
                                            <select id="single-select-abc8" class="form-select form-control"
                                                style="width:100%;" name="court_case_no">
                                                <option value="" selected>Select Court Case </option>
                                                @foreach ($courtCaseNos as $court_case_no)
                                                    <option value="{{ $court_case_no }}"
                                                        {{ old('court_case_no') == $court_case_no ? 'selected' : '' }}>
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

                                            <select id="single-select-abc6" class="form-select form-control"
                                                style="width:100%;" name="doc_no">
                                                <option value="" selected>Select Document No</option>
                                                @foreach ($doc_nos as $doc_no)
                                                    <option value="{{ $doc_no }}"
                                                        {{ old('doc_no') == $doc_no ? 'selected' : '' }}>
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

                                            <select id="single-select-abc7" class="form-select form-control"
                                                style="width:100%;" name="survey_no">
                                                <option value="" selected>Select Survey No</option>
                                                @foreach ($survey_nos as $survey_no)
                                                    <option value="{{ $survey_no }}"
                                                        {{ old('survey_no') == $survey_no ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $survey_no)) }}
                                                    </option>
                                                @endforeach
                                            </select>


                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"> Categories </label>
                                            <select class="form-select form-control" id="category-select"
                                                name="categories[]" multiple>
                                                <option selected disabled>Select Categories</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ collect(old('categories'))->contains($category->id) ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"> Subcategories </label>
                                            <select class="form-select form-control" id="subcategory-select"
                                                name="subcategories[]" multiple>
                                                <!-- Subcategories will be populated dynamically -->
                                            </select>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"> Locker IDs </label>
                                            <select class="form-select form-control" id="single-select-abc9"
                                                name="locker_ids[]" multiple>
                                                <option selected disabled>Select Locker IDs</option>
                                                @foreach ($lockers as $locker)
                                                    <option value="{{ $locker }}"
                                                        {{ collect(old('locker_ids'))->contains($locker) ? 'selected' : '' }}>
                                                        {{ $locker }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"> Document Identifiers </label>
                                            <select class="form-select form-control" id="single-select-abc10"
                                                name="doc_identifiers[]" multiple>
                                                <option selected disabled>Select Document Identifiers</option>
                                                @foreach ($docIdentifiers as $docIdentifier)
                                                    <option value="{{ $docIdentifier }}"
                                                        {{ collect(old('doc_identifiers'))->contains($docIdentifier) ? 'selected' : '' }}>
                                                        {{ $docIdentifier }}
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
                                <div class="table-responsive">
                                    <table class="table table-striped table-responsive-sm" style="width:100%"
                                        id="filter-table" style="min-width: 845px;font-size: 12px;">
                                        {{-- <table id="example2" lass="display table-hover"  > --}}
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
                                    {{-- <div class="d-flex justify-content-center">
                                    {{ $documents->links() }}
                                </div> --}}
                                    <div class="row">
                                        <div class="col">
                                            {{ $documents->links('vendor.pagination.custom') }}
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
    $("#single-select-abc9").select2();

    $(".single-select-abc9-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc10").select2();

    $(".single-select-abc10-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    // $("#single-select-abctest3").select2();

    // $(".single-select-abctest3-placeholder").select2({
    //     placeholder: "Select a state",
    //     allowClear: true
    // });
</script>

{{-- <script src="/assets/vendor/nouislider/nouislider.min.js"></script>
<script src="/assets/js/plugins-init/nouislider-init.js"></script> --}}
<script>
    document.getElementById('exportButton').addEventListener('click', function() {
        var table = document.getElementById('filter-table'); // Your table ID
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
<style>
    .loader {
        border: 4px solid #f3f3f3;
        border-radius: 50%;
        border-top: 4px solid #3498db;
        width: 20px;
        height: 20px;
        animation: spin 2s linear infinite;
        display: inline-block;
        vertical-align: middle;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
<script>
    function resetDropdown(id) {
        const dropdown = document.getElementById(id);
        const text = id.replace('single-select-abctest', ''); // Extract number suffix
        const label = {
            '3': 'State',
            '4': 'District',
            '5': 'Village'
        } [text] || 'Select'; // Map number to label, default to 'Select'

        dropdown.innerHTML = `<option value="" selected>Select ${label}</option>`;
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    document.getElementById('single-select-abctest3').addEventListener('change', function() {
        updateSelections('state', this.value);
    });

    document.getElementById('single-select-abctest4').addEventListener('change', function() {
        updateSelections('district', this.value);
    });

    function updateSelections(type, value) {
        if (type === 'state') {
            resetDropdown('single-select-abctest4');
            resetDropdown('single-select-abctest5');
        } else if (type === 'district') {
            resetDropdown('single-select-abctest5');
        }

        let url = '';
        switch (type) {
            case 'state':
                url = `/api/fetch/districts/${value}`;
                fetchDropdownData(url, 'single-select-abctest4');
                targetId = 'single-select-abctest4';
                break;
            case 'district':
                url = `/api/fetch/villages/${value}`;
                fetchDropdownData(url, 'single-select-abctest5');
                targetId = 'single-select-abctest5';
                break;
            default:
                console.error('Unhandled selection type:', type);
                return;
        }
        showLoader(targetId);
        fetchDropdownData(url, targetId);
    }

    function fetchDropdownData(url, targetId, selectedValue = '') {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const dropdown = document.getElementById(targetId);
                const loader = document.getElementById('loader-' + targetId.replace('single-select-abctest', ''));
                console.log("data", data);
                resetDropdown(targetId); // Reset with correct default text
                if (Array.isArray(data)) {
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item;
                        option.textContent = item;
                        if (item === selectedValue) {
                            option.selected = true;
                        }
                        dropdown.appendChild(option);
                    });
                } else {
                    console.error('Data format error:', data);
                }
                hideLoader(targetId);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                hideLoader(targetId);
            });
    }

    function showLoader(targetId) {
        const loaderId = 'loader-' + targetId.replace('single-select-abctest', '');
        const loader = document.getElementById(loaderId);
        if (loader) {
            loader.style.display = 'inline-block';
        }
    }

    function hideLoader(targetId) {
        const loaderId = 'loader-' + targetId.replace('single-select-abctest', '');
        const loader = document.getElementById(loaderId);
        if (loader) {
            loader.style.display = 'none';
        }
    }
    // Initialize dropdowns based on previous selections
    document.addEventListener('DOMContentLoaded', function() {
        const selectedState = document.getElementById('single-select-abctest3').value;
        const selectedDistrict = document.getElementById('single-select-abctest4').value;

        if (selectedState) {
            updateSelections('state', selectedState);
        }

        if (selectedState && selectedDistrict) {
            setTimeout(() => {
                updateSelections('district', selectedDistrict);
            }, 500); // Adjust timeout as necessary to ensure state dropdown is populated first
        }
    });
</script>
<script>
    $(document).ready(function() {
        // Initialize select2
        $("#category-select").select2({
            placeholder: "Select Categories",
            allowClear: true
        });
        $("#subcategory-select").select2({
            placeholder: "Select Subcategories",
            allowClear: true
        });

        // Populate subcategories on page load based on pre-selected categories
        populateSubcategories();

        $('#category-select').on('change', function() {
            populateSubcategories();
        });

        function populateSubcategories() {
            var selectedCategories = $('#category-select').val();
            var subcategories = [];

            // Collect selected subcategories to retain selection
            var previouslySelectedSubcategories = {!! json_encode(old('subcategories', [])) !!};

            @foreach ($categories as $category)
                if (selectedCategories && selectedCategories.includes('{{ $category->id }}')) {
                    @foreach ($category->subcategories as $subcategory)
                        subcategories.push({
                            id: '{{ $subcategory->id }}',
                            name: '{{ $subcategory->name }}',
                            category: '{{ $category->name }}'
                        });
                    @endforeach
                }
            @endforeach

            var subcategorySelect = $('#subcategory-select');
            subcategorySelect.empty();

            if (subcategories.length > 0) {
                subcategories.forEach(function(subcategory) {
                    var isSelected = previouslySelectedSubcategories.includes(subcategory.id
                    .toString()) ? 'selected' : '';
                    subcategorySelect.append('<option value="' + subcategory.id + '" ' + isSelected +
                        '>' + subcategory.category + ' - ' + subcategory.name + '</option>');
                });
            } else {
                subcategorySelect.append('<option selected disabled>No Subcategories Available</option>');
            }

            // Reinitialize select2 for subcategory select
            $('#subcategory-select').select2({
                placeholder: "Select Subcategories",
                allowClear: true
            });
            //     $("#subcategory-select").select2({
            //     placeholder: "Select Subcategories",
            //     allowClear: true
            // });
        }
    });
</script>
