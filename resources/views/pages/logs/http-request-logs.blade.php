<x-app-layout>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <x-header />
    <x-sidebar />

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Logs</a></li>

                        <li class="breadcrumb-item active"><a href="javascript:void(0)">HTTP Request Logs</a></li>
                    </ol>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="filter cm-content-box box-primary">
                            <div class="content-title SlideToolHeader">
                                <h4>
                                    Filters
                                </h4>
                                <div class="tools">
                                    <a href="javascript:void(0);" class="expand handle"><i
                                            class="fal fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="cm-content-body form excerpt">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form action="{{ route('compliances.index') }}" method="GET">
                                                <div class="row">




                                                    <div class="mb-3 col-md-4">
                                                        <label for="start_due_date" class="form-label">URL</label>
                                                        <input type="text" id="start_due_date" class="form-control"
                                                            name="start_due_date" value="{{ old('start_due_date') }}">
                                                    </div>
                                                    <div class="mb-3 col-md-4">
                                                        <label for="start_due_date" class="form-label">Start Due
                                                            Date</label>
                                                        <input type="date" id="start_due_date" class="form-control"
                                                            name="start_due_date" value="{{ old('start_due_date') }}">
                                                    </div>

                                                    <div class="mb-3 col-md-4">
                                                        <label for="end_due_date" class="form-label">End Due
                                                            Date</label>
                                                        <input type="date" id="end_due_date" class="form-control"
                                                            name="end_due_date" value="{{ old('end_due_date') }}">
                                                    </div>



                                                    <div class="mb-3 col-md-4">
                                                        <label for="status" class="form-label">Models</label>
                                                        <select id="status" class="form-select form-control"
                                                            id="single-select-abc4" name="status">
                                                            <option value="">Model 1</option>
                                                            <option value="">Model 2</option>

                                                        </select>
                                                    </div>

                                                    <div class="mb-3 col-md-4">
                                                        <label for="status" class="form-label">Users</label>
                                                        <select id="status" class="form-select form-control"
                                                            id="single-select-abc4" name="status">
                                                            <option value="">User 1</option>
                                                            <option value="">User 2</option>

                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-md-4">
                                                        <label for="status" class="form-label">Method</label>
                                                        <select id="status" class="form-select form-control"
                                                            id="single-select-abc4" name="status">
                                                       
                                                            <option value="">Get</option>
                                                            <option value="">Post</option>

                                                        </select>
                                                    </div>

                                                    <div class="col-md-12 d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-primary">Filter</button>
                                                        <a href="{{ route('advocates.export', request()->all()) }}"
                                                            class="btn btn-success ms-2">Export to Excel</a>
                                                        <a href="{{ url('/') }}/advocates"
                                                            class="btn btn-dark ms-2">Reset</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title">Http Request Logs</h5>

                                </div>
                                <div class="card-body">

                                    <div class="table-responsive">
                                        @if ($logs->isEmpty())
                                        <p>No logs available.</p>
                                    @else
                                        {{-- <div class="table-responsive"> --}}
                                        {{-- <table id="example3" class="display" style="min-width: 845px"> --}}
                                            <table  class="table table-responsive-md">
                                            {{-- <table id="example3" class="display"> --}}

                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>User ID</th>
                                                    <th>IP Address</th>
                                                    {{-- <th>Model ID</th> --}}
                                                    {{-- <th >Agent</th> --}}
                                                    <th>Method</th>
                                                    <th>URL</th>
                                                    <th>Created At</th>
                                                    {{-- <th>Updated At</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $counter = 0;
                                                @endphp
                                                @foreach ($logs->chunk(10) as $batch)
                                                    @foreach ($batch as $log)
                                                        @php $counter++; @endphp
                                                        <tr>
                                                            {{-- <td>{{ $counter }}</td>  --}}
                                                            <td>{{ $log->id }}</td> {{-- Display the user name --}}

                                                            <td>{{ $log->user_name }}</td> {{-- Display the user name --}}
                                                            <td>{{ $log->ip_address }}</td> {{-- Remove namespace prefix --}}
                                                            {{-- <td style="max-width:200px; word-wrap:break-word;">{{ $log->user_agent }}</td>  --}}
                                                            <td>{{ $log->method }}</td>
                                                            <td>{{ $log->url }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($log->created_at)->format('H:i d-M-Y') }}
                                                            </td> {{-- Format the created_at timestamp --}}
                                                         
                                                        </tr>
                                                    @endforeach
                                                @endforeach

                                            </tbody>
                                        </table>

                                        <div class="row">
                                            <div class="col">
                                                {{ $logs->links('vendor.pagination.custom') }}
                                            </div>
                                        </div>
                                    @endif

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
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
<!-- Latest compiled and minified jQuery -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
