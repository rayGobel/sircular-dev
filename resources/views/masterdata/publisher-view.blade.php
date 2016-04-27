@extends('app')

@section('content')
<div class="container">
   <div class="row">
       <div class="col-lg-6">
           <p class="lead">
           <strong>Are you sure you want to delete records of </strong>
           {{ $publisher->name }} <strong>?</strong>
           </p>
       </div>
   </div>
   <div class="row">
       <div class="col-lg-6">
           <h2>Details</h2>
           <table>
               <tbody>
                   <tr>
                       <td>Publisher Name</td>
                       <td>:</td>
                       <td>{{ $publisher->name }}</td>
                   </tr>
                   <tr>
                       <td>City and province</td>
                       <td>:</td>
                       <td>{{ $publisher->city }}, {{ $publisher->province }}</td>
                   </tr>
                   <tr>
                       <td>Phone number</td>
                       <td>:</td>
                       <td>{{ $publisher->phone }}</td>
                   </tr>
                   <tr>
                       <td>Available contact</td>
                       <td>:</td>
                       <td>{{ $publisher->contact }}</td>
                   </tr>
               </tbody>
           </table>

       </div>
   </div>
   <div class="row">
       <div class="col-lg-6">
           <hr></hr>
           <form action="{{ $delete_action }}" method="POST">
               <input type="hidden" name="_method" value="DELETE">
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <div class="form-group">
                   <button type="submit" class="btn btn-danger">Delete</button>
                    <a class="btn btn-default" href="{{ action('PublisherController@index') }}">
                        <i class="fa fa-reply fa-fw"></i>
                        Cancel
                    </a>
               </div>
           </form>
       </div>
   </div>
</div>
@endsection
