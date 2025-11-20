document.addEventListener('DOMContentLoaded',function(){
  function detectType(v){
    if(/^\s*-?\d+(\.\d+)?\s*$/.test(v)) return 'number';
    if(/^\d{4}-\d{2}-\d{2}$/.test(v)) return 'date';
    if(/^\d{2}:\d{2}(:\d{2})?$/.test(v)) return 'time';
    return 'text';
  }
  function parseByType(v,t){
    if(t==='number') return parseFloat(v.replace(/[^0-9.-]/g,'')) || 0;
    if(t==='date') return new Date(v).getTime() || 0;
    if(t==='time'){var p=v.split(':');return (parseInt(p[0])||0)*3600+(parseInt(p[1])||0)*60+(parseInt(p[2]||'0')||0);} 
    return v.toLowerCase();
  }
  function enableSorting(table){
    var ths=table.querySelectorAll('thead th');
    ths.forEach(function(th,idx){
      th.style.cursor='pointer';
      th.addEventListener('click',function(){
        var tbody=table.tBodies[0];
        var rows=Array.from(tbody.querySelectorAll('tr'));
        var sample=rows.find(function(r){return r.cells[idx] && r.cells[idx].innerText.trim().length>0;});
        var val=sample?sample.cells[idx].innerText.trim():'';
        var type=detectType(val);
        var asc=th.dataset.order!=='asc';
        ths.forEach(function(h){h.removeAttribute('data-order');});
        th.setAttribute('data-order',asc?'asc':'desc');
        rows.sort(function(a,b){
          var va=parseByType(a.cells[idx]?a.cells[idx].innerText.trim():'',type);
          var vb=parseByType(b.cells[idx]?b.cells[idx].innerText.trim():'',type);
          if(va<vb) return asc?-1:1;
          if(va>vb) return asc?1:-1;
          return 0;
        });
        var frag=document.createDocumentFragment();
        rows.forEach(function(r){frag.appendChild(r);});
        tbody.appendChild(frag);
      });
    });
  }
  function attachSearch(input,table){
    var fn=function(){
      var q=input.value.toLowerCase();
      Array.from(table.tBodies[0].rows).forEach(function(r){
        var t=r.innerText.toLowerCase();
        r.style.display=t.indexOf(q)>-1?'':'none';
      });
    };
    input.addEventListener('input',fn);
  }
  document.querySelectorAll('.admin-ui table').forEach(function(t){ if(t.tHead && t.tBodies.length) enableSorting(t); });
  document.querySelectorAll('.table-search').forEach(function(inp){
    var table=document.querySelector(inp.dataset.target)||inp.closest('.table-responsive')?.querySelector('table');
    if(table) attachSearch(inp,table);
  });
  var gSearch=document.querySelector('.gallery-search');
  if(gSearch){
    var items=document.querySelectorAll('.row.g-3 > div');
    gSearch.addEventListener('input',function(){
      var q=gSearch.value.toLowerCase();
      items.forEach(function(el){
        var img=el.querySelector('img');
        var s=(img?img.getAttribute('src'):'')+'';
        s=s.toLowerCase();
        el.style.display=s.indexOf(q)>-1?'':'none';
      });
    });
  }
});