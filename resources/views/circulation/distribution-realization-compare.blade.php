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
       </div><!-- container.row.col-lg-12 -->
    </div><!-- container.row -->

    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">

            <p class="lead">
            <strong>Perbandingan antara rencana dengan realisasi kirim</strong>
            </p>

            <table class="table table-bordered">
                <tr>
                    <th rowspan="2">Nama Agen</th>
                    <th colspan="2">Quota</th>
                    <th colspan="2">Konsinyasi</th>
                    <th colspan="2">Gratis</th>
                </tr>
                <tr>
                    <th>Rencana</th>
                    <th>Realisasi</th>
                    <th>Rencana</th>
                    <th>Realisasi</th>
                    <th>Rencana</th>
                    <th>Realisasi</th>
                </tr>
            @foreach($agent_plan as $detail)
            <?php $i = $detail->agent->id ?>
                <tr>
                    <td>{{ $detail->agent->name }}</td>
                    <td>{{ $detail->quota }}</td>
                    <td>{{ $agent_real[$i]->quota }}</td>
                    <td>{{ $detail->consigned }}</td>
                    <td>{{ $agent_real[$i]->consigned }}</td>
                    <td>{{ $detail->gratis }}</td>
                    <td>{{ $agent_real[$i]->gratis }}</td>

                </tr>
            @endforeach

            </table>

        </div><!-- container.row.col-lg-4... -->
    </div><!-- container.row -->

</div><!-- container -->
@endsection
