// Initialize GLightbox if available
document.addEventListener('DOMContentLoaded', function () {
  if (typeof GLightbox !== 'undefined') {
    GLightbox({ selector: '.glightbox' });
  }
  var gs=document.querySelector('.gallery-search-public');
  if(gs){
    var container=gs.closest('.container');
    var items=container?container.querySelectorAll('.row.g-3 > div'):[];
    gs.addEventListener('input',function(){
      var q=gs.value.toLowerCase();
      items.forEach(function(el){
        var img=el.querySelector('img');
        var s=(img?img.getAttribute('src'):'')+'';
        el.style.display=s.toLowerCase().indexOf(q)>-1?'':'none';
      });
    });
  }
  var hero=document.querySelector('.hero-alt');
  if(hero){
    var bgs=hero.querySelectorAll('.hero-bg');
    if(bgs.length>1){
      var idx=0;
      for(var i=0;i<bgs.length;i++){ if(bgs[i].classList.contains('visible')) { idx=i; break; } }
      setInterval(function(){
        bgs[idx].classList.remove('visible');
        idx=(idx+1)%bgs.length;
        bgs[idx].classList.add('visible');
      },5000);
    }
  }
});