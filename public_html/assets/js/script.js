
  const PROJECTS = {
    educacion: {
      title: "Educación Transformadora",
      estado: "enprogreso", // enprogreso | pendiente | finalizado
      main: "https://images.unsplash.com/photo-1761048370427-494e01030f97?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjaGlsZHJlbiUyMGVkdWNhdGlvbiUyMGNvbW11bml0eXxlbnwxfHx8fDE3NjQwNDE3Mzl8MA&ixlib=rb-4.1.0&q=80&w=1080",
      thumbs: ["assets/img/edu1.jpeg","assets/img/edu2.jpeg","assets/img/edu3.jpeg"],
      paragraphs: [
        "Nuestra iniciativa Educación Transformadora trabaja con niños y adolescentes en situaciones de vulnerabilidad para ofrecer apoyo escolar y talleres formativos.",
        "Creamos espacios seguros de aprendizaje y acompañamiento, orientados a mejorar las oportunidades académicas y personales.",
        "Colaboramos con escuelas, voluntarios y aliados para dar sostenibilidad a nuestras acciones."
      ],
      
    },
    desarrollo: {
      title: "Desarrollo Comunitario",
      estado: "enprogreso",
      main: "assets/img/desarrollo_main.jpg",
      thumbs: ["assets/img/desarrollo_1.jpg","assets/img/desarrollo_2.jpg","assets/img/desarrollo_3.jpg"],
      paragraphs: [
        "El Desarrollo Comunitario es el corazón de nuestra misión. Trabajamos para fortalecer el tejido social y promover oportunidades para las familias.",
        "A través de talleres, encuentros y proyectos productivos impulsamos capacidades locales y fomentamos la autonomía.",
        "Buscamos soluciones sostenibles que permitan mejorar condiciones de vida y crear redes de apoyo permanentes."
      ],
      
    },
    juventud: {
      title: "Juventud con Futuro",
      estado: "pendiente",
      main: "assets/img/juventud_main.jpg",
      thumbs: ["assets/img/juventud_1.jpg","assets/img/juventud_2.jpg","assets/img/juventud_3.jpg"],
      paragraphs: [
        "Juventud con Futuro ofrece talleres de formación técnica y liderazgo para jóvenes de la comunidad.",
        "Fomentamos habilidades laborales y proyectos juveniles que permitan nuevas oportunidades.",
        "Trabajamos con empresas y mentores para crear puentes hacia el empleo y el emprendimiento."
      ],
     
    }
  };

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