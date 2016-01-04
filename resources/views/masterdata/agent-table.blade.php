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
                    <th>Kategori</th>
                    <th>Telepon</th>
                    <th>Kontak</th>
                    <th></th>
                </tr>
            <?php $i = 0; ?>
            @foreach($agents as $agent)
                <tr>
                    <td><?php echo $agents->firstItem() + $i; $i++ ?></td>
                    <td>{{ $agent->name }}</td>
                    <td>{{ $agent->city }}</td>
                    <td>{{ $agent->agent_category->name }}</td>
                    <td>{{ $agent->phone }}</td>
                    <td>{{ $agent->contact }}</td>
                    <td>
                        <a href="agent/relationship/{{$agent->id}}">Majalah</a>

                        <a href="agent/{{$agent->id}}/edit">Edit</a>
                        
                        <a href="agent/{{ $agent->id}}">Delete</a>
                    </td>
                </tr>
            @endforeach
            </table>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-12">
            {!! $agents->render() !!}
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <a class="btn btn-default" href="agent/create" role="button">
                Create new
            </a>
        </div>
    </div>
</div>
@endsection
