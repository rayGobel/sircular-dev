@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <p class="lead">Available reports</p>

            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <p>Sirkulasi</p>
                    <p><a href="{{ url('report/create-dist-realization') }}">Distribution Realization</a></p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <p>Invoice</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <p>Return</p>
                </div>
            </div>

        </div><!-- container.row.col-lg-12 -->
    </div><!-- container.row -->
</div><!-- container -->
@endsection
