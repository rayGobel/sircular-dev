<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;

use App\Masterdata\Publisher;

class PublisherController extends Controller {

    protected $rules = ['name'=>'required|max:255',
                        'city'=>'max:255',
                        'province'=>'max:255',
                        'phone'=>'max:50',
                        'contact'=>'max:100'];

	/**
	 * Display a listing of publishers
     *
     * @return Response
	 *
	 */
	public function index()
	{
        $publs = Publisher::paginate(10);
        $publs->setPath('');
        return view('masterdata/publisher-table',
                        ['publishers'=>$publs]
                   );
	}

	/**
	 * Show the form for creating a new publisher.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('masterdata/publisher-form');
	}

	/**
	 * Store a newly created publisher in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        //Storing new request
        $this->validate($request, $this->rules);
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
        try {
            $publ = Publisher::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $execMsg = "Cannot show record. Unknown publisher with ID={$id}";
            return redirect('masterdata/publisher')->with('errMsg',$execMsg);
        }

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
        try {
            $publ = Publisher::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $execMsg = "Cannot edit record. Unknown publisher with ID={$id}";
            return redirect('masterdata/publisher')->with('errMsg',$execMsg);
        }

        return view('masterdata/publisher-form',
            ['publisher'=>$publ,
             'method'=>'PUT',
             'pub_id'=>$id
            ]
        ); 
	}

	/**
	 * Update the specified publisher in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        $this->validate($request, $this->rules);
        $input = $request->only('name','city','province','phone','contact');
        try {
            $pub = Publisher::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $execMsg = "Cannot update record. Unknown publisher with ID={$id}.";
            return redirect('masterdata/publisher')->with('errMsg',$execMsg);
        }

        // Begin changes
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
	 * Remove the specified publisher from storage.
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
