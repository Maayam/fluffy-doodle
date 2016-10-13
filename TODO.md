I'LL DO IT LATER
=================

#BUGS
======
- when performing a search AFTER adding some plots on the map, the newly added plots appear AFTER the search is performed even when it is not supposed to be in the search result
 
#Ergonomy
====
- Change icon for Plots
- Javascript et server-side script pour l'auto-completion des noms tapés dans la barre de recherche sur la map
- afficher l'échelle des distances
- bouger les boutons zoom/dezoom

#Performance
=============
- empêcher l'insertion d'un plot s'il éxiste déjà un plot à ces coordonnées !
- about poping forms on map click, I should response only JSON and populate the form locally from the JSON data received
- stocker tout les plots déjà chargé une fois dans une db cliente afin de minimiser le nombre de requête et accélérer le temps de chargement.

#clean Code
============
- do things better in the popFormOnClick() function in map.js
