<?php

namespace App\Http\Controllers\API;

use App\Models\Producer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Label;

class ProducerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //Ce code n'affiche que les producteurs présent dans la table Pivot

        // On récupère tous les producteurs
        $players = DB::table('producers')
            // On y join la table categories
            ->join('categories', 'categories.id', '=', 'producers.category_id')

            // On y join la table label_producer
            ->join('label_producer', 'label_producer.producer_id', '=', 'producers.id')
            // On y join la table labels
            ->join('labels', 'labels.id', '=', 'label_producer.label_id')
            // On sélectionne les colonnes du producteurs et on les renommes
            ->select('producers.*', 'labels.labelName as label_name', 'labels.labelPicture as label_picture')

            // On récupère sous forme de tableau
            ->get()
            // On le transforme en tableau PHP
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


        // On crée un nouveau producteur
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

        // // table pivot label_producer // //

        // Cette ligne de code ajoute l'ID du label envoyé dans la requête dans un tableau $producersLabelIds.
        // Cela vous permettra de récupérer tous les ID des labels sélectionnés pour le producteur       
        $producersLabelIds[] = $request->label_id;
        // Cette condition vérifie si le tableau $producersLabelIds n'est pas vide avant de poursuivre le traitement.
        //  Cela permet d'éviter d'exécuter le code inutilement si aucun label n'a été envoyé dans la requête.        
        if (!empty($producersLabelIds)) {
            // Cette boucle foreach itère sur chaque ID de label présent dans le tableau $producersLabelIds.
            foreach ($producersLabelIds as $labelId) {
                // Cette ligne de code récupère le modèle Label correspondant à l'ID du label de la boucle en utilisant la méthode find().
                //  Cette méthode recherche un enregistrement dans la table labels qui a l'ID spécifié et renvoie un objet Label correspondant.
                $label = Label::find($labelId);
                // Cette ligne de code utilise la méthode attach() sur la relation ManyToMany entre Producer et Label pour ajouter le label
                //  récupéré précédemment au producteur que vous voulez mettre à jour.
                $producer->label()->attach($label);
            }
        }
        
        // On retourne les informations du nouveau producteur en JSON
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
        // On retourne les informations du producteur en JSON
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

        // création d'un tableau vide $updateProdId pour stocker les identifiants des modèles Label
        // qui seront utilisés pour la mise à jour des relations.
        $updateProdId = array();

        // // table pivot label_producer // //

        // récupèration des identifiants des modèles Label à partir de la requête HTTP : $producersLabelId= $request->label_id;.
        $producersLabelId= $request->label_id;
        // on vérifie que le tableau $producersLabelId n'est pas vide,
        if (!empty($producersLabelId)) {
            // puis pour chaque identifiant dans le tableau, on récupère le modèle Label correspondant en utilisant la méthode find() 
            for ($i = 0; $i < count($producersLabelId); $i++) {
                $label = Label::find($producersLabelId[$i]);
                // on ajoute son identifiant au tableau $updateProdId en utilisant la fonction array_push().
                array_push($updateProdId, $label->id);
            }
            // on appelle la méthode sync() sur la relation label du modèle Producer en passant le tableau $updateProdId comme argument,
            //  ce qui mettra à jour les relations en supprimant toutes les entrées pivot existantes et
            //  en insérant de nouvelles entrées pour les identifiants de modèles Label fournis.
            $producer->label()->sync($updateProdId);
        }



        return response()->json([
            'status' => 'Mise à jour avec succèss',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producer $producer)
    {
        // On supprime tous les enregistrements associés dans la table pivot
        $producer->label()->detach();
        // On supprime l'utilisateur
        $producer->delete();
        // On retourne la réponse JSON
        return response()->json([
            'status' => 'Supprimer avec succès avec succèss'
        ]);
    }
}
