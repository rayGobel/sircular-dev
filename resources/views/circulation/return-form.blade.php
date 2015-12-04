@extends('app')

@section('content')
<div class="container">
   @foreach($errors->all() as $error)
        <div class="alert alert-danger" role="alert">
            <p>{{ $error }}</p>
        </div>
   @endforeach
   <div class="row">
        <div class="col-lg-6">
            <form action="/sircular-dev/public/circulation/return" method="POST">
                <div class="form-group">
                    <label for="return-agent">Agent Name</label>
                    @if (isset($detail->distPlanDet->agent->id))
                    <select id="return-agent" class="form-control disabled" name="agent_id" disabled>
                        <option selected value={{$detail->distPlanDet->agent->id}}>
                            {{$detail->distPlanDet->agent->name}}
                        </option>
                    </select>
                    @else
                    <select id="dist-detail-cat" class="form-control" name="agent_id">
                        @foreach($agents as $agent)
                        <option value={{$agent->id}}>
                            {{$agent->name}}
                        </option>
                        @endforeach
                    </select>
                    @endif
                </div>
                <div class="form-group">
                    <label for="return-edition">Edisi Majalah</label>
                    @if (isset($detail->distPlanDet->distributionPlan->edition->id))
                    <select id="return-magazine" class="form-control disabled" name="edition_id" disabled>
                        <option selected value={{$detail->distPlanDet->distributionPlan->edition->id}}>
                            {{$detail->distPlanDet->distributionPlan->edition->magazine->name}}
                            {{$detail->distPlanDet->distributionPlan->edition->edition_code}}
                        </option>
                    </select>
                    @else
                    <select id="dist-detail-cat" class="form-control" name="edition_id">
                        @foreach($editions as $ed)
                        <option value={{$ed->id}}>
                            {{$ed->magazine->name}}
                            {{$ed->edition_code}}
                        </option>
                        @endforeach
                    </select>
                    @endif
                </div>
                <div class="form-group">
                    <label for="delivery-total">Total returned item</label>
                    <input type="text" class="form-control" name="total" value="{{ $detail->total or ''}}"  id="delivery-total">
                </div>
                <div class="form-group">
                    <label for="return-date">Return date</label>
                    <input type="text" class="form-control" name="date" value="{{ date('d-m-Y')}}" id="return-Date">
                    <p class="help-block">example (dd-mm-YYYY) <em>24-03-2015</em></p>
                </div>
                <!-- Laravel CSRF Token and method SPOOFING -->
                @if (isset($method))
                <input type="hidden" name="_method" value="{{ $method }}">
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <button type="submit" class="btn btn-default">
                        <i class="fa fa-pencil fa-fw"></i>
                        Create
                    </button>
                    <a class="btn btn-default" href="/sircular-dev/public/circulation/return">
                        <i class="fa fa-reply fa-fw"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
   </div>
</div>
@endsection
