@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">

            <table class="table table-stripped" id="report-header">
                <tr>
                    <td rowspan="5">MRA Printed</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>{{ date('l, j F Y') }}</td>
                </tr>
                <tr>
                    <td>Majalah</td>
                    <td>{{ $distReal->edition->magazine->name }}</td>
                </tr>
                <tr>
                    <td>Edisi</td>
                    <td>{{ $distReal->edition->edition_code }}</td>
                </tr>
                <tr>
                    <td>Tanggal Edar</td>
                    <?php //This is a possible bug incase there are difference between distPlan and distReal publish_date ?>
                    <td>{{ date('l, j F Y', strtotime($distReal->publish_date)) }}</td>
                </tr>
            </table>


            <table class="table">
                <tr>
                    <th>No</th>
                    <th></th>
                    <th>Agent Name</th>
                    <th>Planned Quota</th>
                    <th>Distributed Quota</th>
                    <th>Planned Consigned</th>
                    <th>Distributed Consigned</th>
                    <th>Planned Gratis</th>
                    <th>Distributed Gratis</th>
                </tr>
                <?php $count = 1; $cur_agent_cat = 0 ?>
                @foreach($agents as $agent)
                <?php $i = $agent->id; ?>
                <tr>
                    <td>{{ $count }}</td>
                    <td>{{ $agent->city }}</td>
                    <td>{{ $agent->name }}</td>
                    <td>{{ $distPlanDet[$i]->quota }}</td>
                    <td>{{ $distRealDet[$i]->quota }}</td>
                    <td>{{ $distPlanDet[$i]->consigned }}</td>
                    <td>{{ $distRealDet[$i]->consigned }}</td>
                    <td>{{ $distPlanDet[$i]->gratis }}</td>
                    <td>{{ $distRealDet[$i]->gratis }}</td>
                </tr>
                <?php $count += 1 ?>
                @endforeach
            </table>

        </div>
    </div>
</div>

@endsection
