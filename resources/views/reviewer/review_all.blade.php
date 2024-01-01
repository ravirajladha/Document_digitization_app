<x-app-layout>
 

    @include('layouts.header')
    @include('layouts.sidebar')

<div class="content-body default-height">
    <!-- row -->
    <div class="container-fluid">

<div class="page-body">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3>
                        Documents Details</h3>
                </div>
                <div class="col-12 col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">
                                <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Documents Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3>Data</h3>
                        <table class="table m-2">
                            <tbody>
                                @foreach ($columns as $column)
                                    <tr>
                                        @if (!($column == 'id' || $column == 'created_at' || $column == 'updated_at' || $column == 'status'))
                                            @if (!($field_types->$column == 3 || $field_types->$column == 4 || $field_types->$column == 6))
                                            @php
                                            $columnName = ucWords(str_replace('_', ' ', $column));
                                        @endphp
                                                <th>{{ $columnName }}</th>
                                                <td>{{ $document->$column }}</td>
                                            @endif
                                        @endif
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3>File</h3>
                                <div class="row">
                                @foreach ($columns as $column)
                                        @if (!($column == 'id' || $column == 'created_at' || $column == 'updated_at' || $column == 'status'))
                                        @php
                                            $columnName = ucWords(str_replace('_', ' ', $column));
                                        @endphp
                                            @if ($field_types->$column == 3)
                                                <h4 class="mt-2">{{ $columnName }}</h4>
                                                <img src="{{ url('/')}}/{{ $document->$column }}" alt="img">
                                            @elseif( $field_types->$column == 4)
                                            <h4 class="mt-2">{{ $columnName }}</h4>
                                            <iframe src="{{ url('/')}}/{{$document->$column}}" width="100%" height="600" zoom="150%"></iframe>
                                            @elseif( $field_types->$column == 6)
                                            <h4 class="mt-2">{{ $columnName }}</h4>
                                            <iframe src="{{ url('/')}}/{{$document->$column}}" width="100%" height="500" zoom="150%"></iframe>
                                            @endif
                                        @endif
                                @endforeach
                                </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class=" my-auto">
                    <div class="text-end">
                        @if ($document->status == 0 && Auth::user()->type == "admin")
                        <a class="btn btn-primary"
                        href="{{ url('/') }}/edit_document/{{ $tableName }}/{{ $document->id }}"
                        rel="noopener noreferrer">Update</a>
                        @elseif ($document->status == 0 && Auth::user()->type == 'reviewer')
                        <form action="{{ url('/')}}/reviewer/accept_and_next" method="post">
                            @csrf
                            <input type="hidden" value="{{$tableName}}" name="type">
                            <input type="hidden" value="{{$document->id}}" name="id">
                        <td><button type="submit" class="btn btn-primary" href=""
                                rel="noopener noreferrer">Accept</button></td>
                        </form>
                        @else
                        <button type="button" class="btn btn-success" href=""
                                rel="noopener noreferrer">Accepted</button></td>
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


