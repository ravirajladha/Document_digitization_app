<x-app-layout>
    {{-- <link rel="stylesheet" href="/assets/vendor/nouislider/nouislider.min.css"> --}}
    <x-header />


    <x-sidebar/>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider/distribute/nouislider.min.css">
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <!-- Include Buttons extension CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"> --}}
  
    
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
                                    <div class="row">
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">Select Document Type </label>
                                            <select id="single-select-abc1" class="form-select form-control"
                                                style="width:100%;" name="type" >
                                                <option value="" selected>Select Document Type</option>
                                                {{-- this option was giving to show all the documents, but due to complexity cant be given now --}}
                                                {{-- <option value="all" {{ old('type') == 'all' ? 'selected' : '' }}>
                                                Show All Documents</option> --}}
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
                                        <div class="mb-3 col-md-4">
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

                                        <div class="mb-3 col-md-4">
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
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Document Date (Start)</label>
                                            <div class="input-hasicon">
                                                <input name="start_date" type="date"
                                                    class="form-control bt-datepicker solid"
                                                    value="{{ old('start_date') }}">
                                                <div class="icon"><i class="far fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Document Date (End)</label>
                                            <div class="input-hasicon">
                                                <input name="end_date" type="date"
                                                    class="form-control bt-datepicker solid"
                                                    value="{{ old('end_date') }}">
                                                <div class="icon"><i class="far fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Locker Number</label>
                                            <input type="number" name="locker_no" class="form-control"
                                                placeholder="Enter Locker Number" value="{{ old('locker_no') }}">
                                        </div>
                                        <div class="mb-3 col-md-3 col-xl-3">
                                            <label class="form-label">Minimum Area Size</label>


                                            <input name="area_range_start" class="form-control"
                                                placeholder="Enter Minimum Area Size"
                                                value="{{ old('area_range_start') }}">



                                        </div>
                                        <div class="mb-3 col-md-3 col-xl-3">
                                            <label class="form-label">Maximum Area Size</label>
                                            <input name="area_range_end" class="form-control"
                                                placeholder="Enter Maximum Area Size"
                                                value="{{ old('area_range_end') }}">
                                        </div>
                                        <div class="mb-3 col-md-6 col-xl-6">


                                            <label class="form-label">Select Area Unit (Optional)</label>
                                            <select class="form-control" id="area-unit-dropdown" name="area_unit">
                                                <option value="">Select Unit</option>
                                                <option value="1" {{ old('area_unit') == 1 ? 'selected' : '' }}>
                                                    Acres and Cents</option>
                                                <option value="2" {{ old('area_unit') == 2 ? 'selected' : '' }}>
                                                    Square Feet</option>

                                            </select>
                                        </div>
                                    </div>




                                    <div class="card-footer">
                                        <a href="" class="btn-link"></a>
                                        <div class="text-end"><button class="btn btn-secondary"
                                                type="submit"><i class="fas fa-filter"></i>&nbsp;Filter</button>
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
                        <button id="exportButton" class="btn btn-primary float-end"><i class="fas fa-file-export"></i>&nbsp;Export</button>
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
                                        <th scope="col">Sl. No.</th>
                                        <th scope="col">Document Name</th>
                                        <th scope="col">Document Type</th>
                                        <th scope="col">Village</th>
                                        <th scope="col">District</th>

                                        <th scope="col">Status</th>
                                        @if($user && $user->hasPermission('Main Document View '))

                                        <th scope="col">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($documents as $index => $item)
                                        <tr>

                                            <th scope="row">{{ $index + 1 }}</th>
                                            <td scope="row">{{ $item->name }}</td>
                                            <td scope="row">
                                                {{ ucWords(str_replace('_', ' ', $item->document_type_name)) }}</td>

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
                                            @if($user && $user->hasPermission('Main Document View '))

                                            <td>
                                               
                                                  
                                                @if ($item->status_id == 1)
                                                <a href="{{ url('/') }}/review_doc/{{ $item->document_type_name }}/{{ $item->tableId }}" style="padding: 0.25rem 0.5rem; font-size: 0.65rem;" class="btn btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            @else
                                                <a href="{{ url('/') }}/review_doc/{{ $item->document_type_name }}/{{ $item->tableId }}" style="padding: 0.25rem 0.5rem; font-size: 0.65rem;" class="btn btn-secondary">
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
    {{-- <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/nouislider/distribute/nouislider.min.js"></script> --}}
<!-- DataTables CSS -->
<!-- Include DataTables CSS for Bootstrap 5 -->


<!-- Include jQuery -->
{{-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<!-- Include DataTables and Bootstrap JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
<!-- Include DataTables Buttons extension JS -->
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap5.min.js"></script>
<!-- Include JSZip for Excel export -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- Include pdfmake for PDF export -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<!-- Include HTML5 export buttons -->
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script> --}}




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
</script>

{{-- <script src="/assets/vendor/nouislider/nouislider.min.js"></script>
<script src="/assets/js/plugins-init/nouislider-init.js"></script> --}}
<script>
    document.getElementById('exportButton').addEventListener('click', function() {
        var table = document.getElementById('example3'); // Your table ID
        var rows = table.querySelectorAll('tr');
        var csv = [];
    
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll('td, th');
    
            for (var j = 0; j < cols.length-1; j++) {
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
        csvFile = new Blob([csv], {type: "text/csv"});
    
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
    

