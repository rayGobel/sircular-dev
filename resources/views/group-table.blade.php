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
                    <th></th>
                </tr>
            <?php $i = 1; ?>
            @foreach($groups as $group)
                <tr>
                    <td><?php echo $i; $i++ ?></td>
                    <td>{{ $group->name }}</td>
                    <td>
                        <a href="group/{{ $group->id}}">Delete</a>
                    </td>
                </tr>
            @endforeach
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <a class="btn btn-default" href="group/create" role="button">
                Create new
            </a>
        </div>
    </div>
</div>
@endsection
