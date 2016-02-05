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
                    <th>Nomor Invoice</th>
                    <th>Nama Agen</th>
                    <th>Nama Majalah</th>
                    <th>Tanggal pembuatan</th>
                    <th>Tanggal jatuh tempo</th>
                    <th></th>
                </tr>
            <?php $i = 0; ?>
            @foreach($invoices as $invoice)
                <tr>
                    <td><?php echo $invoices->firstItem() + $i; $i++ ?></td>
                    <td>{{ $invoice->number }}</td>
                    <td>{{ $invoice->agent->name }}</td>
                    <td>{{ $invoice->edition->magazine->name }}</td>
                    <td>{{ $invoice->issue_date }}</td>
                    <td>{{ $invoice->due_date }}</td>
                    <td>
                        <a href="invoiceQuota/{{$invoice->id}}">Detail</a>
                    </td>
                </tr>
            @endforeach
            </table>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-12">
            {!! $invoices->render() !!}
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <a class="btn btn-default" href="invoiceQuota/create" role="button">
                Create new
            </a>
        </div>
    </div>
</div>
@endsection
