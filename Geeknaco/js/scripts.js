$(document).ready(function(){
    $("a").on('click', function(event) {
      if (this.hash !== "") {
        event.preventDefault();
        var hash = this.hash;
        $('html, body').animate({
          scrollTop: $(hash).offset().top
        }, 300, function(){
             window.location.hash = hash;
        });
      } 
    });
  });


  $(document).ready(function(){
    $("button").click(function(){
        $("#home").fadeIn();
    });
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


$(function() {
    $('.bodymovin-loop').each(function() {
        var element = $(this);
        var animation = bodymovin.loadAnimation({
            container: element[0],
            renderer: 'svg',
            rendererSettings: {preserveAspectRatio: 'none' },
            loop: true,
            autoplay: true,
            path: element.data('icon')
        });
    });
});


// sets default vlaue of surrounding div to none so it doesnt appear
let animationDiv = document.getElementById('scrollingArea')
animationDiv.style.display = "none"

// need to pass in the div where you want the item to load and the file location
function loader(div, pathLocation) {
   let animation = bodymovin.loadAnimation({
       container: div,
       renderer: "svg",
       loop: 1,
       autoplay: true,
       path: pathLocation
   });
   animation.play();
}
window.addEventListener('scroll', () => {
   // can set scroll height by changing the number
   let scrollHeightPercent = document.documentElement.scrollHeight * 0.08
   let currentPOS = document.documentElement.scrollTop || document.body.scrollTop
       // once the scroll height has gone past the % stated abvoe it will make the style appear
   if (currentPOS >= scrollHeightPercent) {
       let animationDiv = document.getElementById('scrollingArea');
       if (animationDiv.style.display == 'none') {
           // stuff here
           animationDiv.style.display= ""
           let bodyMotion1 = document.getElementById('lottie-scroll-1');
           loader(bodyMotion1, "logo.json")
       };
   };
});