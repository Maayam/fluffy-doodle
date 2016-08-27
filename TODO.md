I'LL DO IT LATER
=================

#UI
====
-notice the user for AJAX loading time when adding a plot from the map
-Change icon for Plots

#Security
===============
-validate form submission properly (PlotController/ajax_addAction)
-validate ajax URL for plots properly (PlotController/findInViewAction)

#Performance
=============
-empêcher l'insertion d'un plot s'il éxiste déjà un plot à ces coordonnées !
-about poping forms on map click, I should response only JSON and populate the form locally from the JSON data received


#clean Code
============
-do things better in the popFormOnClick() function in map.js

#Divers
=========
-harmoniser les noms des actions...
-about poping forms on map click, it would be good to pop a custom context-menu instead. I keep accidentaly popping forms on the map... 
- plotController > ajax_getForm, must be accessed only from an AJAX request
-should I build forms from the form class ? http://symfony.com/doc/current/forms.html#form-creating-form-classes
the link above is useful for reusable components too
-notifier l'utilisateur si aucun plot ne peut être chargé
-Javascript et server-side script pour l'auto-completion des noms tapés dans la barre de recherche sur la map
-set mapView acording to users location
-afficher l'échelle des distances
-bouger les boutons zoom/dezoom

