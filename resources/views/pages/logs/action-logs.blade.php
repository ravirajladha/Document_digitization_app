<x-app-layout>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}

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

                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Action Logs</a></li>
                    </ol>
                </div>


                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title">Action Logs</h5>

                                </div>
                                <div class="card-body">

                                    <div class="table-responsive">
                                        {{-- <div class="table-responsive"> --}}
                                        {{-- <table id="example3" class="display" style="min-width: 845px"> --}}
                                            @if ($logs->isEmpty())
                                            <p>No logs available.</p>
                                        @else
                                            <table  class="table table-responsive-md">
                                         {{--    <table id="example3" class="display"> --}}

                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>User ID</th>
                                                    <th>Model Type</th>
                                                    {{-- <th>Model ID</th> --}}
                                                    <th>Changes</th>
                                                    <th>Action</th>
                                                    <th>Created At</th>
                                                    {{-- <th>Updated At</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $counter = 0; // Calculate the starting index
                                                @endphp

                                                @foreach ($logs as $log)
                                                    @php $counter++; @endphp
                                                    <tr>
                                                        {{-- <td>{{ $counter }}</td>  --}}
                                                        <td>{{ $log->id }}</td> {{-- Display the user name --}}
                                                        <td>{{ $log->user_name }}</td> {{-- Display the user name --}}
                                                        <td>{{ ucwords(str_replace('_', ' ', class_basename($log->model_type))) }}</td>
                                                        {{-- Remove namespace prefix --}}
                                                        <td style="max-width:200px; word-wrap:break-word;">
                                                            {{ $log->changes }}</td> {{-- Use max-width and word-wrap --}}
                                                        <td>{{ ucwords($log->action)}}</td>
                                                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('H:i d-M-Y') }}
                                                            
                                                        </td> 
                                                        

                                                        {{-- <td>{{ Carbon::parse($soldLands->created_at)->format('H:i d-M-Y') }}</td> --}}
                                                        {{-- <td>{{ \Carbon\Carbon::parse($log->updated_at)->format('H:i d/m/Y') }}</td>  --}}
                                                        {{-- Format the updated_at timestamp --}}
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                        {{-- <div class="row">
                                        {{ $logs->links() }}

                                        </div> --}}
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
    </div>



    @include('layouts.footer')


</x-app-layout>
