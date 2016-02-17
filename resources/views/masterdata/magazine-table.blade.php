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
                    <th>Nama Majalah</th>
                    <th>Penerbit</th>
                    <th>Periode</th>
                    <th>Harga</th>
                    <th>% Fee</th>
                    <th>Nilai Fee</th>
                    <th></th>
                </tr>
            <?php $i = 0; ?>
            @foreach($magazines as $magazine)
                <tr>
                    <td><?php echo $magazines->firstItem() + $i; $i++ ?></td>
                    <td>{{ $magazine->name }}</td>
                    <td>{{ $magazine->publisher->name }}</td>
                    <td>{{ $magazine->period }}</td>
                    <td class="monetary-fmt">{{ $magazine->price }}</td>
                    <td>{{ $magazine->percent_fee }}</td>
                    <td>{{ $magazine->percent_value }}</td>
                    <td>
                        <a href="magazine/{{$magazine->id}}/edit">Edit</a>
                        
                        <a href="magazine/{{ $magazine->id}}">Delete</a>
                    </td>
                </tr>
            @endforeach
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            {!! $magazines->render() !!}
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <a class="btn btn-default" href="magazine/create" role="button">
                Create new
            </a>
        </div>
    </div>
</div>
@endsection
