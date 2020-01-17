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
          loop: element.data('loop'),
          autoplay: element.data('aplay'),
          path: element.data('icon')
      });
  });
});

/*
window.addEventListener("load",function (){
    const loader = document.querySelector(".loader");
    loader.className += " hidden";
  })*/