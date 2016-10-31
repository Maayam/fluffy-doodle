* Users
	* Register_page [GET /user/signup] X
		* routing + controller method X
		* make template X
		* posting user to the database success X
		* get form via ajax X
	* post user [POST /user/register] X
	* display one profile [/user/{name}] (update search bar, exact and LIKE search)
	* authentication_page [GET /user/login] (ajax would be nice)
	* update navbar
		* create navbar partial instead of coding it in map.html.twig X
		* (IF SESSION) display "logout" (ELSE) display "connect/register" X
	* as a user, edit my own profile
	* mark plots as "danced it"
	* manage my plots


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
