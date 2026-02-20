

  // util: get project key from URL (?project=desarrollo) default 'desarrollo'
  function getProjectKey(){
    const params = new URLSearchParams(window.location.search);
    const p = params.get('project');
    return p && PROJECTS[p] ? p : 'desarrollo';
  }

  // render page from project data
  function renderProject(key){
    const data = PROJECTS[key];
    document.getElementById('projectTitle').textContent = data.title;

    // estado badge
    const badge = document.getElementById('estadoBadge');
    badge.className = 'estado-badge';
    if(data.estado === 'enprogreso') badge.classList.add('estado-enprogreso'), badge.textContent = 'En progreso';
    else if(data.estado === 'pendiente') badge.classList.add('estado-pendiente'), badge.textContent = 'Pendiente';
    else badge.classList.add('estado-finalizado'), badge.textContent = 'Finalizado';

    // imagen principal
    const mainImg = document.getElementById('imgPrincipal');
    mainImg.src = data.main;

    // galeria thumbs
    const gal = document.getElementById('galeriaThumbs');
    gal.innerHTML = '';
    data.thumbs.forEach((t, i) => {
      const img = document.createElement('img');
      img.src = t;
      img.className = 'thumb' + (i===0 ? ' active' : '');
      img.alt = data.title + ' foto ' + (i+1);
      img.addEventListener('click', () => {
        document.getElementById('imgPrincipal').src = t;
        gal.querySelectorAll('.thumb').forEach(x => x.classList.remove('active'));
        img.classList.add('active');
      });
      gal.appendChild(img);
    });

    // sobre el proyecto (parrafos)
    const sobre = document.getElementById('sobreContenido');
    sobre.innerHTML = '';
    data.paragraphs.forEach(p => {
      const pEl = document.createElement('p');
      pEl.textContent = p;
      sobre.appendChild(pEl);
    });

    

    // marcar tab activo
    document.querySelectorAll('.project-tab').forEach(el => el.classList.remove('active'));
    const tab = document.querySelector('.project-tab[data-project="'+key+'"]');
    if(tab) tab.classList.add('active');
  }

  // inicializar
  document.addEventListener('DOMContentLoaded', () => {
    const key = getProjectKey();
    renderProject(key);

   
  });

  document.getElementById("btnDonar").addEventListener("click", function () {

    const params = new URLSearchParams(window.location.search);
    const project = params.get("project") || "desarrollo";

    window.location.href = "donar.html?project=" + project;

});