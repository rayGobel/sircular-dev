@extends('app')

@section('content')
<div class="container">
   <div class="row">
       <div class="col-lg-6">
           <p class='lead'>
           Are you sure you want to delete records of 
           <em>{{ $detail->agent->name }}</em> for edition <em>{{ $detail->distributionPlan->edition->edition_code }}</em> of <em>{{ $detail->distributionPlan->edition->magazine->name }}</em> ?
           </p>
       </div>
   </div>
   <div class="row">
       <div class="col-lg-6">
           <h2>Details</h2>
           <table class='table'>
               <tbody>
                   <tr>
                       <td>Quota</td>
                       <td>: </td>
                       <td>{{ $detail->quota }}</td>
                   </tr>
                   <tr>
                       <td>Consigned</td>
                       <td>: </td>
                       <td>{{ $detail->consigned }}</td>
                   </tr>
                   <tr>
                       <td>Gratis</td>
                       <td>: </td>
                       <td>{{ $detail->gratis }}</td>
                   </tr>
               </tbody>
           </table>

       </div>
   </div>
   <div class="row">
       <div class="col-lg-6">
           <hr></hr>
           <form action="{{ $detail->id }}" method="POST">
               <input type="hidden" name="_method" value="DELETE">
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <div class="form-group">
                   <button type="submit" class="btn btn-danger">Delete</button>
                   <a class="btn btn-default" href="{{ action('DistributionPlanController@show', $distPlanID) }}">
                        <i class="fa fa-reply fa-fw"></i>
                        Cancel
                    </a>
               </div>
           </form>
       </div>
   </div>
</div>
@endsection
