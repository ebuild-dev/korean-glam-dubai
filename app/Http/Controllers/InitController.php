<?php

namespace App\Http\Controllers;


use App\Models\Brand;
use App\Models\categories as ModelsCategories;
use App\Models\commandes;
use App\Models\contenu_commande;
use App\Models\produits;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InitController extends Controller
{
    public function init()
    {

        //recuperation des bd
        $content = file_get_contents('db/db_user.json');
        $content_category = file_get_contents('db/db_category.json');
        $content_products = file_get_contents('db/db_products.json');
        $content_category = file_get_contents('db/db_category.json');
        $content_order_head = file_get_contents('db/db_order_head.json');
        $content_order_body = file_get_contents('db/db_order_body.json');
        $content_brands = file_get_contents('db/db_brands.json');
        $content_product_photos = file_get_contents('db/db_product_photos.json');
        //conversion des contenus
        $categorys = json_decode($content_category, true);
        $products = json_decode($content_products, true);
        $data = json_decode($content, true);
        $order_head = json_decode($content_order_head, true);
        $order_body = json_decode($content_order_body, true);
        $brands = json_decode($content_brands, true);
        $product_photos = json_decode($content_product_photos, true);
        //importation des categories
        foreach ($categorys as $key => $category) {
            $cat = new ModelsCategories();
            $cat->nom = $category['subject'];
            $cat->save();
        }



        //importation des marques
        foreach ($brands as $key => $brand) {
            if ($brand['brandname']) {
                $new = new Brand();
                $new->brandname = $brand['brandname'];
                $new->brandurl = Str::slug($brand['brandurl']);
                $new->save();
            }
        }




        //importation des produits
        foreach ($products as $key => $product) {
            $produit = new produits();
            $produit->nom = $product['subject'];
            $produit->description = $product['description'];
            $produit->reference = $product['reference'];
            $produit->howtouse = $product['howtouse'];
            $produit->prix = $product['price'];
            $produit->stock = $product['quantity'];
            $categorie = ModelsCategories::find($product['cat_id']);
            $produit->photos = json_encode($this->get_product_photos($product['id'], $product_photos));
            if ($produit->photos == '[]') {
                $produit->photos = null;
            }else{
                $produit->photo = $this->get_product_photos($product['id'], $product_photos)[0];
            }
            if ($categorie) {
                $produit->id_categorie = $product['cat_id'];
            }
            $produit->save();
        }

        //importation des clients
        foreach ($data as $key => $data) {
            $user = new User();
            $user->email = $data['entername'];
            $user->password = $data['password'];
            $user->nom = $data['name'];
            $user->zipcode = $data['zipcode'];
            $user->phone = $data['phone'];
            $user->city = $data['city'];
            $user->save();
        }


        //importation des commandes
        foreach ($order_head as $key => $order) {
            $user = User::find($order['userid']);
            if ($user) {
                $order_head = new commandes();
                $order_head->id_user = $order['userid'];
                $order_head->email = $order['email'];
                $order_head->phone = $order['phone'];
                $order_head->adresse = $order['adresse'];
                $order_head->trackcode = $order['trackcode'];
                $order_head->note = $order['comments'];
                $order_head->city = $order['city'];
                $order_head->postalcode = $order['postalcode'];
                $order_head->nom = $order['nameorder'];
                $order_head->token = $order['redeclare'];
                $order_head->etat = "confirmé";
                $order_head->save();
            }
        }

        //importation des lignes de commande
        foreach ($order_body as $key => $order) {
            $commande = commandes::find($order['oder_head_id']);
            if ($commande) {
                $produit = produits::find($order['product_id']);
                if ($produit) {
                    $order_body = new contenu_commande();
                    $order_body->id_produit = $produit->id;
                    $order_body->id_commande = $commande->id;
                    $order_body->prix_unitaire = $order['price'];
                    $order_body->prix_total = $order['price'] * $order['quantity'];
                    $order_body->quantite = $order['quantity'];
                    $order_body->benefice = $order['price'] * $order['quantity'];
                    $order_body->save();
                }
            }
        }


        //importation des produits
        return "Les données ont été importés";
    }



    public function get_product_photos($product_id, $product_photos)
    {
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ["Erreur : JSON invalide."];
        }
        $photos = [];
        foreach ($product_photos as $product_photo) {
            if ($product_photo['product_id'] == $product_id) {
                $photos[] = 'produits/'.$product_photo['photourl'];
            }
        }
        if (empty($photos)) {
            return [];
        }
        return $photos;
    }

}
