<div class="checkout-form-card">
    <h4 class="checkout-title">
        <i class="fas fa-user-circle"></i> My Account
    </h4>

    <div class="d-flex flex-column gap-2">
        <a href="{{ route('dashboard') }}" class="menu-item">
            <i class="fas fa-user-alt me-2"></i> My Profile
        </a>
        <a href="{{ route('front.orders') }}" class="menu-item">
            <i class="fas fa-shopping-bag me-2"></i> My Orders
        </a>
        <a href="{{ route('front.wishlist') }}" class="menu-item">
            <i class="fas fa-heart me-2"></i> Wishlist
        </a>
        <a href="{{ route('front.change-password') }}" class="menu-item">
            <i class="fas fa-lock me-2"></i> Change Password
        </a>

        <form action="{{ route('user.logout') }}" method="POST" class="mt-2 pt-2"
            style="border-top: 1px solid var(--color-gray-200);">
            @csrf
            <button type="submit" name="submit" class="btn-secondary-premium menu-item w-100 text-start text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
        </form>
    </div>
</div>
