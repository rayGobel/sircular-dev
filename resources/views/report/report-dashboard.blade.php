@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <p class="lead">Available reports</p>

            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <h4>Masterdata</h4>
                    <dl class="dl-horizontal">
                        <dt>
                            <p><a href="{{ url('report/create-dist-realization') }}">data penerbit</a></p>
                        </dt>
                        <dd>Laporan ini menampilkan data penerbit beserta majalah-majalahnya</dd>
                        <dt>
                            <a href="{{ url('report/create-dist-realization') }}">data agen</a>
                        </dt>
                        <dd>Laporan ini menampilkan data agen/outlet yang menjadi pelanggan</dd>
                        <dt>
                            <a href="{{ url('report/create-dist-realization') }}">ketentuan agen</a>
                        </dt>
                        <dd> </dd>
                        <dt>
                            <a href="{{ url('report/create-dist-realization') }}">data discount</a>
                        </dt>
                        <dd>Laporan ini menampilkan data discount dari tiap pelanggan</dd>
                    </dl>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <h4>Sirkulasi</h4>
                    <dl class="dl-horizontal">
                        <dt>
                            <p><a href="{{ url('report/create-dist-realization') }}">Realisasi distribusi</a></p>
                        </dt>
                        <dd>Realisasi Distribusi</dd>
                    </dl>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <p>Invoice</p>
                    <dl>
                        <dt>Laporan invoice</dt>
                        <dd>Somewhere here</dd>
                        <dt>Sistem informasi</dt>
                        <dd>Somewhere gone here</dd>
                    </dl>
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
