<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
   <meta charset="utf-8">
   <meta
      name="viewport"
      content="width=device-width, initial-scale=1"
   >
   <meta
      name="csrf-token"
      content="{{ csrf_token() }}"
   >
    <meta property="og:title" content="CashoraN – Build Real Digital Income">
    <meta property="og:description" content="Courses + ready-to-sell assets to start earning from day one.">
    <meta property="og:url" content="https://cashoran.com/">
   {{-- Inline style to set the HTML background color based on our theme in app.css --}}
   <style>
      html {
         background-color: oklch(1 0 0);
      }

      html.dark {
         background-color: oklch(0.145 0 0);
      }
   </style>

   @if (app('system_settings'))
      <title inertia>
         {{ $metaTitle ?? app('system_settings')->fields['name'] }}
      </title>
      <meta
         name="description"
         content="{{ $metaDescription ?? app('system_settings')->fields['description'] }}"
      >
      <meta
         name="keywords"
         content="{{ $metaKeywords ?? app('system_settings')->fields['keywords'] }}"
      >
      <meta
         name="author"
         content="{{ app('system_settings')->fields['author'] }}"
      >

      @if (!empty(app('system_settings')->fields['favicon']))
         <link
            rel="icon"
            href="{{ app('system_settings')->fields['favicon'] }}"
            type="image/png"
         >
      @elseif (!empty(app('system_settings')->fields['logo_light']))
         <link
            rel="icon"
            href="{{ app('system_settings')->fields['logo_light'] }}"
            type="image/png"
         >
      @endif

      <meta
         property="og:type"
         content="{{ $ogType ?? 'website' }}"
      >
      <meta
         property="og:url"
         content="{{ $ogUrl ?? env('APP_URL', config('app.url')) }}"
      >
      <meta
         property="og:title"
         content="{{ $ogTitle ?? (app('system_settings')->fields['title'] ?? app('system_settings')->fields['name']) }}"
      >
      <meta
         property="og:description"
         content="{{ $ogDescription ?? app('system_settings')->fields['description'] }}"
      >
      <meta
         property="og:site_name"
         content="{{ app('system_settings')->fields['name'] }}"
      >

      @if (!empty($ogImage))
         <meta
            property="og:image"
            content="{{ $ogImage }}"
         >
         <meta
            property="og:image:width"
            content="1200"
         >
         <meta
            property="og:image:height"
            content="630"
         >
         <meta
            property="og:image:alt"
            content="{{ $ogTitle ?? app('system_settings')->fields['name'] }}"
         >
      @elseif (!empty(app('system_settings')->fields['banner']))
         <meta
            property="og:image"
            content="{{ app('system_settings')->fields['banner'] }}"
         >
         <meta
            property="og:image:width"
            content="1000"
         >
         <meta
            property="og:image:height"
            content="600"
         >
         <meta
            property="og:image:alt"
            content="{{ app('system_settings')->fields['name'] }}"
         >
      @endif

      <meta
         name="twitter:card"
         content="{{ $twitterCard ?? 'summary_large_image' }}"
      >
      <meta
         name="twitter:title"
         content="{{ $twitterTitle ?? (app('system_settings')->fields['title'] ?? app('system_settings')->fields['name']) }}"
      >
      <meta
         name="twitter:description"
         content="{{ $twitterDescription ?? app('system_settings')->fields['description'] }}"
      >
      @if (!empty($twitterImage))
         <meta
            name="twitter:image"
            content="{{ $twitterImage }}"
         >
      @elseif (!empty(app('system_settings')->fields['banner']))
         <meta
            name="twitter:image"
            content="{{ app('system_settings')->fields['banner'] }}"
         >
      @endif
   @endif

   <link
      rel="preconnect"
      href="https://fonts.bunny.net"
   >
   <link
      href="https://fonts.bunny.net/css?family=inter:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i"
      rel="stylesheet"
   />
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-RD9DD7ZVS1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-RD9DD7ZVS1');
    </script>
   @routes
   @viteReactRefresh
   @vite(['resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
   @inertiaHead
</head>

<body class="font-sans antialiased">
   @inertia

   {{-- Inject Global Style AFTER app styles so it wins in the cascade --}}
   @php($globalStyle = app('system_settings')->fields['global_style'] ?? '')
   @if ($globalStyle)
      <style
         data-global-style
         type="text/css"
      >
         {!! $globalStyle !!}
      </style>
   @endif
   <script>
       (function () {
           // Detect absolute URL host safely
           const getHost = (url) => {
               try { return new URL(url, location.href).host; } catch { return ""; }
           };
           const isExternal = (href) => {
               const host = getHost(href);
               return !!host && host !== location.host;
           };

           // 1) Normalize all external <a> to open normally
           function normalizeExternalLinks(root = document) {
               root.querySelectorAll('a[href]').forEach(a => {
                   const href = a.getAttribute('href');
                   if (!href || href.startsWith('javascript:') || href === '#') return;

                   if (isExternal(href)) {
                       // Make it explicit this is an external, non-SPA link
                       a.setAttribute('data-hardlink', 'true');
                       if (!a.getAttribute('target')) a.setAttribute('target', '_blank');
                       const rel = (a.getAttribute('rel') || '').toLowerCase();
                       if (!rel.includes('noopener')) a.setAttribute('rel', (rel + ' noopener noreferrer').trim());
                   }
               });
           }

           // 2) Hard-link handler: beat frameworks by using capture and stopImmediatePropagation
           function hardLinkHandler(e) {
               const a = e.target && (e.target.closest ? e.target.closest('a[href]') : null);
               if (!a) return;
               const href = a.getAttribute('href');
               if (!href || href.startsWith('javascript:') || href === '#') return;

               if (isExternal(href) || a.hasAttribute('data-hardlink')) {
                   // Prevent SPA/router/analytics from hijacking
                   e.preventDefault();
                   e.stopPropagation();
                   if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();

                   // Open as proper navigation
                   const url = new URL(href, location.href).href;
                   const tgt = (a.getAttribute('target') || '').toLowerCase();

                   if (tgt === '_blank') {
                       // Popup blockers won’t block because this is a direct user click
                       window.open(url, '_blank', 'noopener,noreferrer');
                   } else {
                       location.href = url;
                   }
               }
           }

           // Run now and watch for dynamic content
           normalizeExternalLinks(document);
           document.addEventListener('click', hardLinkHandler, true);

           // Handle links injected later
           new MutationObserver(muts => {
               for (const m of muts) {
                   m.addedNodes && m.addedNodes.forEach(node => {
                       if (node.nodeType === 1) normalizeExternalLinks(node);
                   });
               }
           }).observe(document.documentElement, { childList: true, subtree: true });

           // Optional: stop any fetch/XHR to social share endpoints that caused the CORS spam
           const blocked = [
               /https?:\/\/(www\.)?facebook\.com\/share\//i,
               /https?:\/\/(www\.)?facebook\.com\/sharer\/sharer\.php/i
           ];
           const shouldBlock = (u) => blocked.some(r => r.test(String(u||"")));

           if (window.fetch) {
               const _fetch = window.fetch;
               window.fetch = function(resource, init) {
                   const url = typeof resource === 'string' ? resource : (resource && resource.url);
                   if (shouldBlock(url)) return Promise.reject(new Error('Blocked share endpoint (fetch)'));
                   return _fetch.apply(this, arguments);
               };
           }
           if (window.XMLHttpRequest) {
               const _open = XMLHttpRequest.prototype.open;
               const _send = XMLHttpRequest.prototype.send;
               XMLHttpRequest.prototype.open = function(method, url) {
                   this.__blockShare = shouldBlock(url);
                   return _open.apply(this, arguments);
               };
               XMLHttpRequest.prototype.send = function() {
                   if (this.__blockShare) { try { this.abort(); } catch(_) {} throw new Error('Blocked share endpoint (XHR)'); }
                   return _send.apply(this, arguments);
               };
           }
       })();
   </script>

   <script src="https://cdn.brevo.com/js/sdk-loader.js" async></script>
   <script>
       // Version: 2.0
       window.Brevo = window.Brevo || [];
       Brevo.push([
           "init",
           {
               client_key: "dmd71b2fd25brjv24fsdknz8"
           }
       ]);
   </script>

</body>

</html>
