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
                    <th>Magazine</th>
                    <th>Edition</th>
                    <th>Publish date</th>
                    <th>Total prints</th>
                    <th></th>
                </tr>
            <?php $i = 0; ?>
            @foreach($dist_plans as $dist_plan)
                <tr>
                    <td><?php echo $dist_plans->firstItem() + $i; $i++ ?></td>
                    <td>{{ $dist_plan->edition->magazine->name }}</td>
                    <td>{{ $dist_plan->edition->edition_code }}</td>
                    <td>{{ $dist_plan->publish_date }}</td>
                    <td>{{ $dist_plan->print }}</td>
                    <td>
                        <a href="distribution-plan/{{$dist_plan->id}}/edit">Edit</a>
                        
                        <a href="distribution-plan/{{ $dist_plan->id}}">Details</a>
                    </td>
                </tr>
            @endforeach
            </table>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-12">
            {!! $dist_plans->render() !!}
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <a class="btn btn-default" href="distribution-plan/create" role="button">
                Create new
            </a>
        </div>
    </div>
</div>
@endsection
