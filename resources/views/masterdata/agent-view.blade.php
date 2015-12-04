@extends('app')

@section('content')
<div class="container">
   <div class="row">
       <div class="col-lg-6">
           <p class="lead">
           <strong>Are you sure you want to delete records of </strong>
           {{ $agent->name }} <strong>?</strong>
           </p>
       </div>
   </div>
   <div class="row">
       <div class="col-lg-6">
           <h2>Details</h2>
           <table class='table'>
               <tbody>
                   <tr>
                       <td>Agent/Outlet Name</td>
                       <td>: </td>
                       <td>{{ $agent->name }}</td>
                   </tr>
                   <tr>
                       <td>City</td>
                       <td>: </td>
                       <td>{{ $agent->city }}</td>
                   </tr>
                   <tr>
                       <td>Category</td>
                       <td>: </td>
                       <td>{{ $agent->agent_category->name }}</td>
                   </tr>
                   <tr>
                       <td>Phone Number</td>
                       <td>: </td>
                       <td>{{ $agent->phone }}</td>
                   </tr>
                   <tr>
                       <td>Contact</td>
                       <td>: </td>
                       <td>{{ $agent->contact }}</td>
                   </tr>
               </tbody>
           </table>

       </div>
   </div>
   <div class="row">
       <div class="col-lg-6">
           <hr></hr>
           <form action="/sircular-dev/public/masterdata/agent/{{ $agent->id }}" method="POST">
               <input type="hidden" name="_method" value="DELETE">
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <div class="form-group">
                   <button type="submit" class="btn btn-danger">Delete</button>
                    <a class="btn btn-default" href="/sircular-dev/public/masterdata/agent">
                        <i class="fa fa-reply fa-fw"></i>
                        Cancel
                    </a>
               </div>
           </form>
       </div>
   </div>
</div>
@endsection
