# Import du catalogue

Import effectué depuis C:/Users/Msi/Downloads/catalogue-avec-images-2026-10-07-174915 vers la base locale korean-glam-dubai-2.

11 marques, 3 catégories, 11 sous-catégories, 24 sous-sous-catégories, 411 produits et 20 liens. Les identifiants ont été conservés. Les prix, stocks et relations ont été comparés aux lignes source. Les 422 associations d’images utilisent 409 fichiers uniques. id_promotion et gamme_id sont vidés.

Commande : php artisan catalogue:import "chemin/du/dossier" --dry-run pour valider, puis sans --dry-run pour importer dans des tables vides. php artisan storage:link crée l’accès public aux images. La commande refuse les tables déjà remplies.

Rapport : storage/app/catalogue-imports/20261007-180121-cefdfd7d/report.json.

La sauvegarde avant import annoncée par la commande n’a pas été retrouvée au contrôle final. Une sauvegarde complète après import est conservée dans ce même dossier : database-after.sql. Ce fichier contient des données privées et doit rester hors du répertoire public et du dépôt.
