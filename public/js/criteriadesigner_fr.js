(function($) {

	$.fn.criteriadesigner.displayText = {"AND": "ET", "OR": "OU", "NAND": "NON ET", "NOR": "NON OU", "Group": "Groupe", "Element": "Element", "Remove": "Supprimer"};

  	$.fn.criteriadesigner.categoriesConditions = {
		"id": [ "connecté", "non connecté", "égal", "différent"],
		"bool" : [ "vrai", "faux"],
		"int": [ "égal", "différent", "supérieur strictement", "supérieur ou égal", "inférieur strictement", "inférieur ou égal", "entre", "n'est pas entre", "commence par", "ne commence pas par", "contient", "ne contient pas",
        		"fini par", "ne fini pas par", "parmi", "n'est pas parmi", "vide", "n'est pas vide"],
		"string": [ "égal", "différent", "commence par", "ne commence pas par", "contient", "ne contient pas",
        		"fini par", "ne fini pas par", "parmi", "n'est pas parmi", "vide", "n'est pas vide"],
		"list": ["égal", "différent", "commence par", "ne commence pas par", "contient", "ne contient pas",
        		"fini par", "ne fini pas par","parmi", "n'est pas parmi", "vide", "n'est pas vide"],
		"date": [ "égal", "différent", "supérieur strictement", "supérieur ou égal", "inférieur strictement", "inférieur ou égal", "entre", "n'est pas entre", "vide", "n'est pas vide",
		  		"aujourd'hui", "hier", "demain",
		  		"semaine dernière", "plutôt cette semaine", "cette semaine", "plutard cette semaine", "semaine suivante",
		  		"mois dernier", "plutôt ce mois", "mois actuel", "plutard ce mois", "mois suivant",
		  		"plutôt cette année", "cette année", "plutard cette année"],
  		"collection" : [ "existe", "n'existe pas", "tous", "égal", "différent", "supérieur strictement", "supérieur ou égal", "inférieur strictement", "inférieur ou égal", "entre", "n'est pas entre"]
	};

})(jQuery);