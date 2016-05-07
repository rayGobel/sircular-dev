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
            <form action="{{ $form_action }}" method="POST">
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
                    <label for="delv-quota">Quota</label>
                    <input type="text" class="form-control" name="quota" value="{{$detail->quota or ''}}" id="delv-quota">
                </div>
                <div class="form-group">
                    <label for="delv-consigned">Consigned</label>
                    <input type="text" class="form-control" name="consigned" value="{{$detail->consigned or ''}}" id="delv-consigned">
                </div>
                <div class="form-group">
                    <label for="delv-gratis">Gratis</label>
                    <input type="text" class="form-control" name="gratis" value="{{$detail->gratis or ''}}" id="delv-gratis">
                </div>
                <div class="form-group">
                    <label for="delv-issueDate">Issue date</label>
                    <input type="text" class="form-control" name="date_issued" value="{{ date('d-m-Y')}}" id="delv-issueDate">
                    <p class="help-block">example (dd-mm-YYYY): <em>24-03-2015</em></p>
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
                    <a class="btn btn-default" href="{{ url('circulation/distribution-realization', $distRealizationID) }}">
                        <i class="fa fa-reply fa-fw"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
   </div>
</div>
@endsection
