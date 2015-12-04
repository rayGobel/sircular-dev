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
            <form action="/sircular-dev/public/masterdata/agent{{ isset($agent_id) ? '/'.$agent_id : '' }}" method="POST">
                <div class="form-group">
                   <label for="agent-name">Name</label>
                   <input type="text" class="form-control" 
                          id="agent-name" name="name" 
                          value="{{$agent->name or ''}}">
                </div>
                <div class="form-group">
                   <label for="agent-city">City</label>
                   <input type="text" class="form-control" 
                          id="agent-city" name="city" 
                          value="{{$agent->city or ''}}">
                </div>
                <div class="form-group">
                    <label for="agent-cat">Agent Category</label>
                    <select id="agent-cat" class="form-control" name="agent_category_id">
                        @foreach($agent_cat as $cat)
                        <option value={{$cat->id}} {{ isset($agent) && ($cat->id == $agent->agent_category_id) ? 'selected="selected"'  : '' }}>
                            {{$cat->name}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="agent-phone">Phone</label>
                    <input type="text" class="form-control" name="phone" value="{{$agent->phone or ''}}" id="agent-phone">
                </div>
                <div class="form-group">
                    <label for="agent-contact">Contact</label>
                    <input type="text" class="form-control" name="contact" value="{{$agent->contact or ''}}" id="agent-contact">
                </div>
                <!-- Laravel CSRF Token and method SPOOFING -->
                @if (isset($method))
                <input type="hidden" name="_method" value="{{ $method }}">
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Save</button>
                    <a class="btn btn-default" href="/sircular-dev/public/masterdata/agent">
                        <i class="fa fa-reply fa-fw"></i>
                        Return
                    </a>
                </div>
            </form>
        </div>
   </div>
</div>
@endsection
