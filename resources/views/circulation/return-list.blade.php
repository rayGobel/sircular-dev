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
            <table class="table table-hover">
                <tr>
                    <th>No</th>
                    <th>Edisi</th>
                    <th>Nama agen</th>
                    <th>#Retur</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th></th>
                </tr>
            <?php $i = 0; ?>
            @foreach($returnList as $rts)
                <tr>
                    <td><?php echo $returnList->firstItem() + $i; $i++ ?></td>
                    <td>
                        {{ $rts->distRealizationDet->distributionRealization->edition->magazine->name }} -
                        {{ $rts->distRealizationDet->distributionRealization->edition->edition_code }}
                    </td>
                    <td>{{ $rts->distRealizationDet->agent->name }}</td>
                    <td>{{ $rts->number }}</td>
                    <td>{{ $rts->date }}</td>
                    <td>{{ $rts->total }}</td>
                    <td>
                        <a href="return/{{ $rts->id}}">Print/Delete</a>
                    </td>
                </tr>
            @endforeach
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="return/create" class="btn btn-default">
                Create
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            {!! $returnList->render() !!}
        </div>
    </div>
</div>
@endsection
