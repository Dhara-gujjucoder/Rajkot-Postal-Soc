<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ route('home') }}"><img src="{{ asset($setting->logo) }}" alt="Logo"
                            style=" height: 5rem" srcset=""></a>
                </div>
                <br>
                <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20"
                        height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                        <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                opacity=".3"></path>
                            <g transform="translate(-210 -1)">
                                <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                <circle cx="220.5" cy="11.5" r="4"></circle>
                                <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                </path>
                            </g>
                        </g>
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                        <label class="form-check-label"></label>
                    </div>
                    {{-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                        </path>
                    </svg> --}}
                    <a href="{{ route('logout') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout"
                        onclick="event.preventDefault(); 
                                  document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">{{ __('Language') }}</li>
                <li class="sidebar-item">
                    <div class="lang-select">
                        <div class="d-flex">
                            <a href="{{ route('change.locale', ['locale' => 'en']) }}"
                                class="btn btn-sm btn-outline-primary m-2 {{ empty(session('locale')) || session('locale') == 'en' ? 'active' : '' }}">{{ __('EN') }}</a>
                            <a href="{{ route('change.locale', ['locale' => 'gu']) }}"
                                class=" btn btn-sm btn-outline-primary m-2 {{ session('locale') == 'gu' ? 'active' : '' }}">{{ __('GU') }}</a>
                        </div>
                    </div>
                </li>
                <li class="sidebar-title">{{ __('Menu') }}</li>
                <li class="sidebar-item {{ request()->is('admin/home*') ? 'active' : '' }} ">
                    <a href="{{ route('home') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @canany(['create-role', 'edit-role', 'delete-role', 'view-role'])
                    <li class="sidebar-item {{ request()->is('admin/roles*') ? 'active' : '' }}">
                        <a href="{{ route('roles.index') }}" class='sidebar-link'>
                            <i class="bi bi-backpack4-fill"></i>
                            <span>{{ __('Roles') }}</span>
                        </a>
                    </li>
                @endcanany
                @canany(['create-member', 'edit-member', 'delete-member', 'view-member', 'create-user', 'edit-user',
                    'delete-user', 'view-user'])
                    <li
                        class="sidebar-item  has-sub {{ request()->is('admin/members*') || request()->is('admin/users*') ? 'active' : '' }}">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-people-fill"></i>
                            <span>{{ __('Users') }}</span>
                        </a>
                        <ul
                            class="submenu {{ request()->is('admin/members*') || request()->is('admin/users*') ? 'active submenu-open' : '' }}">
                            @canany(['create-member', 'edit-member', 'delete-member', 'view-member'])
                                <li class="submenu-item  {{ request()->is('admin/members*') ? 'active' : '' }}">
                                    <a href="{{ route('members.index') }}" class='submenu-link'>
                                        {{-- <i class="bi bi-person-check-fill"></i> --}}
                                        <span>{{ __('Members') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @canany(['create-user', 'edit-user', 'delete-user', 'view-user'])
                                <li class="submenu-item  {{ request()->is('admin/users*') ? 'active' : '' }}">
                                    <a href="{{ route('users.index') }}" class='submenu-link'>
                                        {{-- <i class="bi bi-person-check-fill"></i> --}}
                                        <span>{{ __('Users') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @canany(['create-account_type', 'edit-account_type', 'delete-account_type', 'view-account_type'])
                    <li class="sidebar-item {{ request()->is('admin/account_type*') ? 'active' : '' }}">
                        <a href="{{ route('account_type.index') }}" class='sidebar-link'>
                            <i class="bi bi-window-split"></i>
                            <span>{{ __('Account Types') }}</span>
                        </a>
                    </li>
                @endcanany
                @canany(['create-ledger_account', 'edit-ledger_account', 'delete-ledger_account',
                    'view-ledger_account'])
                    <li class="sidebar-item {{ request()->is('admin/ledger_account*') ? 'active' : '' }}">
                        <a href="{{ route('ledger_account.index') }}" class='sidebar-link'>
                            <i class="bi bi-window-split"></i>
                            <span>{{ __('Ledger Account') }}</span>
                        </a>
                    </li>
                @endcanany

                @canany(['edit-general-setting', 'loaninterest-setting','share-amount-setting','monthly-saving-setting'])
                <li
                    class="sidebar-item has-sub {{ request()->is('admin/general/setting/*') || request()->is('admin/loaninterest*') || request()->is('admin/shareamount*') || request()->is('admin/monthlysaving*') ? 'active' : '' }}">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-gear-fill"></i>
                        <span>{{ __('Setting') }}</span>
                    </a>
                    <ul
                        class="submenu {{ request()->is('admin/general/setting/*') ? 'active submenu-open' : '' }}">
                        @canany(['edit-general-setting'])
                        <li class="submenu-item {{ request()->is('admin/general/setting*') ? 'active' : '' }}">
                            <a href="{{ route('setting.create',1) }}" class='submenu-link'>
                                    {{-- <i class="bi bi-person-check-fill"></i> --}}
                                    <span>{{ __('System Setting') }}</span>
                                </a>
                            </li>
                        @endcan
                        @canany(['loaninterest-setting'])
                        <li class="submenu-item {{ request()->is('admin/loaninterest*') ? 'active' : '' }}">
                            <a href="{{ route('loaninterest.index') }}" class='submenu-link'>
                                    {{-- <i class="bi bi-person-check-fill"></i> --}}
                                    <span>{{ __('Loan Interest') }}</span>
                                </a>
                            </li>
                        @endcan
                        @canany(['share-amount-setting'])
                        <li class="submenu-item {{ request()->is('admin/shareamount/*') ? 'active' : '' }}">
                            <a href="{{ route('shareamount.index') }}" class='submenu-link'>
                                    {{-- <i class="bi bi-person-check-fill"></i> --}}
                                    <span>{{ __('Share Amount') }}</span>
                                </a>
                            </li>
                        @endcan
                        @canany(['monthly-saving-setting'])
                        <li class="submenu-item {{ request()->is('admin/monthlysaving/*') ? 'active' : '' }}">
                            <a href="{{ route('monthlysaving.index') }}" class='submenu-link'>
                                    {{-- <i class="bi bi-person-check-fill"></i> --}}
                                    <span>{{ __('Monthly Saving') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcan
            </ul>
        </div>
    </div>
</div>
