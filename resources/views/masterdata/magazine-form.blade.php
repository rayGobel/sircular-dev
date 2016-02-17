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
            <form action="/sircular-dev/public/masterdata/magazine{{ isset($magz_id) ? '/'.$magz_id : '' }}" method="POST">
                <div class="form-group">
                   <label for="magz-name">Magazine name</label>
                   <input type="text" class="form-control" 
                          id="magz-name" name="name" 
                          value="{{$magazine->name or ''}}">
                    <p class="help-block"><em>e.g. COSMOPOLITAN-KCL; BAZAAR; MOTHER & BABY</em></p>
                </div>
                <div class="form-group">
                    <label for="magz-pub">Publisher</label>
                    <select id="magz-pub" class="form-control" name="publisher_id">
                        @foreach($publishers as $publ)
                        <option value={{$publ->id}} {{ isset($magazine) && ($publ->id == $magazine->publisher_id) ? 'selected="selected"'  : '' }}>
                            {{$publ->name}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="magz-period">Period</label>
                    <input type="text" class="form-control" name="period"
                           value="{{$magazine->period or ''}}"
                           id="magz-period">
                    <p class="help-block"><em>e.g. BULANAN; MINGGUAN; 2 MINGGUAN;</em></p>
                </div>
                <div class="form-group">
                    <label for="magz-price">Price</label>
                    <div class="input-group">
                        <div class="input-group-addon">Rp.</div>
                        <input type="text" class="form-control" name="price"
                               value="{{$magazine->price or 0 }}"
                               id="magz-price">
                        <div class="input-group-addon">,00</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="magz-fee">% Fee</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="percent_fee"
                               value="{{$magazine->percent_fee or '0' }}"
                               id="magz-fee">
                        <div class="input-group-addon">%</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="magz-value">% Value</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="percent_value"
                               value="{{$magazine->percent_value or '0' }}"
                               id="magz-value">
                        <div class="input-group-addon">%</div>
                    </div>
                </div>
                <!-- Laravel CSRF Token and method SPOOFING -->
                @if (isset($method))
                <input type="hidden" name="_method" value="{{ $method }}">
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Save</button>
                    <a class="btn btn-default" href="/sircular-dev/public/masterdata/magazine">
                        <i class="fa fa-reply fa-fw"></i>
                        Return
                    </a>
                </div>
            </form>
        </div>
   </div>
</div>
@endsection
