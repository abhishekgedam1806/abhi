{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Homepage --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Main Public Directories --}}
    <url>
        <loc>{{ route('job.list') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>hourly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/companies') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('business.list') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('blogs') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
    </url>

    {{-- Informational & Legal Pages --}}
    <url>
        <loc>{{ route('contact.us') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ route('faq') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    {{-- Dynamic CMS Pages (About Us, Terms, Privacy Policy etc.) --}}
    @foreach($cmsPages as $page)
    <url>
        <loc>{{ route('cms', $page->page_slug) }}</loc>
        <lastmod>{{ $page->updated_at ? $page->updated_at->format('Y-m-d') : date('Y-m-d') }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

    {{-- Blog Articles --}}
    @foreach($blogs as $blog)
    @if(!empty($blog->slug))
    <url>
        <loc>{{ url('/blog/' . $blog->slug) }}</loc>
        <lastmod>{{ $blog->updated_at ? date('Y-m-d', strtotime($blog->updated_at)) : date('Y-m-d') }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endif
    @endforeach
</urlset>
