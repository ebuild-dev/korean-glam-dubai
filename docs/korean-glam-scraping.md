# Import du catalogue Korean Glam

La commande récupère `/shop`, conserve les cookies de session et le jeton CSRF,
puis parcourt `/shop/ajax?page=N` sans filtres. Chaque fiche `/produit/{id}`
est enregistrée dans `scraped_products`, dans la base définie par `.env`.

```powershell
php artisan migrate --path=database/migrations/2026_10_07_000001_create_scraped_products_table.php
php artisan catalog:scrape-korean-glam
```

Après interruption ou erreur :

```powershell
php artisan catalog:scrape-korean-glam --resume
```

Une exécution sans `--resume` actualise les fiches déjà présentes. La clé unique
`source, source_product_id` empêche les doublons. Aucun produit existant n'est
supprimé. Les données sont conservées dans une table dédiée ; cette commande
n'alimente pas automatiquement la table commerciale `produits`.

Champs : identifiant et URL source, nom, description complète disponible, prix
actuel et prix barré, devise, marque lorsqu'elle est publiée, stock lorsqu'il est
visible, disponibilité, URL des images et données structurées originales.
Les images sont référencées par URL, sans téléchargement. Les champs non
publiés restent nuls ; le mode d'emploi est récupéré uniquement lorsqu'un bloc
`howtouse` existe.

Le rapport `storage/app/korean-glam-scrape-report.json` indique le total annoncé,
les identifiants découverts, les imports réussis et les erreurs. Le code de sortie
est non nul si une fiche échoue ou si le total ne correspond pas. La pause par
défaut est de 500 ms entre requêtes ; les erreurs HTTP sont réessayées deux fois.
Un accès HTTPS sortant au site est nécessaire.

Validation du parseur :

```powershell
php vendor/bin/phpunit tests/Unit/KoreanGlamParserTest.php
```
