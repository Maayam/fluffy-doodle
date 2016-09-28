=====================================================
----------------------------------------------------------
* enrichir les plots
	* faire le template d'un plot développé
		* pouvoir ajouter des photos pour ce plot
			* créer une Entité et une table Media
			* formulaire d'upload de photo
			* server-side script pour l'enregistrement des fichiers sur le disque et des emplacements dans la bdd (MediaController)
		* pouvoir visionner les photos pour ce plots
			* much JS (probablement une librairie)
			* server-side script pour servir les photos demandées par la librairie JS (MediaController)
		* pouvoir ajouter des tags à ce plot
			* créer une Entité et une table Tag, et une table de relation (manyToMany)
			* possibilité d'en ajouter à la création du plot (modifier le template de création de plot)
			* possibilité d'en ajouter sur un plot éxistant
		* afficher les tags de ce plot
			(pouvoir lancer une recherche par tag en cliquant sur un tag?)

* Users
	* Register_page [GET /user/register]
		* routing + controller method
		* make template
		* posting user to the database success
		* get form via ajax
	* post user [POST /user/register]
	* display one profile [/user/{name}]
	* list profiles [/user]
	* authentication_page [GET /user/login] (ajax would be nice)
	* authenticate [POST /user/login] --> instantiate session
	* update navbar
		* create navbar partial instead of coding it in map.html.twig
		* (IF SESSION) display "logout" (ELSE) display "connect/register"
	* as a user, edit my own profile
	* mark plots as "danced it"
	* manage my plots



----------------------------------------------------------
=====================================================
----------------------------------------------------------
# Compte utilisateurs

en tant que **visiteur**, je souhaites pouvoir:
	* parcourir la map
	* voir les différentes informations pour les plots
	* consulter les profils utilisateurs de façon restreinte
	* m'inscrire (create User)
	* me connecter

en tant qu'**utilisateur**, je souhaites pouvoir:
	* marquer les plots ou j'ai déjà dansé
	* gérer les plots ou j'ai dansé (lister, supprimer, sur mon profil)
	* éditer mon profil
	* rechercher un utilisateur et consulter son profil

Entity: User
	* **name**
	* **password**
	* **mail**

