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
                    <th>Majalah</th>
                    <th>Nama agen</th>
                    <th>DO#</th>
                    <th>Tanggal</th>
                    <th>Jatah</th>
                    <th>Konsinyasi</th>
                    <th>Gratis</th>
                    <th></th>
                </tr>
            <?php $i = 0; ?>
            @foreach($deliveryLists as $dlv)
                <tr>
                    <td><?php echo $deliveryLists->firstItem() + $i; $i++ ?></td>
                    <td>{{ $dlv->distRealizationDet->distributionRealization->edition->magazine->name }}
                    {{ $dlv->distRealizationDet->distributionRealization->edition->edition_code }}</td>
                    <td>{{ $dlv->distRealizationDet->agent->name }}</td>
                    <td>{{ $dlv->order_number }}</td>
                    <td>{{ $dlv->date_issued }}</td>
                    <td>{{ $dlv->quota }}</td>
                    <td>{{ $dlv->consigned }}</td>
                    <td>{{ $dlv->gratis }}</td>
                    <td>
                        <a href="/sircular-dev/public/circulation/distribution-realization/{{ $dlv->distRealizationDet->distributionRealization->id }}/details/{{ $dlv->distRealizationDet->id }}/delivery/{{ $dlv->id}}">Print/Delete</a>
                    </td>
                </tr>
            @endforeach
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            {!! $deliveryLists->render() !!}
        </div>
    </div>
    @if ($createButton==true)
    <div class="row">
        <div class="col-lg-12">
            <a class="btn btn-default" href="delivery/create" role="button">
                Create new
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
