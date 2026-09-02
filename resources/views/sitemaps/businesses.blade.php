{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/businesses') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    @foreach($categories as $category)
    <url>
        <loc>{{ url('/businesses/category/' . $category->slug) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach($cities as $city)
    <url>
        <loc>{{ url('/businesses/location/' . \Illuminate\Support\Str::slug($city->city)) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach($businesses as $business)
    <url>
        <loc>{{ url('/business/' . $business->slug) }}</loc>
        <lastmod>{{ $business->updated_at ? $business->updated_at->format('Y-m-d') : date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
</urlset>
