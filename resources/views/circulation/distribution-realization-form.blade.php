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
                    <label for="distPlan">Distribution Plans</label>
                    <select id="distPlan" class="form-control" name="dist_plan_id">
                        @foreach($dist_plans as $dist_plan)
                        <option value={{ $dist_plan->id }}>
                        {{ $dist_plan->edition->magazine->name }} {{ $dist_plan->edition->edition_code }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <!-- Laravel CSRF Token and method SPOOFING -->
                @if (isset($method))
                <input type="hidden" name="_method" value="{{ $method }}">
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Save</button>
                    <a class="btn btn-default" href="{{ url('circulation/distribution-realization') }}">
                        <i class="fa fa-reply fa-fw"></i>
                        Return
                    </a>
                </div>
            </form>
        </div>
   </div>
</div>
@endsection

