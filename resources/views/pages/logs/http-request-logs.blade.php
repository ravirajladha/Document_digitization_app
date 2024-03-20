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


                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title">Http Request Logs</h5>
                                   
                                </div>
                                <div class="card-body">

                                    <div class="table-responsive">
                                        {{-- <div class="table-responsive"> --}}
                                        <table id="example3" class="display" style="min-width: 845px">
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
                                            <td>{{ \Carbon\Carbon::parse($log->created_at)->format('H:i d/m/Y') }}</td> {{-- Format the created_at timestamp --}}
                                            {{-- <td>{{ \Carbon\Carbon::parse($log->updated_at)->format('H:i d/m/Y') }}</td>  --}}
                                            {{-- Format the updated_at timestamp --}}
                                        </tr>
                                    @endforeach
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
    </div>



    @include('layouts.footer')


</x-app-layout>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
<!-- Latest compiled and minified jQuery -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}



  