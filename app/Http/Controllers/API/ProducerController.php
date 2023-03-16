<?php

namespace App\Http\Controllers\API;

use App\Models\Producer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProducerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On récupère tous les joueurs
        $players = DB::table('producers')
            ->join('categories','categories.id', '=', 'producers.category_id' )

            ->join('producers_labels', 'producers_labels.producers_id', '=', 'producers.id')
            ->join('labels', 'labels.id', '=', 'producers_labels.labels_id')
            ->select('producers.*', 'labels.labelName as label_name', 'labels.labelPisture as label_picture')
            
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
            'firstName' => 'required|max:100',
            'lastName' => 'required|max:100',
            'email' => 'required|max:100',
            'phone' => 'required|max:25',
            'address' => 'required',
            'zip' => 'required|max:15',
            'city' => 'required|max:100',
            'description' => 'required',

        ]);

        $filename = "";
        if ($request->hasFile('picture')) {
        // On récupère le nom du fichier avec son extension, résultat $filenameWithExt : "jeanmiche.jpg"
        $filenameWithExt = $request->file('picture')->getClientOriginalName();
        $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // On récupère l'extension du fichier, résultat $extension : ".jpg"
        $extension = $request->file('picture')->getClientOriginalExtension();
        // On créer un nouveau fichier avec le nom + une date + l'extension, résultat $fileNameToStore :"jeanmiche_20220422.jpg"
        $filename = $filenameWithoutExt . '_' . time() . '.' . $extension;
        // On enregistre le fichier à la racine /storage/app/public/uploads, ici la méthode storeAs défini déjà le chemin /storage/app
        $path = $request->file('picture')->storeAs('public/uploads', $filename);
        } else {
        $filename = Null;
        }


        // On crée un nouvel utilisateur
        $producer = Producer::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'zip' => $request->zip,
            'city' => $request->city,
            'description' => $request->description,
            'picture' => $filename,
            'category_id' => $request->category_id

        ]);
        // On retourne les informations du nouvel utilisateur en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $producer,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Producer $producer)
    {
        // On retourne les informations de l'utilisateur en JSON
        return response()->json($producer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producer $producer)
    {
        $this->validate($request, [
            'firstName' => 'required|max:100',
            'lastName' => 'required|max:100',
            'email' => 'required|max:100',
            'phone' => 'required|max:25',
            'address' => 'required',
            'zip' => 'required|max:15',
            'city' => 'required|max:100',
            'description' => 'required',
            // 'picture' => 'required|max:100',

        ]);

        $filename = "";
        if ($request->hasFile('picture')) {
        // On récupère le nom du fichier avec son extension, résultat $filenameWithExt : "jeanmiche.jpg"
        $filenameWithExt = $request->file('picture')->getClientOriginalName();
        $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // On récupère l'extension du fichier, résultat $extension : ".jpg"
        $extension = $request->file('picture')->getClientOriginalExtension();
        // On créer un nouveau fichier avec le nom + une date + l'extension, résultat $fileNameToStore :"jeanmiche_20220422.jpg"
        $filename = $filenameWithoutExt . '_' . time() . '.' . $extension;
        // On enregistre le fichier à la racine /storage/app/public/uploads, ici la méthode storeAs défini déjà le chemin /storage/app
        $path = $request->file('picture')->storeAs('public/uploads', $filename);
        } else {
        $filename = Null;
        }


        $producer->update([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'zip' => $request->zip,
            'city' => $request->city,
            'description' => $request->description,
            'picture' => $filename,
            'category_id' => $request->category_id


        ]);
        return response()->json([
            'status' => 'Mise à jour avec succèss',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producer $producer)
    {
        // On supprime l'utilisateur
        $producer->delete();
        // On retourne la réponse JSON
        return response()->json([
            'status' => 'Supprimer avec succès avec succèss'
        ]);
    }
}
