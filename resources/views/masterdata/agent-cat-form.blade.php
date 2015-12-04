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
            <form action="/sircular-dev/public/masterdata/agent-cat{{ isset($agent_cat_id) ? '/'.$agent_cat_id : '' }}" method="POST">
                <div class="form-group">
                   <label for="agent-cat-name">Nama</label>
                   <input type="text" class="form-control" 
                          id="agent-cat-name" name="name" 
                          value="{{$agent_cat->name or ''}}">
                </div>
                <div class="form-group">
                   <label for="agent-category-desc">Keterangan</label>
                   <textarea class="form-control"
                             id="agent-category-desc" 
                             rows="4"
                             name="description">{{ $agent_cat->description or '' }}</textarea>
                </div>
                <!-- Laravel CSRF Token and method SPOOFING -->
                @if (isset($method))
                <input type="hidden" name="_method" value="{{ $method }}">
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Save</button>
                    <a class="btn btn-default" href="/sircular-dev/public/masterdata/agent-cat">
                        <i class="fa fa-reply fa-fw"></i>
                        Return
                    </a>
                </div>
            </form>
        </div>
   </div>
</div>
@endsection

