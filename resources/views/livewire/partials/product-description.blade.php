@if($product->parsed_long_description)
    <div class="border-t border-slate-100 pt-0">
       
        <div class="mt-4 text-slate-600 leading-relaxed space-y-4">
            {!! $product->parsed_long_description !!}
        </div>
    </div>
@endif
