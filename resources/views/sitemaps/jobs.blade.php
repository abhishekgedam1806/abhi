{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/jobs') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    @foreach($cities as $city)
    <url>
        <loc>{{ url('/jobs-in-' . \Illuminate\Support\Str::slug($city->city)) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach($jobs as $job)
    <url>
        <loc>{{ url('/job/' . $job->slug) }}</loc>
        <lastmod>{{ $job->updated_at ? $job->updated_at->format('Y-m-d') : date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
</urlset>
