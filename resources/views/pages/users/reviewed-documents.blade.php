@php
use Carbon\Carbon;
@endphp
<x-app-layout>

    <x-header />
    <x-sidebar />

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">

            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-body">
                        <div class="row page-titles">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/users">Users</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0)">Reviewed Documents User Wise Count</a>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-xxl-4 col-sm-4">
                                <div class="card text-white bg-primary">
                                    <ul class="list-group list-group-flush">

                                        <li class="list-group-item d-flex justify-content-between"><span
                                                class="mb-0 text-white">Name :</span><strong
                                                class="text-white">{{ $user_detail->name }} </strong></li>
                                        <li class="list-group-item d-flex justify-content-between"><span
                                                class="mb-0 text-white">Email Id :</span><strong
                                                class="text-white">{{ $user_detail->email }}</strong></li>
                                        <li class="list-group-item d-flex justify-content-between"><span
                                                class="mb-0 text-white">Phone :</span><strong
                                                class="text-white">{{ $user_detail->phone ?? "xxxxxxxxx" }}</strong></li>

                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-sm-8">
                                <div class="card">
                                    <div class="card-header border-0 pb-0">
                                        <h2 class="card-title">Today Reviewed Document Count</h2>
                                    </div>
                                    <div class="card-body pb-0">
                                       

                                    </div>
                                    <div class="card-footer pt-0 pb-0 text-center">
                                        <div class="row">
                                            <div class="col-3 pt-3 pb-3 border-end">
                                                <h3 class="mb-1 text-primary">{{ $todayCounts[0] ?? 0 }}</h3>
                                                <span>Pending</span>
                                            </div>
                                            <div class="col-3 pt-3 pb-3 border-end">
                                                <h3 class="mb-1 text-primary">{{ $todayCounts[1]??0 }}</h3>
                                                <span>Approved</span>
                                            </div>
                                            <div class="col-3 pt-3 pb-3 border-end">
                                                <h3 class="mb-1 text-primary">{{ $todayCounts[2] ??0 }}</h3>
                                                <span>Hold</span>
                                            </div>
                                            <div class="col-3 pt-3 pb-3">
                                                <h3 class="mb-1 text-primary">{{ $todayCounts[3] ??0 }}</h3>
                                                <span>Review Feedback</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Reviewed Documents Report</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive recentOrderTable">
                                            <table class="table verticle-middle table-responsive-md">
                                                <thead>
                                                    <tr>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Pending</th>
                                                        <th>Approved</th>
                                                        <th>Hold</th>
                                                        <th>Reviewer Feedback</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($data as $date => $counts)
                                                    <tr>
                                                        <td>{{ $date }}</td>
                                                        {{-- <td>{{ Carbon::parse($date)->format('d-M-Y') }}</td> --}}
                                                        <td>{{ $counts['Pending'] }}</td>
                                                        <td>{{ $counts['Approved'] }}</td>
                                                        <td>{{ $counts['Hold'] }}</td>
                                                        <td>{{ $counts['Reviewer Feedback'] }}</td>
                                                        <td>{{ $counts['Total'] }}</td>
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

        </div>
    </div>


    @include('layouts.footer')


</x-app-layout>
