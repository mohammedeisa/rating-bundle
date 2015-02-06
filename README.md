MeisaRatingBundle - The Ultimate Rating Bundle
========================================================
About the bundle
========================================================
MEisaRatingBundle embeds a rating stars into any part of your application using just one line of code.
Installation:
==
run this command:<br>
```$ composer require mohammedeisa/rating-bundle```<br>
or add this line to your composer.json<br>
```"mohammedeisa/rating-bundle": "~2.0"```
Add the bundle to your registered bundles:
==
```new Meisa\RatingBundle\MeisaRatingBundle()```
Include these files into your template
==
```
'<link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">'
<link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet">
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('bundles/meisarating/css/rating.css') }}"/>
*<script type="text/javascript" src="{{ asset('bundles/meisarating/js/rating.js') }}"></script>
*<script src="{{ asset('bundles/fosjsrouting/js/router.js') }}"  type="text/javascript" ></script>
*<script src="{{ path('fos_js_routing_js', {"callback": "fos.Router.setData"}) }}"  type="text/javascript" ></script>
```
Import bundle configurations:
==
```- { resource: @MeisaRatingBundle/Resources/config/config.yml }```
Enable the bundle routing:
==
```
meisa_rating:
    resource: "@MeisaRatingBundle/Controller/"
    type:     annotation
    prefix:   /
```
Usage:
==
```{{ "rating_1"|show_rating|raw }}```<br>
`change "rating_1" so that it should be unique over your application.`