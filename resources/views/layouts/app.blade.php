<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0e2137">
    <title>@yield('title', 'Panel de control') · SGHI</title>
    <link rel="stylesheet" href="{{ asset('assets/sghi.css') }}">
    @stack('head')
</head>
<body>
<svg class="svg-defs" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
    <symbol id="i-dashboard" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></symbol>
    <symbol id="i-lab" viewBox="0 0 24 24"><path d="M9 3h6M10 3v7l-6.5 9A2 2 0 0 0 5.1 22h13.8a2 2 0 0 0 1.6-3L14 10V3M8 15h8"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24"><circle cx="9" cy="8" r="4"/><path d="M2 21v-2a7 7 0 0 1 14 0v2M16 4a4 4 0 0 1 0 8M18 15a6 6 0 0 1 4 6"/></symbol>
    <symbol id="i-calendar" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4M17 3v4M3 10h18M8 15h3M14 15h3"/></symbol>
    <symbol id="i-file" viewBox="0 0 24 24"><path d="M6 3h8l5 5v13H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2ZM14 3v6h5M8 14h8M8 18h6"/></symbol>
    <symbol id="i-arrow" viewBox="0 0 24 24"><path d="M5 12h14m-6-6 6 6-6 6"/></symbol>
    <symbol id="i-spark" viewBox="0 0 24 24"><path d="m12 2 2.3 7.7L22 12l-7.7 2.3L12 22l-2.3-7.7L2 12l7.7-2.3L12 2Z"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></symbol>
    <symbol id="i-menu" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></symbol>
    <symbol id="i-activity" viewBox="0 0 24 24"><path d="M2 12h5l3-7 4 14 3-7h5"/></symbol>
    <symbol id="i-chevron" viewBox="0 0 24 24"><path d="m9 6 6 6-6 6"/></symbol>
    <symbol id="i-trash" viewBox="0 0 24 24"><path d="M4 7h16M9 7V4h6v3m-9 0 1 14h10l1-14M10 11v6m4-6v6"/></symbol>
    <symbol id="i-plus" viewBox="0 0 24 24"><path d="M12 4v16M4 12h16"/></symbol>
    <symbol id="i-layers" viewBox="0 0 24 24"><path d="m12 2 9 5-9 5-9-5 9-5Z M3 12l9 5 9-5M3 17l9 5 9-5"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24"><path d="M12 2 4 6v6c0 5 3.5 8.5 8 10 4.5-1.5 8-5 8-10V6l-8-4Z M9 12l2 2 4-4"/></symbol>
    <symbol id="i-play" viewBox="0 0 24 24"><path d="m8 5 11 7-11 7V5Z"/></symbol>
    <symbol id="i-git" viewBox="0 0 24 24"><circle cx="6" cy="5" r="2"/><circle cx="18" cy="5" r="2"/><circle cx="12" cy="19" r="2"/><path d="M6 7v6c0 3 6 1 6 4M18 7v6c0 3-6 1-6 4"/></symbol>
    <symbol id="i-heart" viewBox="0 0 24 24"><path d="M20 8.7c0 5-8 11-8 11s-8-6-8-11a4.7 4.7 0 0 1 8-3.4 4.7 4.7 0 0 1 8 3.4Z"/></symbol>
</svg>
<div class="shell">
    <div class="mobile-shade" id="mobile-shade" hidden></div>
    <aside class="sidebar" id="sidebar" aria-label="Navegación principal">
        <a class="brand" href="{{ route('dashboard.index') }}" aria-label="SGHI — Inicio">
            <span class="brand-mark"><svg class="icon"><use href="#i-heart"/></svg></span>
            <span><strong>SGHI<span class="brand-dot">.</span></strong><small>Hospital inteligente</small></span>
        </a>
        <div class="sidebar-divider"></div>
        <div class="nav-label">PLATAFORMA</div>
        <nav class="sidebar-nav">
            <a class="nav-link {{ request()->routeIs('dashboard.*') ? 'is-active' : '' }}" href="{{ route('dashboard.index') }}"><svg class="icon"><use href="#i-dashboard"/></svg> Vista general</a>
            <a class="nav-link {{ request()->routeIs('demo.*') ? 'is-active' : '' }}" href="{{ route('demo.index') }}"><svg class="icon"><use href="#i-lab"/></svg> Patrones creacionales <span class="nav-pill">2</span></a>
            <a class="nav-link {{ request()->routeIs('estructurales.*') ? 'is-active' : '' }}" href="{{ route('estructurales.index') }}"><svg class="icon"><use href="#i-layers"/></svg> Patrones estructurales <span class="nav-pill">3</span></a>
        </nav>
        <div class="nav-label nav-label-space">GESTIÓN HOSPITALARIA</div>
        <nav class="sidebar-nav">
            <a class="nav-link {{ request()->routeIs('web.pacientes.*') ? 'is-active' : '' }}" href="{{ route('web.pacientes.index') }}"><svg class="icon"><use href="#i-users"/></svg> Pacientes</a>
            <a class="nav-link {{ request()->routeIs('web.citas.*') ? 'is-active' : '' }}" href="{{ route('web.citas.index') }}"><svg class="icon"><use href="#i-calendar"/></svg> Citas médicas</a>
        </nav>
        <div class="sidebar-bottom">
            <div class="environment-card"><span class="environment-icon"><svg class="icon"><use href="#i-spark"/></svg></span><strong>Entorno académico</strong><small>Los datos del laboratorio son simulados y no representan diagnósticos reales.</small><span class="environment-state"><span class="status-dot"></span> Demo disponible</span></div>
            <div class="sidebar-footer">SGHI · Laravel 12<br><span>Arquitectura y patrones de diseño</span></div>
        </div>
    </aside>
    <div class="main-area">
        <header class="topbar">
            <div class="topbar-left"><button type="button" class="mobile-menu icon-button" id="menu-toggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="sidebar"><svg class="icon"><use href="#i-menu"/></svg></button><span class="topbar-breadcrumb">SGHI</span><svg class="icon breadcrumb-chevron"><use href="#i-chevron"/></svg><strong>@yield('breadcrumb', 'Vista general')</strong></div>
            <div class="topbar-right"><span class="today-date">{{ now()->format('d/m/Y') }}</span><span class="topbar-separator"></span><span class="profile-circle">SG</span><span class="profile-text">Demo académico<small>Entorno local</small></span></div>
        </header>
        <main class="main-content" id="main-content">
            @if (session('mensaje')) <div class="alert alert-success" role="status"><svg class="icon"><use href="#i-check"/></svg><span>{{ session('mensaje') }}</span></div> @endif
            @if ($errors->any())
                <div class="alert alert-error" role="alert"><strong>Revisa los campos del formulario:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            @yield('content')
        </main>
        <footer class="page-footer"><span>© {{ now()->year }} SGHI · Proyecto de Ingeniería de Software</span><span>Versión académica</span></footer>
    </div>
</div>
<script>
(function(){
  const toggle=document.getElementById('menu-toggle'); const sidebar=document.getElementById('sidebar'); const shade=document.getElementById('mobile-shade');
  if(!toggle||!sidebar||!shade)return;
  function close(){ sidebar.classList.remove('sidebar-open');shade.hidden=true;toggle.setAttribute('aria-expanded','false');document.body.classList.remove('menu-is-open'); }
  toggle.addEventListener('click',()=>{let open=sidebar.classList.toggle('sidebar-open');shade.hidden=!open;toggle.setAttribute('aria-expanded',String(open));document.body.classList.toggle('menu-is-open',open);});
  shade.addEventListener('click',close);document.addEventListener('keydown',e=>{if(e.key==='Escape')close()});
})();
</script>
@stack('scripts')
</body>
</html>
