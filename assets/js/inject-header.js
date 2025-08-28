// NAWA header injector + style overrides
(function(){
  function injectStyles(){
    var css = `:root{--nawa-navy:#0F172A;--nawa-gold:#EAB308;}
      body{background:#fff !important;color:var(--nawa-navy) !important;}
      h1,h2,h3,h4,h5,h6{color:var(--nawa-navy) !important;}
      a{color:var(--nawa-navy);} a:hover{color:#0b1222;}
      .header,.header-6,.header-8,.menu-area{background:#ffffff !important;}
      .main-menu ul li>a{color:var(--nawa-navy) !important;}
      .main-menu ul li>a:hover{color:var(--nawa-gold) !important;}
      .rr-btn,.btn,.btn-primary{background:var(--nawa-navy) !important;border-color:var(--nawa-navy) !important;color:#fff !important;}
      .rr-btn:hover,.btn:hover{background:#0c1223 !important;border-color:#0c1223 !important;}
      .price-bg,.badge,.highlight{background:var(--nawa-gold) !important;}
      .color-theme-primary,.text-primary{color:var(--nawa-navy) !important;}`;
    var s = document.createElement('style');
    s.setAttribute('data-nawa','overrides');
    s.appendChild(document.createTextNode(css));
    document.head.appendChild(s);
  }

  function replaceHeader(){
    var header = document.querySelector('header');
    fetch('assets/partials/header.html')
      .then(r => r.text())
      .then(html => {
        if(header){
          header.outerHTML = html;
        } else {
          document.body.insertAdjacentHTML('afterbegin', html);
        }
      })
      .catch(err => console.error('Header inject error:', err));
  }

  if (document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', function(){
      injectStyles();
      replaceHeader();
    });
  } else {
    injectStyles();
    replaceHeader();
  }
})();