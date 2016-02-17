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
            <form action="/sircular-dev/public/masterdata/edition{{ isset($edition_id) ? '/'.$edition_id : '' }}" method="POST">
                <div class="form-group">
                   <label for="edition-code">Edition code</label>
                   <input type="text" class="form-control" 
                          id="edition-code" name="edition_code" 
                          value="{{$edition->edition_code or ''}}">
                    <p class="help-block"><em>e.g. 111/MARET/15; 01/SEP/2016; 52-JAN-16;</em></p>
                </div>
                <div class="form-group">
                    <label for="edition-magz">Magazine name</label>
                    <select id="edition-magz" class="form-control" name="magazine_id">
                        @foreach($magazines as $mag)
                        <option value={{$mag->id}} {{ isset($edition) && ($mag->id == $edition->magazine_id) ? 'selected="selected"'  : '' }}>
                            {{$mag->name}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="edition-article">Main article</label>
                    <input type="text" class="form-control" name="main_article" value="{{$edition->main_article or ''}}" id="edition-article">
                </div>
                <div class="form-group">
                    <label for="edition-cover">Cover</label>
                    <input type="text" class="form-control" name="cover" value="{{$edition->cover or ''}}" id="edition-cover">
                </div>
                <div class="form-group">
                    <label for="edition-price">Price</label>
                    <div class="input-group">
                        <div class="input-group-addon">Rp.</div>
                        <input type="text" class="form-control" name="price"
                               value="{{$edition->price or 0 }}"
                               id="edition-price">
                        <div class="input-group-addon">.00</div>
                    </div>
                </div>
                <!-- Laravel CSRF Token and method SPOOFING -->
                @if (isset($method))
                <input type="hidden" name="_method" value="{{ $method }}">
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Save</button>
                    <a class="btn btn-default" href="/sircular-dev/public/masterdata/edition">
                        <i class="fa fa-reply fa-fw"></i>
                        Return
                    </a>
                </div>
            </form>
        </div>
   </div>
</div>
@endsection
