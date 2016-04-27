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
            <form class="form-inline" action="{{ action('EditionController@postBeginFilter') }}" method="POST">
                <div class="form-group">
                    <select class="form-control" id="filter-magazine" name="magazine_id">
                        @foreach ($magazines as $mag)
                        <option value={{$mag->id}}>{{$mag->name}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-default">Filter</button>
            </form>
            <hr></hr>
            <table class="table table-hover">
                <tr>
                    <th>No</th>
                    <th>Majalah</th>
                    <th>Kode</th>
                    <th>Cover</th>
                    <th>Artikel Utama</th>
                    <th>Harga</th>
                    <th></th>
                </tr>
            <?php $i = 0; ?>
            @foreach($editions as $edition)
                <tr>
                    <td><?php echo $editions->firstItem() + $i; $i++ ?></td>
                    <td>{{ $edition->magazine->name }}</td>
                    <td>{{ $edition->edition_code }}</td>
                    <td>{{ $edition->cover }}</td>
                    <td>{{ $edition->main_article }}</td>
                    <td class="monetary-fmt">{{ $edition->price }}</td>
                    <td>
                        <a href="{{ url('masterdata/edition') }}/{{$edition->id}}/edit">Edit</a>
                        
                        <a href="{{ url('masterdata/edition') }}/{{ $edition->id}}">Delete</a>
                    </td>
                </tr>
            @endforeach
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            {!! $editions->render() !!}
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <a class="btn btn-default" href="{{ url('masterdata/edition/create') }}" role="button">
                Create new
            </a>
        </div>
    </div>
</div>
@endsection
