@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <p class="lead">Available reports</p>

            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <h4>Masterdata</h4>
                    <dl class="dl">
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
                    <dl class="dl">
                        <dt>
                            <p><a href="">Laporan Rencana Distribusi</a></p>
                        </dt>
                        <dd>Laporan ini menampilkan rencana distribusi majalah ke agen-agen</dd>
                        <dt>
                            <p><a href="{{ url('report/create-dist-realization') }}">Realisasi distribusi</a></p>
                        </dt>
                        <dd>Laporan ini menampilkan realisasi distribusi majalah ke agen-agen</dd>
                        <dt>
                            <p><a href="">Laporan Retur</a></p>
                        </dt>
                        <dd>Laporan ini menampilkan retur majalah dari agen-agen</dd>
                        <dt>
                            <p><a href="">Laporan Retur Harian</a></p>
                        </dt>
                        <dd></dd>
                        <dt>
                            <p><a href="">Laporan Rekap DO</a></p>
                        </dt>
                        <dd></dd>
                        <dt>
                            <p><a href="">Laporan Jumlah Majalah</a></p>
                        </dt>
                        <dd></dd>
                        <dt>
                            <p><a href="">Laporan Mutasi Majalah</a></p>
                        </dt>
                        <dd></dd>
                        <dt>
                            <p><a href="">Laporan Penjualan Majalah</a></p>
                        </dt>
                        <dd></dd>
                        <dt>
                            <p><a href="">Laporan Pengiriman per ekspedisi</a></p>
                        </dt>
                        <dd></dd>
                        <dt>
                            <p><a href="">Laporan penjualan per kota</a></p>
                        </dt>
                        <dd></dd>
                        <dt>
                            <p><a href="">Laporan retur per majalah dan agen</a></p>
                        </dt>
                        <dd></dd>
                    </dl>
                </div>
            </div>


        </div><!-- container.row.col-lg-12 -->
    </div><!-- container.row -->
</div><!-- container -->
@endsection
