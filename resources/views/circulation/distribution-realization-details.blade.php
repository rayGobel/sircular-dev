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
   <div class="row">
       <div class="col-lg-4 col-lg-offset-2">
           <p class="lead">
           <strong>Realisasi Distribusi Majalah </strong>
           </p>
           <p><em><a href="{{ url('circulation/distribution-plan') }}/{{ $dist->distribution_plan_id }}">
                   Lihat rencana</a></em></p>
       </div>
   </div>
   <div class="row">
       <div class="col-lg-8 col-lg-offset-2">
           <hr></hr>
           <p class="lead">Majalah yang akan diedarkan</p>
           <table class="table">
                   <tr>
                       <th>Majalah</th>
                       <td>{{ $dist->edition->magazine->name }}</td>
                       <th>Edisi</th>
                       <td>{{ $dist->edition->edition_code }}</td>
                   </tr>
                   <tr>
                       <th>Cetakan</th>
                       <td>{{ $dist->print_number }}</td>
                       <th>Tanggal Terbit</th>
                       <td>{{ $dist->publish_date }}</td>
                   </tr>
                   <tr>
                       <th>Harga</th>
                       <td>{{ $dist->edition->price }}</td>
                   </tr>
           </table>
       </div>
   </div>
   <div class="row">
       <div class="col-lg-8 col-lg-offset-2">
           <hr></hr>
           <p class="lead">Rencana edaran</p>
           <table class="table">
                   <tr>
                       <th>Cetak</th>
                       <td>{{ $dist->print }}</td>
                       <th>Distribusi</th>
                       <td>{{ $dist->distributed }}</td>
                   </tr>
                   <tr>
                       <th>Gratis</th>
                       <td>{{ $dist->gratis }}</td>
                       <th>Stock</th>
                       <td>{{ $dist->stock }}</td>
                   </tr>
           </table>
       </div>
   </div>
   <div class="row">
       <div class="col-lg-8 col-lg-offset-2">
           <hr></hr>
           <p class="lead">Detail per agen</p>
           <table class="table table-striped">
               <thead>
                   <tr>
                       <th>No</th>
                       <th>Agent name</th>
                       <th>Quota</th>
                       <th>Consigned</th>
                       <th>Gratis</th>
                       <th></th>
                   </tr>
               </thead>
               <tbody>
               <?php $i=0; $sumquota=0; $sumcon=0; $sumgra=0?>
               @foreach($dist->details as $detail)
                    <tr>
                        <td>
                        <?php
                            echo 1 + $i; $i++
                        ?>
                        </td>
                        <td>
                            {{ $detail->agent->name }}
                        </td>
                        <td>
                            {{ $detail->quota }}
                        </td>
                        <td>
                            {{ $detail->consigned }}
                        </td>
                        <td>
                            {{ $detail->gratis }}
                        </td>
                        <td>
                            <a href="{{ $dist_id }}/details/{{ $detail->id }}/edit">Edit</a>
                            <a href="{{ $dist_id }}/details/{{ $detail->id }}">Delete</a>
                        </td>
                    </tr>
<?php 
                            $sumquota+=(int)$detail->quota;
                            $sumcon+=(int)$detail->consigned;
                            $sumgra+=(int)$detail->gratis;
?>
               @endforeach
               <tr>
                   <td colspan=2>
                       <p>Total:</p>
                   </td>
                   <td>
                       {{ $sumquota }}
                   </td>
                   <td>
                       {{ $sumcon }}
                   </td>
                   <td>
                       {{ $sumgra }}
                   </td>
                   <td>
                   </td>
               </tr>
               </tbody>

            
           </table>
       </div>
   </div>
</div>
@endsection
