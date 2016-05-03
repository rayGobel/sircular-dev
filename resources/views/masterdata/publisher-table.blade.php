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
                    <th>Nama</th>
                    <th>Kota</th>
                    <th>Provinsi</th>
                    <th>Telepon</th>
                    <th>Kontak</th>
                    <th></th>
                </tr>
            <?php $i = 0; ?>
            @foreach($publishers as $publisher)
                <tr>
                    <td><?php echo $publishers->firstItem() + $i; $i++ ?></td>
                    <td>{{ $publisher->name }}</td>
                    <td>{{ $publisher->city }}</td>
                    <td>{{ $publisher->province }}</td>
                    <td>{{ $publisher->phone }}</td>
                    <td>{{ $publisher->contact }}</td>
                    <td>
                        <a href="{{ action('PublisherController@edit', $publisher->id) }}">Edit</a>
                        
                        <a href="{{ action('PublisherController@show', $publisher->id) }}">Delete</a>
                    </td>
                </tr>
            @endforeach
            </table>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-12">
            {!! $publishers->render() !!}
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <a class="btn btn-default" href="{{ action('PublisherController@create') }}" role="button">
                Create new
            </a>
        </div>
    </div>
</div>
@endsection
