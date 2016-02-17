<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;

use App\Masterdata\AgentCategory;
use App\Masterdata\Agent;


class AgentCatController extends Controller {

    /**
     * define form validation rules for
     * new category creation or edits
     */
    protected $rules = [
        'name'=>'required|regex:/^[A-Za-z0-9\.\ \-\,\:]+$/',
        'description'=>'required|regex:/^[A-Za-z0-9\.\ \-\,\:\(\)]+$/'
        ];


	/**
	 * Display a listing of agent categories
	 *
	 * @return Response
	 */
	public function index()
	{
        $categories = AgentCategory::all();
        return view('masterdata/agent-cat-table',['agent_cat'=>$categories]);
	}

	/**
	 * Show the form for creating a new agent category
	 *
	 * @return Response
	 */
	public function create()
	{
		//
        return view('masterdata/agent-cat-form');
	}

	/**
	 * Store a newly created category in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $this->validate($request, $this->rules);
        $input = $request->only('name', 'description');
        $agentCat = AgentCategory::firstOrCreate($input);
        $msg = "New category: {$input['name']} is successfully added!";
        return redirect('masterdata/agent-cat')->with('message', $msg);
	}

	/**
	 * Display the specified agent category
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        try {
            $agentCat = AgentCategory::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $execMsg = "Cannot find category. Failed with ID={$id}";
            return redirect('masterdata/agent-cat')->with('errMsg', $execMsg);
        }

        return view('masterdata/agent-cat-view', ['agent_cat'=>$agentCat]);
	}

	/**
	 * Show the form for editing the specified agent category
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        try {
            $agentCat = AgentCategory::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $execMsg = "Cannot edit category. Failed with ID={$id}";
            return redirect('masterdata/agent-cat')->with('errMsg', $execMsg);
        }

        return view('masterdata/agent-cat-form',
            ['agent_cat'=>$agentCat,
            'agent_cat_id'=>$id,
            'method'=>'PUT'
        ]);
	}

	/**
	 * Update the specified agent category in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        $this->validate($request, $this->rules);
        $input = $request->only('name', 'description');

        try {
            $agentCat = AgentCategory::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $execMsg = "Cannot update category. Failed with ID={$id}";
            return redirect('masterdata/agent-cat')->with('errMsg', $execMsg);
        }

        $agentCat->name = $input['name'];
        $agentCat->description = $input['description'];
        $agentCat->save();
        $msg = "Update successful!";
        return redirect("masterdata/agent-cat")->with('message', $msg);
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
            $agentCat = AgentCategory::findOrFail($id);
            $agentCat->delete();
            // Update all Agent tables too
            $execMsg = "Delete Successful!";
        } catch (ModelNotFoundException $e) {
            $execMsg = "Cannot delete record. Data not found.";
            return redirect('masterdata/agent-cat')->with('errMsg', $execMsg);
        }
        return redirect('masterdata/agent-cat')->with('message', $execMsg);
	}

}
