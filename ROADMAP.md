version 1:
=====================================================
-----------------------------------------------------

-ajout d'un plot au clique sur la carte
	X-faire le template du formulaire d'ajout (généré localement ou fetched from server?)
	-Javascript pour pop un formulaire onMapClick (jss/map.js)
	-server-side script pour traiter les données postées par le formulaire et enregistrer une nouvelle entrée dans la db (PlotController.php)
	-afficher une notification de succès

-rechercher un plot par nom
	-Ajouter une barre de recherche dans le template pages/user-map.html.twig (formulaire)
	-server-side script pour renvoyer LE plot trouvé en JSON ou une notif dans le cas ou rien n'a été trouvé
	-JS dans map.js pour pan automatiquement vers le plot trouvé

-----------------------------------------------------
=====================================================
-----------------------------------------------------
-enrichir les plots
	-faire le template d'un plot développé
		-pouvoir ajouter des photos pour ce plot
			-créer une Entité et une table Media
			-formulaire d'upload de photo
			-server-side script pour l'enregistrement des fichiers sur le disque et des emplacements dans la bdd (MediaController)

		-pouvoir visionner les photos pour ce plots
			-much JS (probablement une librairie)
			-server-side script pour servir les photos demandées par la librairie JS (MediaController)

		-pouvoir ajouter des tags à ce plot
			-créer une Entité et une table Tag, et une table de relation (manyToMany)
			-possibilité d'en ajouter à la création du plot (modifier le template de création de plot)
			-possibilité d'en ajouter sur un plot éxistant

		-afficher les tags de ce plot
			(-pouvoir lancer une recherche par tag en cliquant sur un tag?)

-----------------------------------------------------
=====================================================
-----------------------------------------------------
-un peu de modération quand même
	-user account (admin only, créé manuellement)
		-Entité et table
	-template de connexion
	-modifier template map pour ajouter les options d'administration

-----------------------------------------------------
=====================================================