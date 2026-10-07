<?php

namespace App\Http\Controllers;

use App\Models\commandes;
use App\Models\config;
use App\Models\historiques_connexion;
use App\Models\produits;
use App\Models\User;
use App\Models\views;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportUser;
use App\Http\Traits\ListGouvernorats;
use App\Models\clients;
use App\Models\contacts;
use App\Models\contenu_commande;
use App\Models\historiques_stock;
use App\Models\notifications;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;


class AdminController extends Controller
{
    use ListGouvernorats;



    public function dashboard(Request $request)
    {

        //veification des permissions
        if (Gate::check('dashboard')) {
        } elseif (Gate::check('product_view')) {
            return redirect()->route('produits');
        } elseif (Gate::check('order_view')) {
            return redirect()->route('commandes');
        } elseif (Gate::check('clients_view')) {
            return redirect()->route('clients');
        } else {
            return "Veuillez demande a l'administrateur de vous attribuer des permissions.";
        }


        $currentYear = date('Y');

        $currentYear2 = Carbon::now()->year;


        // Format ISO 8601 (YYYY-MM-DD)
        $firstDayOfYearISO = Carbon::createFromDate($currentYear2, 1, 1)->startOfDay()->format('Y-m-d');
        $lastDayOfYearISO = Carbon::createFromDate($currentYear2, 12, 31)->endOfDay()->format('Y-m-d');


        $date_debut = $request->input('date_debut') ??  $firstDayOfYearISO;
        $date_fin = $request->input('date_fin') ?? $lastDayOfYearISO;

        //get statistiques
        $visitsPerMonth = [];
        $commandesPerMonth = [];
        $ventesPerMonth = [];
        $inscriptionMonth = [];
        $stat_commande_confirmer_Month = [];
        $stat_commande_non_confirmer_Month = [];
        $profilNet = [];
        for ($i = 1; $i <= 12; $i++) {
            $visitsPerMonth[] = Views::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->count();
            $commandesPerMonth[] = Commandes::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)->count();

            $stat_commande_confirmer_Month[] =  Commandes::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->where('etat', 'confirmé')
                ->count();

            $stat_commande_non_confirmer_Month[] =  Commandes::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->where('etat', 'attente')
                ->count();
            $montant = Commandes::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->where('statut', 'payée')
                ->get()
                ->sum(function ($commande) {
                    return $commande->montant();
                });

            $inscriptionMonth[] = User::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->where('role', 'client')
                ->count();

            $ventesPerMonth[] = $montant;


            //calcul du profil net de tous les produit
           /* $stat_commande_non_confirmer_Month[] =  Commandes::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->where('etat', 'attente')
                ->count();*/
            $profilNet[] = contenu_commande::whereBetween('created_at', [$date_debut, $date_fin])
                ->whereMonth('created_at', $i)
                ->whereHas('commande', function ($query) {
                    $query->where('statut', 'payée');
                })
                ->sum('benefice');
        }


        $total_visites = views::whereBetween('created_at', [$date_debut, $date_fin])->count();
        $total_commandes = commandes::whereBetween('created_at', [$date_debut, $date_fin])->where('statut', 'payée')->get(['id']);
        $total_produits = produits::count();
        $totalDesCommandes = 0;
        foreach ($total_commandes as $command) {
            $totalDesCommandes += $command->montant();
        }






        $commandes = DB::table('commandes')
            ->selectRaw('statut, COUNT(*) as count')
            ->whereBetween('created_at', [$date_debut, $date_fin])
            ->where(function ($query) {
                $query->where('statut', '!=', 'créé')
                    ->orWhere(function ($query) {
                        $query->where('statut', 'créé')
                            ->where('etat', 'confirmé');
                    });
            })
            ->where(function ($query) {
                $query->where('statut', '!=', 'retournée')
                    ->orWhere(function ($query) {
                        $query->where('statut', 'retournée')
                            ->where('etat', 'confirmé');
                    });
            })
            ->groupBy('statut')
            ->whereIn('statut', [
                'créé',
                'traitement',
                'livraison',
                'livrée',
                'livré',
                'payée',
                'planification retour',
                'retournée',
                'en cours de livraison - tentative 1',
                'livré estimé',
                'Échange reçu par expéditeur',
                'retour reçu par expéditeur',
                'a vérifier',
                'livré payee',
                "transfert des retours expéditeur en attente",
                "en entrepôt (retour des échanges)"
            ])

            ->get()
            ->pluck('count', 'statut')
            ->toArray();

        $nombre_total_commande = commandes::whereBetween('created_at', [$date_debut, $date_fin])->count();
        $statistique_commandes_graph = [];
        if ($nombre_total_commande > 0) {
            foreach ($commandes as $key => $coms) {
                $statistique_commandes_graph[] = [
                    "statut" => $key,
                    "valeur" => $coms,
                    "pourcentage" => round((($coms / $nombre_total_commande) * 100), 2),
                ];
            }
        }

        $etat_commandes = commandes::selectRaw('etat, COUNT(*) as count')
            ->whereBetween('created_at', [$date_debut, $date_fin])
            ->groupBy('etat')
            ->whereIn('etat', ['confirmé', 'attente'])
            ->get()
            ->pluck('count', 'etat')
            ->toArray();
        // Vérifiez si $etat_commandes est défini, sinon définissez-le comme un tableau vide
        $etat_commandes = $etat_commandes ?? [];
        $pourcentage_confirmer = 0;
        $pourcentage_non_confirmer = 0;
        $total_confirme = $etat_commandes['confirmé'] ?? 0;
        $total_non_confirme = $etat_commandes['attente'] ?? 0;
        $total_commandes = $total_confirme + $total_non_confirme;
        if ($total_commandes != 0) {
            $pourcentage_confirmer = round(($total_confirme / $total_commandes) * 100);
            $pourcentage_non_confirmer = round(($total_non_confirme / $total_commandes) * 100);
        }

        $etat_commandes = [
            'confirmer' => $total_confirme,
            'non-confirmer' => $total_non_confirme,
            'pourcentage_confirmer' => $pourcentage_confirmer,
            'pourcentage_non-confirmer' => $pourcentage_non_confirmer,
        ];
        $total = Commandes::where('etat', 'confirmé')
            ->get()
            ->sum(function ($commande) {
                return $commande->montant();
            });

        $json_commandes = '[' . implode(',', [
                $commandes['créé'] ?? 0,
                $commandes['traitement'] ?? 0,
                $commandes['livraison'] ?? 0,
                $commandes['livré'] ?? 0,
                $commandes['payée'] ?? 0,
                $commandes['planification retour'] ?? 0,
                $commandes['retournée'] ?? 0,
                $commandes['en cours de livraison - tentative 1'] ?? 0,
                $commandes['livré payee'] ?? 0,
                $commandes['transfert des retours expéditeur en attente'] ?? 0,
                $commandes['retour reçu par expéditeur'] ?? 0,
                $commandes['Échange reçu par expéditeur'] ?? 0,
                $commandes['livré estimé'] ?? 0,
                $commandes['en entrepôt (retour des échanges)'] ?? 0,
            ]) . ']';

        return view('admin.index')
            ->with('visitsPerMonth', $visitsPerMonth)
            ->with('commandesPerMonth', $commandesPerMonth)
            ->with('ventesPerMonth', $ventesPerMonth)
            ->with('total_visites', $total_visites)
            ->with('total_commandes', $total_commandes)
            ->with('total_produits', $total_produits)
            ->with("statistique_commandes_graph", $statistique_commandes_graph)
            ->with("commandes", $commandes)
            ->with('nombre_total_commande', $nombre_total_commande)
            ->with("json_commandes", $json_commandes)
            ->with('inscriptionMonth', $inscriptionMonth)
            ->with('profilNet', $profilNet)
            ->with('stat_commande_non_confirmer_Month', $stat_commande_non_confirmer_Month)
            ->with('stat_commande_confirmer_Month', $stat_commande_confirmer_Month)
            ->with('etat_commandes', $etat_commandes)
            ->with('totalDesCommandes', $totalDesCommandes)
            ->with('total_confirmé',$total);
    }



    public function update_config(Request $request)
    {
        $send_mail_update_commande = $request->input('send_mail_update_commande') ? 1 : 0;
        $config = config::first();
        $config->send_mail_update_commande = $send_mail_update_commande;
        $config->save();

        return redirect()
            ->route('commandes')
            ->with('success', 'Configuration mise à jour avec succès');
    }



    public function new_commande()
    {
        return view("admin.commandes.ajouter");
    }


    public function add_note(Request $request)
    {
        $id_commande = $request->input('id_commande');
        $note = $request->input("note");

        $commande = commandes::find($id_commande);
        $commande->note = $note;
        if (!$commande) {
            return redirect()
                ->route("commandes")
                ->with("error", "Commande introuvable!");
        }
        $commande->save();
        return redirect()
            ->route("commandes")
            ->with("success", "La note a été ajouté a la commande.");
    }


    public function corbeille()
    {
        return view("admin.produits.corbeille");
    }


    public function contacts(){
        $contacts = contacts::all();
        return view('admin.contacts.list', compact('contacts'));
    }



    public function export_clients()
    {
        $users = User::select('nom', 'email', 'phone', 'adresse', 'city','points')
            ->where('role', 'client') // 🔹 filtre ajouté ici

            ->get();
        return Excel::download(new ExportUser($users), 'users.xlsx');
    }



    public function live_notifications()
    {
        $total = notifications::where("statut", "unread")->count();
        return response()->json(
            [
                'total' => $total
            ]
        );
    }


    public function edit_commande($id)
    {
        $commande = commandes::find($id);
        if (!$commande) {
            $message = "Commande introuvable";
            abort('404', $message);
        }
        if ($commande->statut == 'retournée' && $commande->statut == 'payée') {
            return redirect()->route('commandes');
        }
        return view('admin.commandes.edit', compact('commande'));
    }


    public function produit_add()
    {
        return view('admin.produits.add');
    }

    public function produits()
    {
        return view('admin.produits.list');
    }

    public function produits_update($id)
    {
        $produit = produits::find($id);
        if (!$produit) {
            $message = "Produit non disponible !";
            abort(404, $message);
        }
        return view('admin.produits.update', compact('produit'));
    }


    public function historique(Request $request,$id)
    {
        $date = $request->input('date') ?? null;
        $produit = produits::find($id);
        if (!$produit) {
            $message = "Produit non disponible !";
            abort(404, $message);
        }
        $historique_stock = historiques_stock::where('id_produit',$produit->id)->Orderby('id',"Desc");
        if($date){
            $historique_stock = $historique_stock->whereDate('created_at',$date);
        }
        $historique_stock = $historique_stock->paginate(100);

        return view('admin.produits.historique', compact('produit','historique_stock'));
    }



    public function commandes()
    {
        return view('admin.commandes.list');
    }

    public function parametres()
    {
        $connexions = historiques_connexion::Orderby("id", "Desc")
            ->where('user_id', Auth::id())
            ->get();

        $ipAddress = request()->ip();
        return view('admin.parametres.index', compact('connexions'));
    }


    public function personnels()
    {
        $personnels = User::where('role', 'personnel')->get();
        return view('admin.personnels.list', compact('personnels'));
    }


    public function details_commande($id)
    {
        $commande = commandes::find($id);
        if (!$commande) {
            $message = "Commande introuvable !";
            abort(404, $message);
        }
        return view('admin.commandes.details', compact('commande'));
    }



    public function promotions($id = null)
    {
        if ($id !== null) {
            $produit = Produits::find($id);
            if (!$produit) {
                abort(404);
            }
        } else {
            $produit = null;
        }
        return view('admin.promotions.index', compact('produit'));
    }


    public function clients()
    {
        return view('admin.clients.list');
    }



    public function contact_admin()
    {
        $config = config::first();
        return view('admin.parametres.contact')
        ->with('config', $config);
    }



    public function contact_admin_update(Request $request){
        $this->validate($request, [
            'logo' =>  'image|nullable|max:3024',
            'icon' =>  'image|nullable|max:3024',
            'frais' => 'nullable|numeric',
            'tva' => 'nullable|numeric',
            'timbre' => 'nullable|numeric',
            'telephone' => 'nullable|numeric',
            'adresse' => 'nullable|string',
            'facebook' => 'nullable|string',
            'instagram' => 'nullable|string',
            'email' => 'nullable|email',
            'tiktok' => 'nullable|string',
            'matricule' => 'nullable|string',
            'text_header' => 'nullable|string',
            'text_header_center' => 'nullable|string',
        ]);

        // update the user
        $config = config::first();


        if($request->file('icon')){
            if ($config->icon) {
                Storage::disk('public')->delete($config->icon);
            }
            $config->icon= $request->file('icon')->store('icon', 'public');
        }
        if($request->file('logo')){
            if ($config->logo) {
                Storage::disk('public')->delete($config->logo);
            }
            $config->logo= $request->file('logo')->store('logo', 'public');
        }

        $config->frais = $request->frais;
        $config->tva = $request->tva;
        $config->timbre = $request->timbre;
        $config->telephone = $request->telephone;
        $config->adresse = $request->adresse;
        $config->facebook = $request->facebook;
        $config->instagram = $request->instagram;
        $config->email = $request->email;
        $config->tiktok = $request->tiktok;
        $config->matricule = $request->matricule;
        $config->text_header = $request->text_header;
        $config->text_header_center = $request->text_header_center;
        if($config->save()){
            //flash message
            return redirect()->back()->with('info', 'Vos modifications ont été enregistrées.');
        }else{
            //flash message
            return redirect()->back()->with('danger', 'Vos modifications n\'ont pas été enregistrées.');
        }
    }



    public function delete_personnel($id)
    {
        $user = User::where("id", '=', $id)->first();
        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', 'Personnel supprimé avec succès!');
        }
    }



    public function open_url_notification(Request $request){
        $url = $request->get("url");
        $notifs = notifications::where('url',$url)->get();
        foreach ($notifs as $key => $notif) {
            $notif->update(["statut"=>"read"]);
        }


        return redirect($url);
    }


    public function update_permission(Request $request)
    {

        $selectedPermissions = $request->input('permissions', []);
        $user = User::findOrFail($request->input('id'));
        $user->syncPermissions($selectedPermissions);
        return redirect()
            ->back()
            ->with('success', 'Permissions mises à jour avec succès.');
    }




    public function delete_all_notifications(){
        $notifs = notifications::all();
        foreach ($notifs as $key => $notif) {
            $notif->delete();
        }
        return redirect()->back()->with('success', 'Toutes les notifications ont été supprimées!');
    }



    public function update_home_infos(Request $request){
        //validation
        $this->validate($request, [
            'home_text_1' => 'nullable|string',
            'home_lien_1' => 'nullable|string',
            'home_photo_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4048',
            'home_text_2' => 'nullable|string',
            'home_lien_2' => 'nullable|string',
            'home_photo_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4048',
        ]);
        $config = config::first();
        $config->home_text_1 = $request->home_text_1;
        $config->home_lien_1 = $request->home_lien_1;
        $config->home_text_2 = $request->home_text_2;
        $config->home_lien_2 = $request->home_lien_2;
        if($request->file('home_photo_1')){
            if ($config->home_photo_1) {
                Storage::disk('public')->delete($config->home_photo_1);
            }
            $config->home_photo_1= $request->file('home_photo_1')->store('home_photo_1', 'public');
        }
        if($request->file('home_photo_2')){
            if ($config->home_photo_2) {
                Storage::disk('public')->delete($config->home_photo_2);
            }
            $config->home_photo_2= $request->file('home_photo_2')->store('home_photo_2', 'public');
        }
        $config->save();
        return redirect()->back()->with('success', 'Informations mises à jour avec succès!');
    }



    public function home_infos(){
        $config = config::first();
        return view('admin.parametres.home_infos', compact('config'));
    }

    public function produit_reorder(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['success' => false, 'message' => 'ids invalid'], 422);
        }

        DB::transaction(function () use ($ids) {
            foreach ($ids as $index => $id) {
                Produits::where('id', $id)->update([
                    'order_column' => $index + 1
                ]);
            }
        });

        return response()->json(['success' => true]);
    }
}
