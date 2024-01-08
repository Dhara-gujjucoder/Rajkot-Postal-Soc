<nav class="main-navbar">
    <div class="">
        <ul class="justify-content-between">
            <li class="menu-item  has-sub ">
                <a href="#" class="menu-link">
                    <span><i class="bi bi-calendar-date"></i> {{ __('Financial Year') }} :
                        {{ $current_year->title ?? ''}}</span>
                </a>
                <div class="submenu">
                    
                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                    <div class="submenu-group-wrapper">
                        <ul class="submenu-group">
                            @foreach ($financial_year as $year)
                                <li class="submenu-item {{ $current_year->id == $year->id ? 'active' : '' }}">
                                    <a href="{{ route('financial_year.change', $year->id) }}"
                                        class="submenu-link">{{ $year->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li class="menu-item  has-sub ">
                <a href="#" class="menu-link">
                    <span><i class="bi bi-person"></i> {{ Auth::user()->name }} </span>
                </a>
                <div class="submenu">
                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                    <div class="submenu-group-wrapper">
                        <ul class="submenu-group">
                                <li class="submenu-item {{ request()->is('home*') ? 'active' : '' }}">
                                    <a href="{{ route('users.profile', auth()->user()->id) }}"
                                        class="submenu-link">{{ 'Edit Profile' }}</a>
                                </li>
                        </ul>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</nav>
