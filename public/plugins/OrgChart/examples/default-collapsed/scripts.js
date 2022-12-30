'use strict';

(function($){

  $(function() {

    var datascource = {
      "id": "1",
        "name": "Admin",
        "title": "Advogado Pleno",
        "className": "top-level",
        "children": [
          {
            "id": "2",
            "name": "Daniel",
            "title": "Não possui cargo",
            "className": "top-level",
            "children": [],
          },
        ],    
    };

    $('#chart-container').orgchart({
      'data' : datascource,
      'nodeContent': 'title'
    });

  });

})(jQuery);