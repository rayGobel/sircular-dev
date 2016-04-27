@extends('app')

@section('content')
<div class="container">
   <div class="row">
        <div class="col-lg-6">
            <form action="{{ $form_action }}" method="POST">
                <div class="form-group">
                   <label for="pub-name">Publisher name</label>
                   <input type="text" class="form-control" 
                          id="pub-name" name="name" 
                          value="{{ $publisher->name or ''}}">
                    <p class="help-block"><em>e.g. PT. MEDIA MITRA BUNDA</em></p>
                </div>
                <div class="form-group">
                    <label for="pub-city">City</label>
                    <select class="form-control" name="city">
                        <option selected value="Jakarta">Jakarta</option>
                        <option value="Bandung">Bandung</option>
                        <option value="Surabaya">Surabaya</option>
                        <option value="Banjarmasin">Banjarmasin</option>
                        <option value="Irian Jaya">Irian Jaya</option>
                        <option value="Gorontalo">Gorontalo</option>
                        <option value="Bogor">Bogor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pub-province">Province</label>
                    <input type="text" class="form-control" name="province"
                           value="{{$publisher->province or ''}}"
                           id="pub-province">
                    <p class="help-block"><em>e.g. DKI JAKARTA</em></p>
                </div>
                <div class="form-group">
                    <label for="pub-phone">Phone number</label>
                    <input type="text" class="form-control" name="phone"
                           value="{{$publisher->phone or '' }}"
                           id="pub-phone">
                    <p class="help-block"><em>e.g. 021-8776548; +62 811 88726112</em></p>
                </div>
                <div class="form-group">
                    <label for="pub-phone">Available contact</label>
                    <input type="text" class="form-control" name="contact"
                           value="{{$publisher->contact or '' }}"
                           id="pub-contact">
                </div>
                <!-- Laravel CSRF Token and method SPOOFING -->
                @if (isset($method))
                <input type="hidden" name="_method" value="{{ $method }}">
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Save</button>
                    <a class="btn btn-default" href="{{ action('PublisherController@index') }}">
                        <i class="fa fa-reply fa-fw"></i>
                        Return
                    </a>
                </div>
            </form>
        </div>
   </div>
</div>
@endsection
