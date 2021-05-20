<?php

namespace App\Http\Controllers\Api;

use App\Referentiel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferentielRequest;
use App\Http\Resources\ReferentielResource;
use App\Http\Actions\Referentiel\FindReferentiel;

class ReferentielController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin',['only' => ['store','update']]);
    }

    /**
     * Affiche la liste des referentiels
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, FindReferentiel $findReferentiel)
    {
        if($request->has('type')){
            return ReferentielResource::collection($findReferentiel->byType($request->get('type')));
        }
        return ReferentielResource::collection($findReferentiel->all());
    }

    /**
     * Creé un referentiel
     *
     * @param  \App\Http\Requests\ReferentielRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReferentielRequest $request)
    {
        //
        $referentiel = new Referentiel($request->all());
        $referentiel->save();
        return $this->createdResponse(new ReferentielResource($referentiel));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Referentiel  $referentiel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Referentiel $referentiel)
    {
        //
        $referentiel->update($request->all());
        return $this->createdResponse(new ReferentielResource($referentiel));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
