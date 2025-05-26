<h1>Webshop van {{ $company->name }}</h1>

<ul>
    @foreach ($products as $product)
        <li>{{ $product->name }}</li>
    @endforeach
</ul>
