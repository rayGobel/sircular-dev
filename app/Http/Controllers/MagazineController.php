<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;

//Use magazine model on masterdata
use App\Masterdata\Magazine;
use App\Masterdata\Publisher;

class MagazineController extends Controller {

    /**
     * Necessary rules for form validation
     */
    protected $magazine_rules = [
            'name'=>'required|max:255',
            'publisher_id'=>'required|numeric',
            'period'=>'required|alpha_num',
            'price'=>'required|numeric',
            'percent_fee'=>'numeric',
            'percent_value'=>'numeric'
        ];

	/**
	 * Display a listing of magazines
	 *
	 * @return Response
	 */
	public function index()
	{
        $magazines = Magazine::with('publisher')->paginate(5);
        $magazines->setPath('');
        return view('masterdata/magazine-table',
                        ['magazines'=>$magazines]
                    );

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $publs = Publisher::all();
        return view('masterdata/magazine-form',['publishers'=>$publs]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        //Try to validate. If successful, continue execution
        $this->validate($request, $this->magazine_rules);

        //Continue
        $input = $request->only('name', 'publisher_id', 'period', 'price', 'percent_fee', 'percent_value');
        $magazine = Magazine::firstOrCreate($input);
        $msg = "New magazine {$input['name']} has been created!";
        return redirect('masterdata/magazine')->with('message',$msg);
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
            $magazine = Magazine::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $errMsg = "Cannot show magazine. Error on ID={$id}";
            return redirect("masterdata/magazine")->with('errMsg', $errMsg);
        }

        return view('masterdata/magazine-view',
            ['magazine'=>$magazine]
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
        try {
            $magazine = Magazine::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $errMsg = "Cannot edit magazine. Error on ID={$id}";
            return redirect("masterdata/magazine")->with('errMsg', $errMsg);
        }

        return view('masterdata/magazine-form',
            ['magazine'=>$magazine,
             'publishers'=>Publisher::all(),
             'method'=>'PUT',
             'magz_id'=>$id
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
        $this->validate($request, $this->magazine_rules);
        $input = $request->only('name', 'publisher_id', 'period', 'price', 'percent_fee', 'percent_value');

        try {
            $magz = Magazine::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $errMsg = "Cannot update magazine. Error on ID={$id}";
            return redirect("masterdata/magazine")->with('errMsg', $errMsg);
        }
        $magz->name = $input['name'];
        $magz->publisher_id = $input['publisher_id'];
        $magz->period = $input['period'];
        $magz->price = $input['price'];
        $magz->percent_fee = $input['percent_fee'];
        $magz->percent_value = $input['percent_value'];
        $magz->save();
        $msg = "Update successful!";
        return redirect('masterdata/magazine')->with('message', $msg);
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
            $magz = Magazine::findOrFail($id);
            $magz->delete();
            //Set result message as flashdata
            $execMsg = "Deletion successful!";
        } catch (ModelNotFoundException $e) {
            //In case of failure on deletion/finding data, set errMsg
            $execMsg = "Cannot delete record. Data not found.";
            return redirect('masterdata/magazine')->with('errMsg',$execMsg);
        }
        return redirect('masterdata/magazine')->with('message',$execMsg);
	}


}
