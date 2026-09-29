<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    
    {{-- صفحه اصلی --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    
    {{-- محصولات --}}
    <url>
        <loc>{{ url('/products') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    
    {{-- درباره ما --}}
    <url>
        <loc>{{ url('/about') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    
    {{-- تماس --}}
    <url>
        <loc>{{ url('/contact') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    
    {{-- دسته‌بندی‌ها --}}
    @foreach($categories as $cat)
    <url>
        <loc>{{ url('/category/' . $cat->slug) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        <lastmod>{{ $cat->updated_at->toAtomString() }}</lastmod>
    </url>
    @endforeach
    
    {{-- محصولات --}}
    @foreach($products as $product)
    <url>
        <loc>{{ url('/products/' . $product->slug) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
        <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
    </url>
    @endforeach
    
</urlset>