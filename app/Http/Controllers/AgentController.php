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
        'name'=>'regex:/[A-Za-z0-9\ \.\-\,\:]+/',
        'agent_category_id'=>'numeric',
        'phone'=>'required|regex:/[0-9\(\)\-\ \+]+/',
        'city'=>'regex:/[A-Za-z\.\ ]+/',
        'contact'=>'regex:/[A-Za-z\.\ ]+/'
        ];

	/**
	 * Display a listing of the resource.
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
	 * Show the form for creating a new resource.
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
        $agent = Agent::with('agent_category')->find($id);
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
        return view('masterdata/agent-form',
            ['agent'=>Agent::with('agent_category')->find($id),
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
        $agent = Agent::find($id);
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
        $agent = Agent::with('magazine', 'agent_category')->find($agent_id);
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
        $agent = Agent::find($agent_id)
            ->magazine()
            ->save(Magazine::find($magazine_id));
        // Add new entry
        return redirect("masterdata/agent/relationship/{$agent_id}")
            ->with('message', 'Added new relationship!');
    }

}
