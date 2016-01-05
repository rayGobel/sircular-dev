@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">


            <table class="table">
                <tr>
                    <th>Agent Name</th>
                    <th>Planned Quota</th>
                    <th>Distributed Quota</th>
                    <th>Planned Consigned</th>
                    <th>Distributed Consigned</th>
                    <th>Planned Gratis</th>
                    <th>Distributed Gratis</th>
                </tr>
                @foreach($agents as $agent)
                <?php $i = $agent->id ?>
                <tr>
                    <td>{{ $agent->name }}</td>
                    <td>{{ $distPlanDet[$i]->quota }}</td>
                    <td>{{ $distRealDet[$i]->quota }}</td>
                    <td>{{ $distPlanDet[$i]->consigned }}</td>
                    <td>{{ $distRealDet[$i]->consigned }}</td>
                    <td>{{ $distPlanDet[$i]->gratis }}</td>
                    <td>{{ $distRealDet[$i]->gratis }}</td>
                </tr>
                @endforeach
            </table>

        </div>
    </div>
</div>

@endsection
