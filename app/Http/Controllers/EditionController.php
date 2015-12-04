<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException
    as ModelNotFoundException;

use App\Masterdata\Edition;
use App\Masterdata\Magazine;

class EditionController extends Controller {

    /**
     * Necessary rules for form validation
     */
    protected $edition_rules = [
            'edition_code'=>'required',
            'magazine_id'=>'numeric',
            'price'=>'required|numeric'
        ];

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $editions = Edition::with('magazine')->paginate(15);
        $editions->setPath('');
        $magazines = Magazine::all();
        return view('masterdata/edition-table',
                        ['editions'=>$editions, 'magazines'=>$magazines]
                   );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $magz = Magazine::all(); //For Edition options
		return view('masterdata/edition-form', ['magazines'=>$magz]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        //Validate, continue if successful
        $this->validate($request, $this->edition_rules);

        $input = $request->only('edition_code', 'magazine_id', 'price', 'main_article', 'cover');
        $edtn = Edition::firstOrCreate($input);
        $msg = "Added new edition! Code: {$input['edition_code']}";
        return redirect('masterdata/edition')->with('message', $msg);

	}

    public function postBeginFilter(Request $request)
    {
        // I suppose there are better method than this
        $input = $request->only('magazine_id');
        return redirect('masterdata/edition/filter/'.$input['magazine_id']);

    }

    public function getFilter($magazine_id)
    {
        $magazines = Magazine::all();
        $edition = Edition::with('magazine')->Filter($magazine_id)
            ->paginate(5);
        $edition->setPath('');
        return view('masterdata/edition-table',
                        ['editions'=>$edition, 'magazines'=>$magazines]
                   );

    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $edition = Edition::with('magazine')->find($id);
        return view('masterdata/edition-view',
            ['edition'=>$edition]
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
        return view('masterdata/edition-form',
            ['edition'=>Edition::with('magazine')->find($id),
             'magazines'=>Magazine::all(),
             'method'=>'PUT',
             'edition_id'=>$id
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
        $input = $request->only('edition_code', 'magazine_id', 'price', 'cover', 'main_article');
        $edition = Edition::find($id);
        $edition->edition_code = $input['edition_code'];
        $edition->magazine_id = $input['magazine_id'];
        $edition->cover = $input['cover'];
        $edition->price = $input['price'];
        $edition->main_article = $input['main_article'];
        $edition->save();
        $msg = "Update successful!";
        return redirect('masterdata/edition')->with('message', $msg);
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
            $edition = Edition::findOrFail($id);
            $edition->delete();
            //Set result message as flashdata
            $execMsg = "Deletion successful!";
        } catch (ModelNotFoundException $e) {
            //In case of failure on deletion/finding data, set errMsg
            $execMsg = "Cannot delete record. Data not found.";
            return redirect('masterdata/edition')->with('errMsg',$execMsg);
        }
        return redirect('masterdata/edition')->with('message',$execMsg);
	}

}
