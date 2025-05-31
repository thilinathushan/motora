<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ml-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu">
                    <i class="fi fi-rr-menu-burger"></i></a>
            </div>
            <div class="nk-header-brand d-xl-none">
                <a href="{{ route('dashboard') }}" class="logo-link">
                    <img class="logo-light logo-img" src="{{ asset('motora-logo-2.png') }}" srcset=""
                        alt="logo" />
                    <img class="logo-dark logo-img" src="{{ asset('motora-logo-2.png') }}" srcset=""
                        alt="logo-dark" />
                </a>
            </div>
            <!-- .nk-header-brand -->
            {{-- <div class="nk-header-news d-none d-xl-block">
                <div class="nk-news-list">
                    <a class="nk-news-item" href="#">
                        <div class="nk-news-icon">
                            <em class="icon ni ni-card-view"></em>
                        </div>
                        <div class="nk-news-text">
                            <p>
                                Do you know the latest update of 2019?
                                <span>
                                    A overview of our is now available on YouTube</span>
                            </p>
                            <em class="icon ni ni-external"></em>
                        </div>
                    </a>
                </div>
            </div> --}}
            <!-- .nk-header-news -->
            @php
                $selectedModelVersion = session('user_selected_model', Config::get('prediction_model.default'));
                $modelDetails = Config::get('prediction_model.models');
            @endphp
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    {{-- model selection --}}
                    <div class="col m-0 p-0 d-flex flex-column justify-content-start align-items-start">
                        <p class="p-0 m-0 text-muted" style="font-size: 12px">Motora Prediction</p>
                        <select id="modelSelector" name="model_version" class="form-select border-0 badge rounded-pill text-secondary text-start"
                            style="background-color: rgb(195, 195, 195)" aria-label="Default select example">
                            @foreach ($modelDetails as $version => $model)
                                <option @if ($version == $selectedModelVersion) selected @endif
                                    value="{{ $version }}"
                                    data-fulltext="{{ $model['modelName'] }}"
                                    data-shorttext="{{ \Illuminate\Support\Str::limit($model['modelName'],2) }}">
                                    {{ $model['modelName'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="me-3" style="width: 40px; height: 40px;">
                                    <img src="{{ asset('icons/man.png') }}" alt="user avatar" class="rounded-circle">
                                </div>
                                <div class="user-info d-none d-md-block">
                                    <div class="user-status">
                                        @if (Auth::guard('organization_user')->check() && isset(Auth::guard('organization_user')->user()->userType))
                                            {{ Auth::guard('organization_user')->user()->userType->name }}
                                        @else
                                            User
                                        @endif
                                    </div>
                                    <div class="user-name">
                                        @if (Auth::guard('organization_user')->check())
                                            {{ Auth::guard('organization_user')->user()->name }}
                                        @elseif (Auth::guard('web')->check())
                                            {{ Auth::guard('web')->user()->name }}
                                        @else
                                            Guest
                                        @endif
                                        <i class="fi fi-rr-caret-down"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="me-3" style="width: 50px; height: 50px;">
                                        <img src="{{ asset('icons/man.png') }}" alt="user avatar" class="rounded-circle">
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">
                                            @if (Auth::guard('organization_user')->check())
                                                {{ Auth::guard('organization_user')->user()->name }}
                                            @elseif (Auth::guard('web')->check())
                                                {{ Auth::guard('web')->user()->name }}
                                            @else
                                                Guest
                                            @endif
                                        </span>
                                        <span class="sub-text">
                                            @if (Auth::guard('organization_user')->check())
                                                {{ Auth::guard('organization_user')->user()->email }}
                                            @elseif (Auth::guard('web')->check())
                                                {{ Auth::guard('web')->user()->email }}
                                            @else
                                                Email
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li>
                                        <a href="{{ route('dashboard.viewProfile') }}">
                                            <i class="fs-5 fi fi-rr-circle-user me-2"></i>
                                            <span>View Profile</span>
                                        </a>
                                    </li>
                                    {{-- <li>
                                        <a href="html/user-profile-setting.html"><em
                                                class="icon ni ni-setting-alt"></em><span>Account
                                                Setting</span></a>
                                    </li>
                                    <li>
                                        <a href="html/user-profile-activity.html"><em
                                                class="icon ni ni-activity-alt"></em><span>Login
                                                Activity</span></a>
                                    </li>
                                    <li>
                                        <a class="dark-switch" href="#"><em
                                                class="icon ni ni-moon"></em><span>Dark Mode</span></a>
                                    </li> --}}
                                </ul>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li>
                                        <form action="{{ Route('organization.logout') }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary">SignOut</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    {{-- nk-quick-nav-icon  --}}
                    <div class="dropdown">
                        <i class="fs-4 dropdown-toggle fi fi-rr-bell position-relative cursor-pointer" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <livewire:notification-bell />
                        </i>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start"
                            style="width: 350px; max-width: 90vw; max-height: 400px; overflow-y: auto;">
                            <livewire:notification-panel />
                        </ul>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</div>
