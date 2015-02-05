MeisaRatingBundle - The Ultimate Rating Bundle
========================================================

About the bundle
----------------
MohammedEisaRatingBundle embeds a rating stars into any part of your application using just one line of code.

Installation:
-------------
run this command
$ composer require mohammedeisa/rating-bundle
or add this line to your composer.json
"mohammedeisa/rating-bundle": "2.0.*@dev",

Add the bundle to your registered bundles:
--------------------------------------------
new Meisa\RatingBundle\MeisaRatingBundle()
========================================================

include these files into your template
<link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">
<link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet">
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('bundles/meisarating/css/rating.css') }}"/>
*<script type="text/javascript" src="{{ asset('bundles/meisarating/js/rating.js') }}"></script>
*<script src="{{ asset('bundles/fosjsrouting/js/router.js') }}"  type="text/javascript" ></script>
*<script src="{{ path('fos_js_routing_js', {"callback": "fos.Router.setData"}) }}"  type="text/javascript" ></script>
    

==================================
- { resource: @MeisaRatingBundle/Resources/config/config.yml }
================================
Usage:
------
{{ "rating_1"|show_rating|raw }}

change "rating_1" so that it should be unique over your application.