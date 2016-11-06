en tant que **visiteur**, je souhaites pouvoir:

	* parcourir la map
	* Afficher un plot:
		* Afficher la description
		* voir les photos du plot
		* voir qui a dansé ici et leurs perfs (plusieurs niveau de détail)
		* voir les perfs qui ont été faites à ce plot (par nom et/ou par user, par date)
		* consulter les profils utilisateurs au clic sur le nom d'un odorite
		* une popup qui suit le curseur au hover d'un nom utilisateur (comme sur fb)
	* rechercher un plot
		* selon un nom de plot
		* les plots qui ont tel tag
		* recherche avancée
			* les plots où une ou plusieurs personnes ont fait une performance
			* les plots où une certaine performance a été faite (par ex: la chanson Hide and seek )
			* je peux indiquer une zone de recherche pour que la carte défile automatiquement à cet endroit lorsque je fais ma recherche (par ex: France, Japon, Tokyo, Pays de la loire)
	* rechercher un utilisateur (pas sur la carte mais en liste)
	* rechercher des performances (pas sur la carte, lister les perfs selon un mot clé quoi)
	* m'inscrire
	* me connecter
	* Si je tente de faire une action qui requiert d'être connecté à un compte, le site me propose un formulaire de connexion/inscription sans rechargement de la page. (ajax would be nice)
	* certaines actions requirent que le compte soit validé par mail (ajout de photos, de plot, de perf)

en tant qu'**utilisateur** connecté et validé, je souhaites pouvoir:
	
	* faire tout ce qu'un visiteur peut faire
	* ajouter des plots sur la carte
	* ajouter une performance à un plot (titre, lien youtube, lien nico, date...)
	* ajouter des photos à un plot
	* éditer mon profil
		* le pseudo, la description/présentation
		* age?
		* avatar
		* role (odorite, cameraman, fan)
		* contacts (résaux sociaux, réseaux vidéos, mail, site perso et etc...)
		* gérer mes performances (lister, ajouter, éditer, masquer/rendre privé, supprimer)
			* changer, détacher le plot d'une performance
			* ajouter, supprimer des performers d'une perf (collab)
		* fil d'actualité
			* afficher les derniers tweets, vidéos, post fb, ou autre truc (selon ce qui est renseigné dans le profil et la configuration)
	* marquer un plot comme favoris (on le retrouve dans le profil dans l'onglet favoris, et il apparait différement sur la carte)
	* marquer un user comme favoris
	* marquer une perf comme favorite

Questionement sur les règles d'intégrité:
	* une **Perf** a AU MOINS UN **User** (performer)
	* une **Perf** peut ne pas avoir de vidéos associées
	* une **Perf** a forcément une date qui peut-être antérieure ou postérieure à la date du jour
	* plusieurs **Perfs** peuvent avoir une même vidéo? ou non? ou osef?
	* une **Perf** n'a qu'un **Plot**