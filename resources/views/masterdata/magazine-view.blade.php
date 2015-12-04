@extends('app')

@section('content')
<div class="container">
   <div class="row">
       <div class="col-lg-6">
           <p class="lead">
           <strong>Are you sure you want to delete records of </strong>
           {{ $magazine->name }} <strong>?</strong>
           </p>
       </div>
   </div>
   <div class="row">
       <div class="col-lg-6">
           <h2>Details</h2>
           <table>
               <tbody>
                   <tr>
                       <td>Magazine Name</td>
                       <td>:</td>
                       <td>{{ $magazine->name }}</td>
                   </tr>
                   <tr>
                       <td>Publisher Name</td>
                       <td>:</td>
                       <td>{{ $magazine->publisher->name }}</td>
                   </tr>
                   <tr>
                       <td>Period</td>
                       <td>:</td>
                       <td>{{ $magazine->period }}</td>
                   </tr>
                   <tr>
                       <td>Price</td>
                       <td>:</td>
                       <td>Rp. {{ $magazine->price }},-</td>
                   </tr>
               </tbody>
           </table>

       </div>
   </div>
   <div class="row">
       <div class="col-lg-6">
           <hr></hr>
           <form action="/sircular-dev/public/masterdata/magazine/{{ $magazine->id }}" method="POST">
               <input type="hidden" name="_method" value="DELETE">
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <div class="form-group">
                   <button type="submit" class="btn btn-danger">Delete</button>
                    <a class="btn btn-default" href="/sircular-dev/public/masterdata/magazine">
                        <i class="fa fa-reply fa-fw"></i>
                        Cancel
                    </a>
               </div>
           </form>
       </div>
   </div>
</div>
@endsection
