@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-1">
            <p class="lead">Generate report</p>
            <form action="{{ url('/report/dist-realization') }}" method="POST">
                <div class="form-group">
                    <label for="magazine-name">Magazine</label>
                    <select id="magazine-name" class="form-control">
                        @foreach($magazines as $mag)
                        <option value="{{ $mag->id }}">{{ $mag->name }}</option>
                        @endforeach
                    </select>
                </div><!-- magazine-name -->

                <div class="form-group">
                    <label for="edition-name">Edition</label>
                    <select id="edition-name" class="form-control" name="edition_id">
                        @foreach($editions as $ed)
                        <option value="{{ $ed->id }}">{{ $ed->magazine->name }} {{ $ed->edition_code }}</option>
                        @endforeach
                    </select>
                </div><!-- edition-name -->

                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <button type="submit" class="btn btn-default">
                        <i class="fa fa-eye fa-fw"></i>
                        Preview
                    </button>
                    <a class="btn btn-default" href="{{ url('report/print-dist-realization') }}">
                        <i class="fa fa-print fa-fw"></i>
                        Print
                    </a>
                    <a class="btn btn-default" href="{{ url('report/export-dist-realization') }}">
                        <i class="fa fa-file-excel-o fa-fw"></i>
                        Export
                    </a>
                    <a class="btn btn-default" href="{{ url('report') }}">
                        <i class="fa fa-reply fa-fw"></i>
                        Cancel
                    </a>
                </div><!-- buttons -->

            </form>


        </div>
    </div>
</div>

@endsection
