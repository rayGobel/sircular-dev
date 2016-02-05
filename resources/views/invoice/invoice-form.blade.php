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
            <form action="/sircular-dev/public/invoice/invoice{{ $invType or 'Consign' }}{{ isset($invoice_id) ? '/'.$invoice_id : '' }}" method="POST">
                <p class='lead'>This will guide you to build invoice</p>
                <div class="form-group">
                    <label for="agent-id">Agent name</label>
                    <select id="agent-id" class="form-control" name="agent_id">
                        @foreach($agents as $ag)
                        <option value={{$ag->id}}>
                            {{$ag->name}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="edition-id">Edition</label>
                    <select id="edition-id" class="form-control" name="edition_id">
                        @foreach($eds as $ed)
                        <option value={{$ed->id}}>
                            {{$ed->magazine->name}} {{$ed->edition_code}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="issue-date">Issue date</label>
                    <input type="text" class="form-control" name="issue_date" value="{{ date('d-m-Y') }}" id="issue-date">
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
                    <a class="btn btn-default" href="/sircular-dev/public/invoice/invoice">
                        <i class="fa fa-reply fa-fw"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
   </div>
</div>
@endsection
