<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;

use App\Masterdata\Publisher;

class PublisherController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 */
	public function index()
	{
        $publs = Publisher::paginate(5);
        $publs->setPath('');
        return view('masterdata/publisher-table',
                        ['publishers'=>$publs]
                   );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('masterdata/publisher-form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        //Storing new request
        $input = $request->only('name','city','province','phone','contact');
        $publ = Publisher::firstOrCreate($input);
        //Done
        return redirect('masterdata/publisher');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $publ = Publisher::findOrFail($id);
        return view('masterdata/publisher-view',
            ['publisher'=>$publ]
            );
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        //Set necessary parameter to edit data. 
        //  set `method` to `PUT`
        return view('masterdata/publisher-form',
            ['publisher'=>Publisher::findOrFail($id),
             'method'=>'PUT',
             'pub_id'=>$id
            ]
        ); 
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		//
        $input = $request->only('name','city','province','phone','contact');
        $pub = Publisher::find($id);
        $pub->name = $input['name'];
        $pub->city = $input['city'];
        $pub->province = $input['province'];
        $pub->phone = $input['phone'];
        $pub->contact = $input['contact'];
        $pub->save();
        $msg = "Update successful!";
        return redirect('masterdata/publisher')->with('message', $msg);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        try {
            $pub = Publisher::findOrFail($id);
            $pub->delete();
            //Set result message as flashdata
            $execMsg = "Deletion successful!";
        } catch (ModelNotFoundException $e) {
            //In case of failure on deletion/finding data, set errMsg
            $execMsg = "Cannot delete record. Data not found.";
            return redirect('masterdata/publisher')->with('errMsg',$execMsg);
        }
        return redirect('masterdata/publisher')->with('message',$execMsg);
	}

}
