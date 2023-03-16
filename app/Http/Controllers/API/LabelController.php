<?php

namespace App\Http\Controllers\API;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On récupère tous les joueurs
        $players = DB::table('labels')
            ->get()
            ->toArray();
        // On retourne les informations des utilisateurs en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $players,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'labelName' => 'required|max:100',
            // 'labelPicture' => 'required|max:100',
        ]);

        $filename = "";
        if ($request->hasFile('labelPicture')) {
        // On récupère le nom du fichier avec son extension, résultat $filenameWithExt : "jeanmiche.jpg"
        $filenameWithExt = $request->file('labelPicture')->getClientOriginalName();
        $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // On récupère l'extension du fichier, résultat $extension : ".jpg"
        $extension = $request->file('labelPicture')->getClientOriginalExtension();
        // On créer un nouveau fichier avec le nom + une date + l'extension, résultat $fileNameToStore :"jeanmiche_20220422.jpg"
        $filename = $filenameWithoutExt . '_' . time() . '.' . $extension;
        // On enregistre le fichier à la racine /storage/app/public/uploads, ici la méthode storeAs défini déjà le chemin /storage/app
        $path = $request->file('labelPicture')->storeAs('public/uploads', $filename);
        } else {
        $filename = Null;
        }


        // On crée un nouvel utilisateur
        $label = Label::create([
            'labelName' => $request->labelName,
            'labelPicture' => $filename,
        ]);
        // On retourne les informations du nouvel utilisateur en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $label,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Label $label)
    {
        // On retourne les informations de l'utilisateur en JSON
        return response()->json($label);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Label $label)
    {

        $this->validate($request, [
            'labelName' => 'required|max:100',
            // 'labelPicture' => 'required|max:100',
                    

        ]);
// dd($request);

        $filename = "";
        if ($request->hasFile('labelPicture')) {
        // On récupère le nom du fichier avec son extension, résultat $filenameWithExt : "jeanmiche.jpg"
        $filenameWithExt = $request->file('labelPicture')->getClientOriginalName();
        $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // On récupère l'extension du fichier, résultat $extension : ".jpg"
        $extension = $request->file('labelPicture')->getClientOriginalExtension();
        // On créer un nouveau fichier avec le nom + une date + l'extension, résultat $fileNameToStore :"jeanmiche_20220422.jpg"
        $filename = $filenameWithoutExt . '_' . time() . '.' . $extension;
        // On enregistre le fichier à la racine /storage/app/public/uploads, ici la méthode storeAs défini déjà le chemin /storage/app
        $path = $request->file('labelPicture')->storeAs('public/uploads', $filename);
        } else {
        $filename = Null;
        }

        $label->update([
            'labelName' => $request->labelName,
            'labelPicture' => $filename,
            
        ]);


        return response()->json([
            'status' => 'Mise à jour avec succèss',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label)
    {
        // On supprime l'utilisateur
        $label->delete();
        // On retourne la réponse JSON
        return response()->json([
            'status' => 'Supprimer avec succès avec succèss'
        ]);
    }
}
