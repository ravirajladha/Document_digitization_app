<x-app-layout>


    <x-header />
    @include('layouts.sidebar')

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">

            <div class="page-body">
                <div class="container-fluid">
                    {{-- <div class="page-body">
                        <div class="row page-titles">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0)">Basic Fields Detail</a>
                                </li>
                            </ol>
                        </div>
                    </div> --}}

                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-sm-12">




                                <div class="row">
                                    {{-- <div class="col-xl-3 col-xxl-3 col-sm-6">
                                        <div class="card overflow-hidden">
                                            <div class="social-graph-wrapper widget-facebook">
                                                <span class="s-icon">SETS</span>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 border-end">
                                                    <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                                                        <h4 class="m-1"><span class="counter">-</span></h4>
                                                        <p class="m-0">Count</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                                                        <div class="input-group-append">
                                                            <a href="/set"> <button type="button"
                                                                    class="btn btn-secondary"><i
                                                                        class="fa fa-plus"></i></button></a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-xl-3 col-xxl-3 col-sm-6">
                                        <div class="card overflow-hidden">
                                            <div class="social-graph-wrapper widget-facebook">
                                                <span class="s-icon">RECEIVER TYPE</span>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 border-end">
                                                    <div class="pt-3 pb-3 ps-0 pe-0 text-center">
           
            <h4 class="m-1"><span class="counter">{{$receiver_type_count}}</span></h4>
                                                        <p class="m-0">Count</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                                                        <div class="input-group-append">
                                                            <a href="/receiver-type"> <button type="button"
                                                                    class="btn btn-secondary"><i
                                                                        class="fa fa-plus"></i></button></a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-xl-3 col-xxl-3 col-sm-6">
                                        <div class="card overflow-hidden">
                                            <div class="social-graph-wrapper widget-facebook">
                                                <span class="s-icon">RECEIVERS</span>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 border-end">
                                                    <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                                                        <h4 class="m-1"><span class="counter">-</span></h4>
                                                        <p class="m-0">Count</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                                                        <div class="input-group-append">
                                                            <a href="/receivers"> <button type="button"
                                                                class="btn btn-secondary"><i
                                                                    class="fa fa-plus"></i></button></a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div> --}}
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
