@extends('app')

@section('content')
<div class="container">
   <div class="row">
       <div class="col-lg-6">
           <p class="lead">
           <strong>Are you sure you want to delete records of </strong>
           {{ $agent_cat->name }} <strong>?</strong>
           </p>
       </div>
   </div>
   <div class="row">
       <div class="col-lg-6">
           <h2>Details</h2>
           <table class='table'>
               <tbody>
                   <tr>
                       <td>Name</td>
                       <td>: </td>
                       <td>{{ $agent_cat->name }}</td>
                   </tr>
                   <tr>
                       <td>Description</td>
                       <td>: </td>
                       <td>{{ $agent_cat->description }}</td>
                   </tr>
               </tbody>
           </table>

       </div>
   </div>
   <div class="row">
       <div class="col-lg-6">
           <hr></hr>
           <form action="/sircular-dev/public/masterdata/agent-cat/{{ $agent_cat->id }}" method="POST">
               <input type="hidden" name="_method" value="DELETE">
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <div class="form-group">
                   <button type="submit" class="btn btn-danger">Delete</button>
                    <a class="btn btn-default" href="/sircular-dev/public/masterdata/agent-cat">
                        <i class="fa fa-reply fa-fw"></i>
                        Cancel
                    </a>
               </div>
           </form>
       </div>
   </div>
</div>
@endsection
