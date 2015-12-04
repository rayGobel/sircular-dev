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
           @if (isset($detail))
           <p>Majalah {{$detail->distributionPlan->edition->magazine->name}}, edisi {{$detail->distributionPlan->edition->edition_code}}</p>
           @endif
       </div>
   </div>
   <div class="row">
        <div class="col-lg-6">
            <form action="/sircular-dev/public/circulation/distribution-plan/{{ $distPlanID }}/details{{ isset($detail) ? '/'.$detail->id : '' }}" method="POST">
                <div class="form-group">
                    <label for="dist-detail-cat">Agency</label>
                    @if (isset($detail->agent->id))
                    <select id="dist-detail-cat" class="form-control disabled" name="agent_id" disabled>
                        <option selected value={{$detail->agent->id}}>
                            {{$detail->agent->name}}
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
                    <label for="dist-detail-quota">Quota</label>
                    <input type="text" class="form-control" name="quota" value="{{$detail->quota or ''}}" id="dist-detail-quota">
                </div>
                <div class="form-group">
                    <label for="dist-detail-consigned">Consigned</label>
                    <input type="text" class="form-control" name="consigned" value="{{$detail->consigned or ''}}" id="dist-detail-consigned">
                </div>
                <div class="form-group">
                    <label for="dist-detail-gratis">Gratis</label>
                    <input type="text" class="form-control" name="gratis" value="{{$detail->gratis or ''}}" id="dist-detail-gratis">
                </div>
                <!-- Laravel CSRF Token and method SPOOFING -->
                @if (isset($method))
                <input type="hidden" name="_method" value="{{ $method }}">
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Save</button>
                    <a class="btn btn-default" href="/sircular-dev/public/circulation/distribution-plan/{{ $distPlanID }}">
                        <i class="fa fa-reply fa-fw"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
   </div>
</div>
@endsection
