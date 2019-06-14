var animation = bodymovin.loadAnimation({
  container: document.getElementById('background-orange-start'),
  renderer: 'svg',
  loop: false,
  autoplay: true,
  path: 'background-orange-start.json'
})

var animation = bodymovin.loadAnimation({
  container: document.getElementById('logo-center'),
  renderer: 'svg',
  loop: false,
  autoplay: true,
  path: 'logo.json'
})

var animation1 = bodymovin.loadAnimation({
  container: document.getElementById('background-gradient'),
  renderer: 'svg',
  loop: true,
  autoplay: false,
  path: 'background-gradient.json'
})
setTimeout(function(){ animation1.play(); }, 4000);