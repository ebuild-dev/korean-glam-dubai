<?php

namespace App\Http\Controllers;

use App\Models\banners;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\categories;
use App\Models\commandes;
use App\Models\Comment;
use App\Models\config;
use App\Models\contacts;
use App\Models\contenu_commande;
use App\Models\Coupon;
use App\Models\gouvernorats;
use App\Models\produits;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;


class FrontController extends Controller
{
    public function index(Request $request)
    {

        $categories = categories::Orderby('order_column', 'Asc')->get();
        $banners =  banners::orderBy('order_column', 'asc')->get();
        $topProduits = contenu_commande::select('id_produit', DB::raw('SUM(quantite) as total_quantite'))
            ->groupBy('id_produit')
            ->orderBy('total_quantite', 'desc')
            ->take(8)
            ->get();

        $produits = produits::where('masquer_produit', false)->where('trend', true)->get();
        // ✅ BLOGS (3 derniers)
        $blogs = Blog::orderBy('date', 'desc')
            ->take(3)
            ->get();
        $OtherProduits = produits::take(8)->get();
        $NewProduits = produits::orderby("id", "desc")->take(8)->get();
        $agent = new Agent();
        $itemsPerSlide = $agent->isMobile() ? 1 : 4;
        return view('front.index')
            ->with('categories', $categories)
            ->with('topProduits', $topProduits)
            ->with('banners', $banners)
            ->with('produits', $produits)
            ->with('blogs',$blogs)
            ->with('itemsPerSlide', $itemsPerSlide)
            ->with('OtherProduits', $OtherProduits)
            ->with('NewProduits', $NewProduits);
    }

    public function login()
    {
        return view('front.login');
    }


    public function best_sellers()
    {
        return view('front.best_sellers');
    }


    public function register()
    {
        return view('front.register');
    }

    public function forgotpassword()
    {
        return view('front.forgotpassword');
    }

    public function cart(Request $request)
    {
        if (!session()->has('panier_front')) {
            session(['panier_front' => []]);
        }

        $configs = config::first();
        $panier_temporaire = session('panier_front');

        $produits = [];
        $total_prix = 0;
        $total_avant_promo = 0;
        $total_points_achat = 0;
        $if_promotion = false;

        $user = Auth::user();

        /* ================= PANIER ================= */
        foreach ($panier_temporaire as $pan) {
            $produit = produits::find($pan['id_produit']);
            if (!$produit) continue;

            $prix_apres = $produit->getPrice();
            $prix_avant = $produit->prix;

            $produits[] = [
                "produit_id" => $produit->id,
                "nom" => $produit->nom,
                "prix" => $prix_apres,
                "photo" => $produit->FirstImage(),
                "quantite" => $pan['quantite'],
                "total" => $prix_apres * $pan['quantite'],
                "points_achat" => $produit->points_achat,
                "en_promotion" => $produit->inPromotion(),
                "prix_before" => $prix_avant,
            ];

            if ($produit->inPromotion()) {
                $if_promotion = true;
            }

            $total_prix += $prix_apres * $pan['quantite'];
            $total_avant_promo += $prix_avant * $pan['quantite'];
            $total_points_achat += ($produit->points_achat ?? 0) * $pan['quantite'];
        }

        /* ================= BASE TOTALS ================= */
        $shipping = $configs->frais;
        $total_prix_sans_coupon = $total_prix;
        $total_prix_with_shipping_sans_coupon = $total_prix + $shipping;

        /* ================= COUPON ================= */
        $couponCode = $request->get('coupon');
        $coupon = null;
        $valeurCoupon = 0;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)
                ->where('valid_until', '>', now())
                ->first();

            if ($coupon) {
                // 🔥 coupon appliqué sur TOTAL (produits + shipping)
                $valeurCoupon = ($total_prix_with_shipping_sans_coupon * $coupon->pourcentage) / 100;
                session([
                    'coupon' => $coupon,
                    'valeurCoupon' => $valeurCoupon,
                ]);
            }
        }
        $sub_total=0;
        // Total final après coupon
        $total_prix_with_shipping = max(
            0,
            $total_prix_with_shipping_sans_coupon - $valeurCoupon
        );
        $sub_total = $total_prix_with_shipping;
        // Total produits après coupon (pour affichage)
        $total_prix = max(
            0,
            $total_prix_with_shipping - $shipping
        );

        /* ================= LOGIQUE DES 3 CAS ================= */
        $can_checkout_with_points = false;
        $can_checkout_mixed = false;
        $reste_a_payer = $total_prix_with_shipping;

        if ($user) {
            // CAS 2 : 100% points (produits) + shipping
            if ($user->points >= $total_points_achat && $total_points_achat > 0) {
                $can_checkout_with_points = true;
                $reste_a_payer = $shipping;
                session([
                    'total_points_achat' => $user->points ,
                ]);
                // CAS 3 : mixte
            } elseif ($user->points > 0 && $user->points < $total_points_achat) {
                $can_checkout_mixed = true;
                $reste_a_payer = max(
                    0,
                    $total_prix_with_shipping - ($user->points )
                );

                session([
                    'total_prix' => $total_prix_with_shipping_sans_coupon,
                    'total_points_achat' => $user->points ,
                ]);
            }
        }

        return view('front.cart', compact(
            'produits',
            'total_prix',
            'total_prix_with_shipping',
            'total_prix_sans_coupon',
            'total_avant_promo',
            'total_points_achat',
            'if_promotion',
            'configs',
            'can_checkout_with_points',
            'can_checkout_mixed',
            'reste_a_payer',
            'coupon',
            'valeurCoupon',
            'total_prix_with_shipping_sans_coupon',
        ));
    }



    public function after_commande()
    {
        return view('front.after_commande');
    }
    public function suggestions(Request $request)
    {
        $key = $request->get('q');

        $produits = \App\Models\produits::where('nom', 'LIKE', "%{$key}%")
            ->where('masquer_produit', false)
            ->limit(20)
            ->get(['id', 'nom', 'photo']);

        return response()->json($produits);
    }


    public function shop(Request $request)
    {

        $key = $request->get('key') ?? null;
        session(['key_recherche' => $key]);

        // Paramètres reçus
        $IDcategorie = $request->get('idSelectedCategory') ?? $request->get('IDcategorie') ?? null;
        $IDsouscategorie = $request->get('IDsouscategorie') ?? null;
        $IDsoussouscategorie = $request->get('IDsoussouscategorie') ?? null;

        $Ordre = $request->get('Ordre') ?? null;
        $Ordre_prix = $request->get('Ordre_prix') ?? null;

        $produits = produits::query();
// ✅ Tri par défaut: order_column (0 à la fin), puis id pour stabilité
        $produits->orderByRaw("order_column = 0 ASC")
            ->orderBy('order_column', 'asc');
        $type = $request->get('type') ?? null;
        $idSelectedBrand = $request->get('idSelectedBrand') ?? null;

        // 🔍 Recherche
        if ($key) {
            $produits->where(function ($q) use ($key) {
                $q->where('nom', 'like', '%' . $key . '%')
                    ->where('masquer_produit', false)
                    ->orWhere('reference', 'like', '%' . $key . '%')
                    ->orWhere('description', 'like', '%' . $key . '%')
                    ->orWhere('prix', 'like', '%' . $key . '%');
            });
        }

        // 🔹 Filtres par catégorie / sous-catégorie / sous-sous-catégorie
        if ($IDcategorie) {
            $produits->where('id_categorie', $IDcategorie);
        }
        if ($IDsouscategorie) {
            $produits->where('id_sous_categorie', $IDsouscategorie);
        }
        if ($IDsoussouscategorie) {
            $produits->whereHas('sousSousCategories', function($q) use ($IDsoussouscategorie) {
                $q->where('sous_souscategories.id', $IDsoussouscategorie);
            });
        }


        // 🔹 Tri
        if ($Ordre) {
            $produits->orderBy('id', $Ordre == 'Desc' ? 'Desc' : 'Asc');
        }
        if ($Ordre_prix) {
            $produits->orderBy('prix', $Ordre_prix == 'Desc' ? 'Desc' : 'Asc');
        }

        $produits = $produits
            ->where('masquer_produit', false)
            ->paginate(25)->appends($request->all());
        $categories = categories::with([
            'souscategories' => fn ($query) => $query->withCount(['produits', 'soussouscategories']),
            'souscategories.soussouscategories' => fn ($query) => $query->withCount('produits'),
        ])->orderBy('order_column', 'asc')->get();
        $max_price = produits::max('prix');
        $marques = Brand::withCount('produits')->get();

        return view('front.shop')
            ->with('produits', $produits)
            ->with('categories', $categories)
            ->with('IDcategorie', $IDcategorie)
            ->with('IDsouscategorie', $IDsouscategorie)
            ->with('IDsoussouscategorie', $IDsoussouscategorie)
            ->with('max_price', $max_price)
            ->with('key_recherche', $key)
            ->with('marques', $marques)
            ->with('idSelectedCategory', $IDcategorie) // on garde cohérent
            ->with('idSelectedBrand', $idSelectedBrand)
            ->with('type', $type);
    }

    public function shop_ajax(Request $request)
    {

        $idCategorieSelecteds = $request->get('idCategorieSelecteds', []);
        $idSousCategorieSelecteds = $request->get('idSousCategorieSelecteds', []);
        $idSousSousCategorieSelecteds = $request->get('idSousSousCategorieSelecteds', []);
        $idMarqueSelecteds = $request->input('idMarqueSelecteds', []);
        $maxPrice = $request->input('maxPrice');
        $minPrice = $request->input('minPrice');
        $maxPrice = is_numeric($maxPrice) ? (float)$maxPrice : null;
        $minPrice = is_numeric($minPrice) ? (float)$minPrice : null;
        // Deuxième slider
        $minPrice1 = $request->input('minPrice1');
        $maxPrice1 = $request->input('maxPrice1');
        $minPrice1 = is_numeric($minPrice1) ? (float)$minPrice1 : null;
        $maxPrice1 = is_numeric($maxPrice1) ? (float)$maxPrice1 : null;

        $key_recherche = $request->input("key_recherche");
        $best_sellers = $request->input("best_sellers");
        $query = produits::query();
        // ✅ Tri par défaut: order_column (0 à la fin), puis id
        $query->orderByRaw("order_column = 0 ASC")
            ->orderBy('order_column', 'asc');
        if ($key_recherche) {
            $query->where(function ($search) use ($key_recherche) {
                $search->where('nom', 'like', '%' . $key_recherche . '%')
                    ->orWhere('reference', 'like', '%' . $key_recherche . '%')
                    ->orWhere('description', 'like', '%' . $key_recherche . '%');
            });
        }
        if ($best_sellers) {
            $query->where('best_sellers', 1);
        }
        if (!empty($idCategorieSelecteds)) {
            $query->whereIn('id_categorie', $idCategorieSelecteds);
        }
        if (!empty($idSousCategorieSelecteds)) {
            $query->whereIn('id_sous_categorie', $idSousCategorieSelecteds);
        }
        if (!empty($idSousSousCategorieSelecteds)) {
            $query->whereHas('sousSousCategories', function($q) use ($idSousSousCategorieSelecteds) {
                $q->whereIn('sous_souscategories.id', $idSousSousCategorieSelecteds);
            });
        }

        if (!empty($idMarqueSelecteds)) {
            $query->whereIn('id_brand', $idMarqueSelecteds);
        }
        if ($minPrice !== null && $maxPrice !== null) {
            $query->whereBetween('prix', [$minPrice, $maxPrice]);
        } elseif ($minPrice !== null) {
            $query->where('prix', '>=', $minPrice);
        } elseif ($maxPrice !== null) {
            $query->where('prix', '<=', $maxPrice);
        }
        // Filtrer par deuxième slider
        if(!is_null($minPrice1)) {
            $query->where('prix', '>=', $minPrice1); // ou une autre colonne si c'est différent
        }
        if(!is_null($maxPrice1)) {
            $query->where('prix', '<=', $maxPrice1);
        }
        $produits = $query
            ->with('promotion')
            ->where('masquer_produit', false)
            ->paginate(32);

        $html = view('components.shop-list', compact('produits'))->render();
        return response()->json([
            "html" => $html,
            "status" => 200,
            "total" => $produits->total()
        ]);
    }

    public function orders()
    {
        $commande = commandes::where('id_user', Auth::id())
            ->orderby('id', "Desc")
            ->get();
        return view('front.orders')
            ->with('commandes', $commande);
    }

    public function produit($id)
    {
        $produit = produits::with(['comments.user'])->find($id);
        if (!$produit) {
            $message = "Produit non disponible!";
            abort(404, $message);
        }

        $ip = request()->ip();
        $agent = request()->header('User-Agent');
        $signature = hash('sha256', $ip . '_' . $agent . '_' . $id);

        // éviter les doublons
        DB::table('views')->updateOrInsert(
            ['signature' => $signature],
            [
                'id_produit' => $id,
                'ip_address' => $ip,
                'signature' => $signature,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $autres = produits::where('id_categorie', $produit->id_categorie)->take(10)->get();
        return view('front.produit')
            ->with('produit', $produit)
            ->with('autres', $autres);
    }

    public function category()
    {
        $categories = categories::all();
        return view('front.category')
            ->with('categories', $categories);
    }


    public function contact()
    {
        return view('front.contact');
    }

    public function contact_post(Request $request){
        //validation
        $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:255',
        ]);
        $new = new contacts();
        $new->nom = $request->nom;
        $new->telephone = $request->telephone;
        $new->email = $request->email;
        $new->message = $request->message;
        $new->save();
        return redirect()->back()->with('success', 'Your message has been sent!');
    }




    public function profile()
    {
        return view('front.profile');
    }


    public function checkout()
    {
        $user = Auth::user();
        $config = config::first();

        $panier = session('panier_front', []);
        $total_prix = 0;

        foreach ($panier as $pan) {
            $produit = produits::find($pan['id_produit']);
            if ($produit) {
                $total_prix += $produit->getPrice() * $pan['quantite'];
            }
        }

        $shipping = $config->frais;
        $montant_sans_coupon = $total_prix + $shipping;

        // 🔥 lire coupon depuis session
        $coupon = session('coupon');
        $valeurCoupon = session('valeurCoupon', 0);

        $montant_final = max(0, $montant_sans_coupon - $valeurCoupon);

        return view('front.checkout', [
            'montant_final' => $montant_final,
            'montant_sans_coupon' => $montant_sans_coupon,
            'coupon' => $coupon,
            'valeurCoupon' => $valeurCoupon,
            'mode_paiement' => 'argent',
            'config' => $config,
            'user' => $user
        ]);
    }
    public function checkoutWithPoints()
    {
        $user = Auth::user();
        $config = config::first();

        return view('front.checkout', [
            'montant_final' => $config->frais,
            'mode_paiement' => 'points',
            'points_utilises' => session('total_points_achat'),
            'config' => $config,
            'user' => $user,
            'coupon' => null, // ✅ IMPORTANT
            'valeurCoupon' => 0, // ✅ IMPORTANT
        ]);
    }
    public function checkoutMixed()
    {
        $user = Auth::user();
        $config = config::first();

        $total_prix = session('total_prix');
        $total_points_achat = session('total_points_achat');

        $shipping = $config->frais;



        $montant_sans_coupon = $total_prix ;

        // 🔥 coupon depuis session
        $coupon = session('coupon');
        $valeurCoupon = session('valeurCoupon', 0);

        $montant_avec_coupon = max(0, $montant_sans_coupon - $valeurCoupon);

        $montant_final = max(0, $montant_avec_coupon - $total_points_achat);
        return view('front.checkout', [
            'montant_final' => $montant_final,
            'montant_sans_coupon' => $montant_sans_coupon,
            'coupon' => $coupon,
            'valeurCoupon' => $valeurCoupon,
            'points_utilises' => $user->points,
            'mode_paiement' => 'mixte',
            'config' => $config,
            'user' => $user
        ]);
    }

    public function favoris()
    {
        return view('front.favoris');
    }

    public function change_password()
    {
        return view('front.change_password');
    }


    public function password_reset(Request $request)
    {
        $token  = $request->get('token') ?? null;
        $user = User::where('token', $token)->first();
        if (!$user) {
            abort(404, "Token introuvable !");
        }
        return view('front.password_reset')
            ->with('token', $token);
    }


    public function error_page()
    {
        return view('front.error-page');
    }

    public function register_send(Request $request)
    {
        return view('front.register_send');
    }


    public function faqs()
    {
        return view('front.faqs');
    }

    public function terms()
    {
        return view('front.terms');
    }

    public function return_policy()
    {
        return view('front.return_policy');
    }
    public function addComment(Request $request)
    {
        try {
            $request->validate([
                'commentaire' => 'required|string',
                'photo' => 'nullable|image|max:2048',
                'product_id' => 'required|integer',
            ]);

            $photoPath = $request->file('photo')
                ? $request->file('photo')->store('comments', 'public')
                : null;

            Comment::create([
                'user_id' => Auth::id(),
                'produit_id' => $request->product_id,
                'commentaire' => $request->commentaire,
                'photo' => $photoPath,
                'valide' => false, // par défaut non validé
            ]);

            return back()->with('success', 'Comment submitted successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

}
