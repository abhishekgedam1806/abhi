{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- /jobs main page --}}
    <url>
        <loc>{{ url('/jobs') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- City landing pages: /jobs-in-{city} --}}
    @foreach($cities as $city)
    <url>
        <loc>{{ url('/jobs-in-' . \Illuminate\Support\Str::slug($city->city)) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- Category landing pages: /jobs/category/{slug} --}}
    @foreach($functionalAreas as $fa)
    @if(!empty($fa->functional_area))
    <url>
        <loc>{{ url('/jobs/category/' . \Illuminate\Support\Str::slug($fa->functional_area)) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endif
    @endforeach

    {{-- Individual job pages --}}
    @foreach($jobs as $job)
    <url>
        <loc>{{ url('/job/' . $job->slug) }}</loc>
        <lastmod>{{ $job->updated_at ? $job->updated_at->format('Y-m-d') : date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
</urlset>
