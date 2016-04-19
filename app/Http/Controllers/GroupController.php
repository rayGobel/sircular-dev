<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Group;

class GroupController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $groups = Group::all();
        return view('group-table', ['groups'=>$groups]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $form_action = action('GroupController@store');
		return view('group-form', ['form_action'=>$form_action]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
     * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
        $this->validate($request, [
            'name'=>'regex:/[A-Za-z0-9\ \_\,\-\.]+/|max:255'
        ]);

        $newGroup = new Group;
        $newGroup->name = $request->input('name');
        $newGroup->save();
        $msg = "Added new group!";
        return redirect('group')->with('message', $msg);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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
            $group = Group::findOrFail($id);
            $group->delete();
            $msg = "Delete successful!";
        } catch (ModelNotFoundException $e) {
            $msg = "Cannot delete record. Data not found.";
            return redirect('group')->with('errMsg', $msg);
        }
        return redirect('group')->with('message', $msg);
	}

}
