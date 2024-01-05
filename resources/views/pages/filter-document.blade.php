<x-app-layout>

    <x-header/>
    @include('layouts.sidebar')

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

            <div class="container-fluid">
                <div class="row">

                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Filter Document</h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form action="{{ url('/') }}/filter-document" method="GET">
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Select Document Type </label>
                                                <select class="form-select form-control"
                                                    aria-label="Default select example" name="type">
                                                    <option value="" selected>Select Document Type</option>
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
                                                {{-- ... --}}
                                                <select class="form-select form-control" name="state"
                                                    aria-label="State select">
                                                    <option value="" selected>Select State</option>
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state }}"
                                                            {{ old('state') == $state ? 'selected' : '' }}>
                                                            {{ $state }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                {{-- ... --}}


                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Select District </label>
                                                {{-- ... --}}
                                                <select class="form-select form-control" name="district"
                                                    aria-label="District select">
                                                    <option value="" selected>Select District</option>
                                                    @foreach ($districts as $district)
                                                        <option value="{{ $district }}"
                                                            {{ old('district') == $district ? 'selected' : '' }}>
                                                            {{ $district }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- ... --}}
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Select Village </label>
                                                {{-- ... --}}
                                                <select class="form-select form-control" name="village"
                                                    aria-label="Village select">
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
                                                    placeholder="Enter Locker Number"
                                                    value="{{ old('locker_no') }}">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Old Locker No</label>
                                                <input type="number" name="old_locker_no" class="form-control"
                                                    placeholder="Enter Old Locker Number"
                                                    value="{{ old('old_locker_no') }}">
                                            </div>
                                            <!-- Date Range Filter -->
                                            {{-- <div class="mb-3 col-md-6">
                                                <label for="dateRange" class="form-label">Year (1700 -
                                                    {{ date('Y') }})</label>
                                                <input type="number" id="dateRange" name="year" min="1700"
                                                    max="{{ date('Y') }}" value="{{ old('year', date('Y')) }}"
                                                    class="form-control">
                                            </div> --}}

                                            <!-- Number of Pages Range Filter -->
                                            <div class="mb-3 col-md-6">
                                                <label for="pagesRange" class="form-label">Number of Pages (1 - 100)</label>
                                                <input type="range" id="pagesRange" name="number_of_pages" min="1" max="100"
                                                       value="{{ old('number_of_pages') !== null ? old('number_of_pages') : 'null' }}"
                                                       oninput="if(this.value !== 'null') { this.nextElementSibling.value = this.value; } else { this.nextElementSibling.value = ''; }"
                                                       class="form-range">
                                                <output>{{ old('number_of_pages') !== null ? old('number_of_pages') : 'null' }}</output>
                                            </div>
                                            <div class="card-footer">
                                                <a href="" class="btn-link"></a>
                                                <div class="text-end"><button class="btn btn-secondary"
                                                        type="submit">Filter</button>
                                                </div>
                                            </div>
                                            

                                            {{-- <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">Filter</button>
                                            </div> --}}
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="container-fluid">
                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Document</h4>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example5" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="custom-control d-inline custom-checkbox ms-2">
                                                        <input type="checkbox" class="form-check-input" id="checkAll"
                                                            required="">
                                                        <label class="form-check-label" for="checkAll"></label>
                                                    </div>
                                                </th>
                                                <th scope="col">Sl no</th>
                                                <th scope="col">Document Name</th>
                                                <th scope="col">Document Type</th>
                                                <th scope="col">Created At</th>
                                         
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($documents as $index => $item)
                                                <tr>
                                                    <td>
                                                        <div class="form-check custom-checkbox ms-2">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="customCheckBox2" required="">
                                                            <label class="form-check-label"
                                                                for="customCheckBox2"></label>
                                                        </div>
                                                    </td>
                                                    <th scope="row">{{ $index + 1 }}</th>
                                                    <td scope="row">{{ $item->name }}</td>
                                                    <td scope="row">{{ $item->document_type }}</td>

                                                    <td>{{ $item->created_at }}</td>
                                               
                                                    @if ($item->status_id == 0)
                                                        <td>
                                                            <span class="badge light badge-danger">
                                                                <i class="fa fa-circle text-danger me-1"></i>
                                                                Pending
                                                            </span>
                                                        </td>

                                                        <td><a href="{{ url('/') }}/review_doc/{{ $item->document_type_name }}/{{ $item->tableId }}"
                                                                type="button" class="btn btn-primary">Review</a>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span class="badge light badge-success">
                                                                <i class="fa fa-circle text-success me-1"></i>
                                                                Accepted
                                                            </span>
                                                        </td>

                                                        <td><a href="{{ url('/') }}/review_doc/{{ $item->document_type_nam }}/{{ $item->tableId }}"
                                                                type="button" class="btn btn-primary">View</a></td>
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
    // Assuming you have an element with ID 'pagesRange' for the pages slider
    var pagesSlider = document.getElementById('pagesRange');
    var pagesOutput = pagesSlider.nextElementSibling; // The <output> element after the slider
    pagesOutput.value = pagesSlider.value; // Display the default slider value

    // Update the current slider value (each time you drag the slider handle)
    pagesSlider.oninput = function() {
        pagesOutput.value = this.value;
    }
</script>
