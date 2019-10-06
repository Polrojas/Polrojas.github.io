var app = new Vue({
    el: '#app',
    data: {
      message: 'Hello Vue!',
      categorias: [
        { id:1, nombre: "Pintura", imagen: "json/pintura.json", styleObject:{ backgroundColor: "#FE693F" }},
        { id:2, nombre: "Cocina", imagen: "json/cocina.json", styleObject:{ backgroundColor: "#E22B71"}},
        { id:3, nombre: "Dibujo", imagen: "json/dibujo.json", styleObject:{ backgroundColor: "#F2517D" }},
        { id:4, nombre: "Construcción", imagen: "json/construccion.json", styleObject:{ backgroundColor: "#FFCF0B" }},
        { id:5, nombre: "Jardinería", imagen: "json/jardineria.json", styleObject:{ backgroundColor: "#B12995" }},
        { id:6, nombre: "Danza", imagen: "json/danza.json", styleObject:{ backgroundColor: "#0099C0" }},
        { id:7, nombre: "Ciencia", imagen: "json/ciencia.json", styleObject:{ backgroundColor: "#00C0B3" }},
        { id:8, nombre: "Fotografía", imagen: "json/fotografia.json", styleObject:{ backgroundColor: "#7E52A9"}}
    ]
    }
  })






  function bodymovinAnimation() {
    var scrollTop = $(document).scrollTop(),
        windowHeight = $(window).height(),
        indexList = [];

    animations.each(function (index) {
        var o = $(this);
        if (o) {
            var top = o.position().top;
            if (scrollTop < top && scrollTop + windowHeight > top + (o.height() * 2)) {
                var anim = bodymovin.loadAnimation({
                    container: this,
                    renderer: 'svg',
                    loop: false,
                    autoplay: true,
                    path: o.attr('src')
                });
                indexList.push(index);
            }

        }
    })

    for (var i = indexList.length - 1; i >= 0; i--)
        animations.splice(indexList[i], 1);
}

$(document).ready(function () {
    animations = $('.animation');
    $(document).bind('scroll', bodymovinAnimation);
    bodymovinAnimation();
});

$(function() {
  $('.bodymovin').each(function() {
      var element = $(this);
      var animation = bodymovin.loadAnimation({
          container: element[0],
          renderer: 'svg',
          rendererSettings: {preserveAspectRatio: 'none' },
          loop: false,
          autoplay: true,
          path: element.data('icon')
      });
  });
});