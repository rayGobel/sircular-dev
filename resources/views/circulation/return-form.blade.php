@extends('app')

@section('content')
<div class="container">
   @foreach($errors->all() as $error)
        <div class="alert alert-danger" role="alert">
            <p>{{ $error }}</p>
        </div>
   @endforeach
   <div class="row">
        <div class="col-lg-8">
            <form action="/sircular-dev/public/circulation/return/add-edition-detail" method="POST">
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                <label for="return-item-count">How many edition</label>
                <input type="text" class="form-control" name="return_item_count" value="{{ old('return_item_count') }}" id="return-item-count">
                </div>
                <button type="submit" class="btn btn-default">
                    Submit
                </button>
            </form>
            <hr></hr>
            <form action="/sircular-dev/public/circulation/return" method="POST">
                <input type="hidden" class="form-control" name="return_item_count" value="{{ old('return_item_count') }}" >
                <div class="form-group">
                    <label for="return-number">Return #</label>
                    <input type="text" class="form-control" name="number" value="{{ old('number') }}"  id="return-number">
                </div>
                <div class="form-group">
                    <label for="return-date">Return date</label>
                    <input type="text" class="form-control" name="date" value="{{ old('date')  }}" id="return-Date">
                    <p class="help-block">example (dd-mm-YYYY) <em>24-03-2015</em></p>
                </div>
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
                @for ($i = 0; $i < old('return_item_count'); $i++)
                    <p>Entry #{{$i+1}}</p>
                <div class="row">
                    <div class="col-lg-7 col-lg-offset-1">
                        <div class="form-group">
                            <label for="return-edition">Edisi Majalah</label>
                            <select id="dist-detail-cat" class="form-control" name="edition_id[{{$i}}]">
                                @foreach($editions as $ed)
                                <option value={{$ed->id}}>
                                    {{$ed->magazine->name}}
                                    {{$ed->edition_code}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="delivery-total">Total returned item</label>
                            <input type="text" class="form-control" name="total[{{$i}}]" value="{{ old('total') }}"  id="delivery-total">
                        </div>
                    </div>
                </div>
                @endfor
                <hr></hr>
                <!-- Requesting multiple form_count -->
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
