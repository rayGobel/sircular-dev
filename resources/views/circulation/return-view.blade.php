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
   <div class="row">
       <div class="col-lg-10 col-lg-offset-1">
           <hr></hr>
           <p class="lead">Retur Majalah</p>
           <p>Nama Agen: {{ $returnItem->agent->name }}</p>
           <p>Alamat: {{ $returnItem->agent->city }}</p>
           <p>Retur #: {{ $returnItem->number }}</p>
           <p>Tanggal: {{ $returnItem->date }}</p>
           <table class="table">
               <tr>
                   <th>No</th>
                   <th>Keterangan</th>
                   <th>Jumlah</th>
               </tr>
               <tr>
                   <td>1</td>
                   <td>
                        Majalah {{$returnItem->magazine->name}}
                        edisi {{$returnItem->edition->edition_code}}
                   </td>
                   <td>{{$returnItem->total}}</td>
               </tr>
           </table>
           @if ($returnItem->in_invoice == 0)
           <p><em>Barang retur belum masuk Invoice</em></p>
           @else
           <p><em>Barang retur terdaftar di Invoice #: <a href="/sircular-dev/public/invoice/invoice/{{$returnItem->invoiceDetail->invoice->id}}">{{$returnItem->invoiceDetail->invoice->number}}</a></em></p>
           @endif

       </div>
   </div>
   <div class='row'>
       <div class="col-lg-10 col-lg-offset-1">
           <a href='' class='btn btn-info'>
               <i class='fa fa-print fa-fw'></i>
                Cetak
           </a>
       </div>
   </div>

   <!-- begin control block -->
</div>
@endsection
