@extends('app')

@section('content')
<div class='container'>

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
   </div> <!-- end of message/error row -->

   <div class='row'>
       <div class="col-lg-4 col-lg-offset-2">
           <p class="lead">
           <strong>Detail Invoice </strong>
           </p>
       </div>
       <div class="col-lg-4">
           <form class="pull-right" action="/sircular-dev/public/invoice/invoice/{{$invoiceID}}" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-danger pull-right">
                <i class="fa fa-close fa-fw"></i> Delete
            </button>
           </form>
       </div>
   </div>

   <div class='row'>
       <hr></hr>
       <div class='col-lg-5 col-sm-offset-1'>
           <p class='text'>Nama agen: {{ $invDet->agent->name }}</p>
           <p class='text'>Alamat: {{ $invDet->agent->city }}</p>
           <p class='text'>Majalah: {{ $invDet->edition->magazine->name }}</p>
       </div>
       <div class='col-lg-5 col-sm-offset-1'>
           <p class='text'>NO: {{ $invDet->number }}</p>
           <p class='text'>Tanggal: {{ $invDet->issue_date }}</p>
           <p class='text'>Jatuh tempo: {{ $invDet->due_date }}</p>
       </div>
   </div>

   <div class='row'>
       <div class='col-lg-12'>
           <p class='text'>Pengiriman</p>
           <table class='table'>
               <thead>
                   <tr>
                       <th>No</th>
                       <th>#DO</th>
                       <th>Edisi</th>
                       <th>Harga</th>
                       <th>Jumlah</th>
                       <th>Diskon</th>
                       <th>Total</th>
                   </tr>
               </thead>
               <tbody>
                   <?php $i = 1; ?>
                   @foreach( $invDet->detailDelivery as $details )
                   <tr>
                       <td><?php echo $i; $i++ ?></td>
                       <td>
                           <a href="/sircular-dev/public/circulation/distribution-plan/{{$details->delivery->distPlanDet->distributionPlan->id}}/details/{{$details->delivery->distPlanDet->id}}/delivery/{{$details->delivery->id}}">
                         {{ $details->delivery->order_number }}</a>
                       </td>
                       <td>{{ $details->edition->edition_code }}</td>
                       <td class="monetary-fmt">{{ $details->edition->price }}</td>
                       <td>{{ $details->delivery->quota }}</td>
                       <td class="monetary-fmt">{{ $details->discount }}</td>
                       <td class="monetary-fmt">{{ $details->total }}</td>
                   </tr>
                   @endforeach
               </tbody>
           </table>
       </div>
   </div> <!-- tabel pengiriman -->

   <div class='row'>
       <div class='col-lg-12'>
           <p class='text'>Retur</p>
           <table class='table'>
               <thead>
                   <tr>
                       <th>No</th>
                       <th>#Retur</th>
                       <th>Edisi</th>
                       <th>Harga</th>
                       <th>Jumlah</th>
                       <th>Diskon</th>
                       <th>Total</th>
                   </tr>
               </thead>
               <tbody>
                   <?php $i = 1 ?>
                   @foreach( $invDet->detailReturn as $details )
                   <tr>
                       <td><?php echo $i; $i++ ?></td>
                       <td>
                           <a href="/sircular-dev/public/circulation/return/{{$details->returnItem->id}}">
                            {{ $details->returnItem->number }}
                           </a>
                       </td>
                       <td>{{ $details->returnItem->edition->edition_code }}</td>
                       <td class='monetary-fmt'>{{ $details->returnItem->edition->price }}</td>
                       <td>{{ $details->returnItem->total }}</td>
                       <td class='monetary-fmt'>{{ $details->discount }}</td>
                       <td class='monetary-fmt'>{{ $details->total }}</td>
                   </tr>
                   @endforeach
               </tbody>
           </table>
       </div>
   </div> <!-- tabel retur -->

   <hr></hr>
   <div class='row'>
       <div class='col-lg-12'>
           <p class='text'>Total Invoice</p>
           <table class='table'>
               <thead>
                   <tr>
                       <th>No</th>
                       <th>Edisi</th>
                       <th>Jumlah</th>
                       <th>Diskon</th>
                       <th>Total</th>
                   </tr>
                   <tr>
                       <td>1</td>
                       <td>{{ $invDet->edition->magazine->name }}, Edisi:{{ $invDet->edition->edition_code }}</td>
                       <td class='monetary-fmt'>{{ $totalDelivery - $totalDeliveryDiscount }}</td>
                       <td class='monetary-fmt'>{{ $totalReturn }}</td>
                       <td class='monetary-fmt'>{{ ($totalDelivery - $totalDeliveryDiscount) - ($totalReturn)  }}</td>
                   </tr>
               </thead>
               <tbody>
               </tbody>
           </table>
       </div>
   </div> <!-- tabel total invoice -->
   <hr></hr>
   <div class="row">
       <div class="col-sm-12">
           <button type="submit" class="btn btn-default"><i class="fa fa-print fa-fw"></i> Cetak</button>
       </div>
   </div>

   <hr></hr>

</div>
@endsection
