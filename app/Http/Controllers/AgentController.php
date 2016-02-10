<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;

use App\Masterdata\Agent;
use App\Masterdata\AgentCategory;
use App\Masterdata\Magazine;

class AgentController extends Controller {

    /**
     * Agent form validation rules
     */
    protected $rules = [
        'name'=>'regex:/[A-Za-z0-9\ \.\-\,\:]+/|max:255',
        'agent_category_id'=>'required|numeric',
        'phone'=>'regex:/[0-9\(\)\-\ \+]+/|max:100',
        'city'=>'regex:/[A-Za-z\.\ ]+/|max:255',
        'contact'=>'regex:/[A-Za-z\.\ ]+/|max:255'
        ];

	/**
	 * Display a listing of the agents
	 *
	 * @return Response
	 */
	public function index()
	{
        $agents = Agent::paginate(10);
        $agents->setPath('');
        return view('masterdata/agent-table',['agents'=>$agents]);
	}

	/**
	 * Show the form for creating a new agent
	 *
	 * @return Response
	 */
	public function create()
	{
        $agent_cat = AgentCategory::all();
        return view('masterdata/agent-form',['agent_cat'=>$agent_cat]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, $this->rules);
        $input = $request->only('name','phone','contact','city', 'agent_category_id');
        $agent = Agent::firstOrCreate($input);
        $msg = "Added new Agent! {$input['name']}";
        return redirect('masterdata/agent')->with('message', $msg);
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
            $agent = Agent::with('agent_category')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $errMsg = "Cannot find agent! Error on `show` with ID={$id}";
            return redirect('masterdata/agent')->with('errMsg', $errMsg);
        }
        return view('masterdata/agent-view', ['agent'=>$agent]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        try {
            $agent = Agent::with('agent_category')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $errMsg = "Cannot find agent! Error on `edit` with ID={$id}";
            return redirect('masterdata/agent')->with('errMsg', $errMsg);
        }

        return view('masterdata/agent-form',
            ['agent'=>$agent,
             'agent_cat'=>AgentCategory::all(),
             'method'=>'PUT',
             'agent_id'=>$id
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
        $input = $request->only('name', 'agent_category_id', 'city', 'phone', 'contact');
        try {
            $agent = Agent::with('agent_category')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $errMsg = "Cannot find agent! Error on `update` with ID={$id}";
            return redirect('masterdata/agent')->with('errMsg', $errMsg);
        }

        $agent->name = $input['name'];
        $agent->agent_category_id = $input['agent_category_id'];
        $agent->city = $input['city'];
        $agent->contact = $input['contact'];
        $agent->phone = $input['phone'];
        $agent->save();
        $msg = "Update successful!";
        return redirect('masterdata/agent')->with('message', $msg);

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
        try {
            $agent = Agent::findOrFail($id);
            $agent->delete();
            //Set result message as flashdata
            $execMsg = "Delete successful!";
        } catch (ModelNotFoundException $e) {
            //In case of failure on deletion/finding data, set errMsg
            $execMsg = "Cannot delete record. Data not found.";
            return redirect('masterdata/agent')->with('errMsg',$execMsg);
        }
        return redirect('masterdata/agent')->with('message',$execMsg);
	}

    /**
     * Show Agent-Magazine relationship
     *
     * @param int agent_id
     * @return Response
     */
    public function getRelationship($agent_id)
    {
        try {
            $agent = Agent::with('magazine', 'agent_category')->findOrFail($agent_id);
        } catch (ModelNotFoundException $e) {
            $errMsg = "Cannot find agent! Error on `getRelationship` with ID={$agent_id}";
            return redirect('masterdata/agent')->with('errMsg', $errMsg);
        }

        $selected = [];
        foreach($agent->magazine as $mag) {
            $selected[] = $mag->id;
        }
        // Construct intersect_array
        $unselected_mags = Magazine::whereNotIn('id', $selected)->get();

        return view('masterdata/agent-relationship',
            ['agent'=>$agent, 'unselected_mags'=>$unselected_mags]);

    }

    /**
     * Process request to create new relationship
     *
     * @param Request request
     * @return Response
     */
    public function postCreateRelationship(Request $request)
    {
        $this->validate($request, ['magazine_id'=>'required|numeric', 'agent_id'=>'required|numeric']);
        $agent_id = $request->agent_id;
        $magazine_id = $request->magazine_id;
        // Get current agent
        try {
            $agent = Agent::findOrFail($agent_id);
            $magazine = Magazine::findOrFail($magazine_id);
        } catch (ModelNotFoundException $e) {
            $errMsg = "Cannot find agent/magazine! Error on `CreateRelationship` with agent ID={$agent_id} and magazine ID={$magazine_id}";
            return redirect('masterdata/agent')->with('errMsg', $errMsg);
        }

        $agent = $agent->magazine()->save($magazine);
        // Add new entry
        return redirect("masterdata/agent/relationship/{$agent_id}")
            ->with('message', 'Added new relationship!');
    }

}
