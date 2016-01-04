@extends('app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            @if (Session::has('message'))
            <div class="alert alert-info" role="alert">
                <p>{{ Session::get('message') }}</p>
            </div>
            @endif
            @if (Session::has('errMsg'))
            <div class="alert alert-danger" role="alert">
                    <p>{{ Session::get('errMsg') }}</p>
            </div>
            @endif
        </div><!-- container.row.col-lg-12 -->
    </div><!-- container.row -->


    <div class="row">
        <div class="col-lg-12">
            <p class="lead">Majalah per agen</p>
            <table class="table">
                <tr>
                    <td>Nama Agen:</td>
                    <td>{{ $agent->name }}</td>
                </tr>
                <tr>
                    <td>Kota:</td>
                    <td>{{ $agent->city }}</td>
                </tr>
                <tr>
                    <td>Kategori:</td>
                    <td>{{ $agent->agent_category->name }}</td>
                </tr>
            </table>

            <p class="lead">Korelasi majalah</p>
            <table class="table">
                <tr>
                    <th>Nama majalah</th>
                </tr>
                @foreach ($agent->magazine as $magazine)
                <tr>
                    <td>{{ $magazine->name }}</td>
                </tr>
                @endforeach
            </table>

        </div><!-- container.row.col-lg-12 -->
    </div><!-- container.row -->

    <div class="row">
        <div class="col-lg-10 col-lg-1-offset">
            <form action="{{ url('masterdata/agent') }}/create-relationship" method="POST">
                @if (isset($method))
                <input type="hidden" name="_method" value="{{ $method }}">
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="agent_id" value="{{ $agent->id }}">
                <div class="form-group">
                    <label for="new-magazine-rel">Majalah</label>
                    <select id="new-magazine-rel" class="form-control" name="magazine_id">
                        @foreach($unselected_mags as $mag)
                        <option value={{ $mag->id }}>{{ $mag->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Tambah</button>
                    <a class="btn btn-default" href="{{ url('masterdata/agent') }}">
                        <i class="fa fa-reply fa-fw"></i>
                        Return
                    </a>
                </div>
            </form>
        </div><!-- container.row.col-lg-10... -->
    </div><!-- container.row -->

</div><!-- container -->

@endsection
