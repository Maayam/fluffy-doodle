odottemita_project
==================

## Dependancies

* Imagick

## Installation

Create folders web/uploads/pictures/thumbs or modify thumbs parameters in app/config/parameters.yml. Don't forget to give writing rights to this directory.
If you modify the default uploads/pictures/thumbs for the default location, edit config.yml and modifiy twig.globals

A Symfony project created on August 25, 2016, 4:16 pm.
... well yeah.

## Translations

First you need to build/update the catalog with :
`php bin/console translation:update [XX] --force`

Where XX is the locale you want to edit.

Then you just need to edit the file 
`app/Resources/translations/messages.XX.yml`

If the locale isn't supported yet, we need to add it to the different controllers and add the local in the route annotation
`requirements={"_locale"="en|fr|XX"}`



## Thanks

* [lightSlider](https://github.com/sachinchoolur/lightslider)
* [easy-button](https://github.com/CliffCloud/Leaflet.EasyButton)
* [flag-icon-css](https://github.com/lipis/flag-icon-css)
