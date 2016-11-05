* Users
	* display one profile [/user/{name}] (update search bar, exact and LIKE search)
	* authentication_page [GET /user/login] (ajax would be nice)
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
