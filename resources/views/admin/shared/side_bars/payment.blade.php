<li class="nav-item {{ (Request::is('admin/payment*') || Request::is('admin/offers*') || Request::is('admin/coupon-redemptions*')) ? 'active open' : '' }}">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-credit-card"></i>
        <span class="title">Payment Management</span>
        <span class="arrow {{ (Request::is('admin/payment*') || Request::is('admin/offers*') || Request::is('admin/coupon-redemptions*')) ? 'open' : '' }}"></span>
    </a>
    <ul class="sub-menu" style="{{ (Request::is('admin/payment*') || Request::is('admin/offers*') || Request::is('admin/coupon-redemptions*')) ? 'display: block;' : '' }}">
        <li class="nav-item {{ Request::is('admin/payment*') ? 'active' : '' }}">
            <a href="{{ route('admin.payment.index') }}" class="nav-link">
                <span class="title"><i class="fa fa-list"></i> Transactions</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/offers*') ? 'active' : '' }}">
            <a href="{{ route('admin.offers.index') }}" class="nav-link">
                <span class="title"><i class="fa fa-tag"></i> Offers & Coupons</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/coupon-redemptions*') ? 'active' : '' }}">
            <a href="{{ route('admin.coupon-redemptions.index') }}" class="nav-link">
                <span class="title"><i class="fa fa-history"></i> Coupon Redemptions</span>
            </a>
        </li>
    </ul>
</li>
