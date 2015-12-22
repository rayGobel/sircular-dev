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
                    <th>Edition</th>
                    <th>Publish date</th>
                    <th>Total prints</th>
                    <th></th>
                </tr>
            <?php $i = 0; ?>
            @foreach($dist_reals as $dist_real)
                <tr>
                    <td><?php echo $dist_reals->firstItem() + $i; $i++ ?></td>
                    <td>
                        <a href="distribution-plan/{{ $dist_real->distribution_plan_id }}">
                        {{ $dist_real->edition->magazine->name }} edisi {{ $dist_real->edition->edition_code }}
                        </a>
                    </td>
                    <td>{{ $dist_real->publish_date }}</td>
                    <td>{{ $dist_real->print }}</td>
                    <td>
                        <a href="distribution-realization/{{ $dist_real->id}}">Details</a>
                    </td>
                </tr>
            @endforeach
            </table>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-12">
            {!! $dist_reals->render() !!}
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <a class="btn btn-default" href="distribution-realization/create" role="button">
                Realize!
            </a>
        </div>
    </div>
</div>
@endsection
