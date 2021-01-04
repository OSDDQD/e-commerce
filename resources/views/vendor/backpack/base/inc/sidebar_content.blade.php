<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i>
        <span>Пользователи</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i>
        <span>Роли</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i>
        <span>Разрешения</span>
    </a>
</li>
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#">
        <i class="nav-icon la la-shopping-basket"></i>
        Магазин
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item">
            <a class="nav-link" href="{{ backpack_url('category') }}"><i class="nav-icon la la-sitemap"></i>
                <span>Категории</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ backpack_url('item') }}"><i class="nav-icon la la-shopping-basket"></i>
                <span>Товары</span></a>
        </li>
    </ul>
</li>