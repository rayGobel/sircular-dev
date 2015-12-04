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
            <form action="/sircular-dev/public/circulation/distribution-plan{{ isset($distID) ? '/'.$distID : '' }}" method="POST">
                @if (isset($distPlan))
                <div class="form-group">
                    <label for="distPlan-mag">Magazine</label>
                    <select id="distPlan-mag" class="form-control" name="magazine_id" disabled>
                        <option value={{ $distPlan->edition->magazine->id }}>
                            {{$distPlan->edition->magazine->name}}
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="distPlan-edition">Edition</label>
                    <input type="text" class="form-control" name="edition_code" value="{{$distPlan->edition->edition_code}}" disabled id="distPlan-edition">
                </div>
                @else
                <div class="form-group">
                    <label for="distPlan-mag">Magazine</label>
                    <select id="distPlan-mag" class="form-control" name="magazine_id">
                        @foreach($magList as $mag)
                        <option value={{$mag->id}} >
                            {{$mag->name}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="distPlan-edition">Edition</label>
                    <input type="text" class="form-control" name="edition_code" value="" id="distPlan-edition">
                </div>
                @endif
                <div class="form-group">
                    <label for="distPlan-percentFee">% fee</label>
                    <input type="text" class="form-control" name="percent_fee" value="{{$distPlan->percent_fee or 0}}" id="distPlan-percentFee">
                </div>
                <div class="form-group">
                    <label for="distPlan-valueFee">value fee</label>
                    <input type="text" class="form-control" name="value_fee" value="{{$distPlan->value_fee or 0}}" id="distPlan-valueFee">
                </div>
                <div class="form-group">
                    <label for="distPlan-print">Plan Print</label>
                    <input type="text" class="form-control" name="print" value="{{$distPlan->print or ''}}" id="distPlan-print">
                </div>
                <div class="form-group">
                    <label for="distPlan-gratis">Plan Gratis</label>
                    <input type="text" class="form-control" name="gratis" value="{{$distPlan->gratis or ''}}" id="distPlan-gratis">
                </div>
                <div class="form-group">
                    <label for="distPlan-stock">Plan Stock</label>
                    <input type="text" class="form-control" name="stock" value="{{$distPlan->stock or ''}}" id="distPlan-stock">
                </div>
                <div class="form-group">
                    <label for="distPlan-distributed">Plan Distributed</label>
                    <input type="text" class="form-control" name="distributed" value="{{$distPlan->distributed or ''}}" id="distPlan-distributed">
                </div>
                <div class="form-group">
                    <label for="distPlan-publish">Publish Date</label>
                    <input type="text" class="form-control" name="publish_date" value="{{$distPlan->publish_date or ''}}" id="distPlan-publish">
                </div>
                <div class="form-group">
                    <label for="distPlan-printNum">Print number</label>
                    <input type="text" class="form-control" name="print_number" value="{{$distPlan->print_number or ''}}" id="distPlan-printNum">
                </div>
                <!-- Laravel CSRF Token and method SPOOFING -->
                @if (isset($method))
                <input type="hidden" name="_method" value="{{ $method }}">
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Save</button>
                    <a class="btn btn-default" href="/sircular-dev/public/circulation/distribution-plan">
                        <i class="fa fa-reply fa-fw"></i>
                        Return
                    </a>
                </div>
            </form>
        </div>
   </div>
</div>
@endsection
