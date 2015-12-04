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
     * define fillable form contents for
     * new category creation or editing
     */
    protected $rules = [
        'name'=>'required|regex:/^[A-Za-z0-9\.\ \-\,\:]+$/',
        'description'=>'required|regex:/^[A-Za-z0-9\.\ \-\,\:]+$/'
        ];


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $categories = AgentCategory::all();
        return view('masterdata/agent-cat-table',['agent_cat'=>$categories]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
        return view('masterdata/agent-cat-form');
	}

	/**
	 * Store a newly created resource in storage.
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
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $agentCat = AgentCategory::find($id);
        return view('masterdata/agent-cat-view', ['agent_cat'=>$agentCat]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return view('masterdata/agent-cat-form',
            ['agent_cat'=>AgentCategory::find($id),
            'agent_cat_id'=>$id,
            'method'=>'PUT'
        ]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        $input = $request->only('name', 'description');
        $agentCat = AgentCategory::find($id);
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
