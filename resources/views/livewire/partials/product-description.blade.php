@if($product->parsed_long_description)
    <div class="mt-8 border-t border-slate-100 pt-8">
        <h2 class="text-xl font-bold text-slate-900">Product Details</h2>
        <div class="mt-4 text-slate-600 leading-relaxed space-y-4">
            {!! $product->parsed_long_description !!}
        </div>
    </div>
@endif
