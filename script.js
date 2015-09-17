$(document).ready(function() {

    var mainWidth = 10000;                                         // these need to be 2 to 1 like the viewport (400 x 200)
    var mainHeight = 5000;

    var uml = joint.shapes.uml;

    var graph = new joint.dia.Graph();

    var paper = new joint.dia.Paper({
        el: $('#myholder'),
        width: mainWidth,
        height: mainHeight,
        gridSize: 1,
        model: graph,
        async: { batchSize: 5 },
        napLinks: true
    });

    var paper2 = new joint.dia.Paper({
        el: $('#myholder-small'),
        width: 400,
        height: 200, 
        model: graph,
        gridSize: 1,
        async: { batchSize: 5 },
        snapLinks: true
    });

    var rect = new joint.shapes.basic.Rect({
        position: { x: 0, y: 0 },
        size: { width: $(window).width(), height: $(window).height() }
    });

    rect.attr({
        rect: { fill: '#EDEDED', rx: 5, ry: 5, stroke: 'none' }
    });


    graph.addCell(rect);

    paper.findViewByModel(rect).remove();

    paper.scale(0.8);

    paper2.scale(400/mainWidth);                                // this is where the viewport is scaled
    paper2.$el.css('pointer-events', 'none');

    $.getJSON('./data.json', function(j) {
        fillup(j);
    });


    var fillup = function(data) {

        _.each(data.nodes, function(p, i) {

            var num = p.att.split(';').length + p.meth.split(';').length;
            var high = 100;
            var stk, bkgd;
            var last;

            if (num >= 6) {
                high = high + num * 5;
            }

            if (p.type === 'blue') {                           // 1st cell doesn't have a number id, so i put it offstage, need to find better solution !
                bkgd = 'rgba(48, 208, 198, 0.1)';
                stk = 'rgba(48, 208, 198, 0.5)';

            } else if (p.type === 'darkred') {
                bkgd = 'rgba(255, 55, 55, 0.1)';
                stk = 'rgba(255, 20, 20, 0.5)';

            } else if (p.type === 'red') {
                bkgd = 'rgba(219, 97, 166, 0.1)';
                stk = 'rgba(208, 47, 138, 0.5)';

            } else if (p.type === 'darkgreen') {
                bkgd = 'rgba(0, 150, 0, 0.1)';
                stk = 'rgba(0, 120, 0, 0.5)';

            } else if (p.type === 'green') {
                bkgd = 'rgba(105, 219, 47, 0.1)';
                stk = 'rgba(58, 208, 138, 0.5)';
            }

            // randomizer
            // var xx, yy;
            // xx = Math.floor(Math.random() * (mainWidth));
            // yy = Math.floor(Math.random() * (mainHeight));
            //console.log(xx);

            last = graph.addCell(new uml.Class({
                id: i,
                //position: { x: xx, y: yy },
                position: { x: p.x, y: p.y },
                size: { width: 250, height: high },
                name: p.name,
                attributes: p.att.split(';'),
                methods: p.meth.split(';'),                  // separate with ;

                attrs: {
                    '.uml-class-name-rect': {
                        fill: bkgd,
                        stroke: stk,
                        'stroke-width': 1.5
                    },
                    '.uml-class-attrs-rect, .uml-class-methods-rect': {
                        fill: bkgd,
                        stroke: stk,
                        'stroke-width': 1.5
                    },
                    '.uml-class-attrs-text': {
                        ref: '.uml-class-attrs-rect',
                        'ref-y': 0.5,
                        'y-alignment': 'middle'
                    },
                    '.uml-class-methods-text': {
                        ref: '.uml-class-methods-rect',
                        'ref-y': 0.5,
                        'y-alignment': 'middle'
                    }
                }

            }));

            // } else if (p.type === 'abstract') {
            //     graph.addCell(new uml.Abstract({
            //         id: i,
            //         position: { x: p.x, y: p.y },
            //         size: { width: 250, height: 100 },
            //         name: p.name,
            //         attributes: p.att.split(';'),
            //         methods: p.meth.split(';')                 // separate with ;
            //     }));
            // }
        });

        _.each(data.links, function(p, i) {

            graph.addCell(new uml.Generalization({
                source: { id: graph.getCell(p.source) }, 
                target: { id: graph.getCell(p.target) } 
            }));        
              
        });
        
        // // randomizer
        // console.log(data.nodes.length);
        // for (var i = 0; i < data.nodes.length; i++) { 

        //     var r = Math.floor(Math.random() * (data.nodes.length - 1));
        //     var r2 = Math.floor(Math.random() * (data.nodes.length - 1));

        //     graph.addCell(new uml.Generalization({
        //         source: { id: graph.getCell(r) }, 
        //         target: { id: graph.getCell(r2) } 
        //     }));        
              
        // }

    };


    $( window ).scroll(function() {
        rect.set('position', { x: $(window).scrollLeft(), y: $(window).scrollTop()});
    });
});