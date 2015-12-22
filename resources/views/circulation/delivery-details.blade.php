@extends('app')

@section('content')
<div class="container">
   <div class="row">
       <div class="col-lg-12">
       @if (Session::has('message'))
           <div class="alert alert-info" role="alert">
               <p>{{ Session::get('message') }}</p>
           </div>
       @endif
       @if (Session::has('errMsg'))
           <div class="alert alert-danger" role="alert">
               <p>{{ Session::get('errMsg') }}</p>
           </div>
       @endif
       </div>
   </div>

   <!-- List all DO's in each rows -->
   @foreach($dlv->delivery as $dv)
   <div class="row">
       <div class="col-lg-10 col-lg-offset-1">
           <hr></hr>
           <p class="lead">Delivery Order</p>
           <p>Nama Agen: {{ $dlv->agent->name }}</p>
           <p>Alamat: {{ $dlv->agent->city }}</p>
           <p>Nomor DO: {{ $dv->order_number }}</p>
           <p>Tanggal: {{ $dv->date_issued }}</p>
           <table class="table">
               <tr>
                   <th>No</th>
                   <th>Keterangan</th>
                   <th>Jumlah</th>
                   <th>Harga</th>
                   <th>Total</th>
               </tr>
               <tr>
                   <td>1</td>
                   <td>
                        Majalah {{$dlv->DistributionRealization->edition->magazine->name}}
                        edisi {{$dlv->DistributionRealization->edition->edition_code}}
                   </td>
                   <td>{{$dv->quota}}</td>
                   <td class="monetary-fmt">{{$dlv->DistributionRealization->edition->price}}</td>
                   <td class="monetary-fmt">{{$dlv->DistributionRealization->edition->price * $dv->quota}}</td>
               </tr>
           </table>
       </div>
   </div>
   <div class='row'>
       <div class="col-lg-10 col-lg-offset-1">
           <a href='' class='btn btn-info'>
               <i class='fa fa-print fa-fw'></i>
                Cetak
           </a>
           <a href='' class='btn btn-danger'>
               <i class='fa fa-trash fa-fw'></i>
                Hapus DO
           </a>
       </div>
   </div>
   @endforeach

   <!-- begin control block -->
   <hr></hr>
   <div class='row'>
       <div class="col-lg-10 col-lg-offset-1">
           <a href='' class='btn btn-primary'>
               <i class='fa fa-print fa-fw'></i>
                Cetak Semua
           </a>
       </div>
   </div>
</div>
@endsection
