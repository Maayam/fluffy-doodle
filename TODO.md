I'LL DO IT LATER
=================

#BUGS
======
-while filtering with search bar, ALL plots appear on the map after panning.
-when performing a search AFTER adding some plots on the map, the newly added plots appear AFTER the search is performed even when it is not supposed to be in the search result
 
#Ergonomy
====
-notice the user for AJAX loading time when adding a plot from the map
-Change icon for Plots
-Javascript et server-side script pour l'auto-completion des noms tapés dans la barre de recherche sur la map
-set mapView acording to users location
-afficher l'échelle des distances
-bouger les boutons zoom/dezoom
-when performing a search by name, pann the map to all the plots found.

#Security
===============
-validate ajax URL for plots properly (PlotController/findInViewAction) isXMLHttpRequest();
-validate form submission properly (PlotController/ajax_addAction) FIXED

#Performance
=============
-empêcher l'insertion d'un plot s'il éxiste déjà un plot à ces coordonnées !
-about poping forms on map click, I should response only JSON and populate the form locally from the JSON data received
-stocker tout les plots déjà chargé une fois dans une db cliente afin de minimiser le nombre de requête et accélérer le temps de chargement.

#clean Code
============
-do things better in the popFormOnClick() function in map.js
-should I build forms from the form class ? http://symfony.com/doc/current/forms.html#form-creating-form-classes
the link above is useful for reusable components too
