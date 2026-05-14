<div class="product-card">
    <div class="product-image">
        <a href="{{ route('front.product', $product->slug) }}">
            @if (!empty($product->product_images->first()->image))
                <img src="{{ asset('uploads/product/small/' . $product->product_images->first()->image) }}" alt="{{ $product->title }}">
            @else
                <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="{{ $product->title }}">
            @endif
        </a>

        @if($product->compare_price > 0)
            <span class="product-badge product-badge-sale">Sale</span>
        @elseif($product->qty <= 0)
            <span class="product-badge product-badge-soldout">Sold Out</span>
        @else
            <span class="product-badge product-badge-new">New</span>
        @endif

        <button class="wishlist-btn" onclick="addToWishList({{ $product->id }}); event.preventDefault();">
            <i class="far fa-heart"></i>
        </button>

        <div class="product-actions-overlay">
            <button class="action-btn" onclick="addToWishList({{ $product->id }})" title="Add to Wishlist">
                <i class="far fa-heart"></i>
            </button>
            <button class="action-btn action-btn-add" onclick="quickAddToCart({{ $product->id }})" title="Quick Add">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>

    <div class="product-info">
        <span class="product-category">{{ $product->category->name ?? '' }}</span>
        <h3 class="product-title">
            <a href="{{ route('front.product', $product->slug) }}">{{ $product->title }}</a>
        </h3>
        <div class="product-price">
            <span class="price-current">৳{{ number_format($product->price) }}</span>
            @if ($product->compare_price > 0)
                <span class="price-original">৳{{ number_format($product->compare_price) }}</span>
                <span class="price-discount">{{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}% OFF</span>
            @endif
        </div>
        <div class="product-rating">
            <div class="rating-stars">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star {{ $i <= 4 ? '' : 'empty' }}"></i>
                @endfor
            </div>
            <span class="rating-count">({{ $product->product_ratings_count ?? 0 }})</span>
        </div>
    </div>
</div>