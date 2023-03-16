<?php

namespace App\Http\Controllers\API;

use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On récupère tous les joueurs
        $category = DB::table('categories')
            ->get()
            ->toArray();
        // On retourne les informations des utilisateurs en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $category,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoryName' => 'required|max:100',
        ]);
        // On crée un nouvel utilisateur
        $category = Category::create([
            'categorytName' => $request->categorytName,
        ]);
        // On retourne les informations du nouvel utilisateur en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $category,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(category $category)
    {
        // On retourne les informations de l'utilisateur en JSON
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, category $category)
    {
        $this->validate($request, [
            'categoryName' => 'required|max:100',
        ]);

        $category->update([
            'categorytName' => $request->categorytName,
        ]);
        return response()->json([
            'status' => 'Mise à jour avec succèss'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(category $category)
    {
        // On supprime l'utilisateur
        $category->delete();
        // On retourne la réponse JSON
        return response()->json([
            'status' => 'Supprimer avec succès avec succèss'
        ]);
    }
}
