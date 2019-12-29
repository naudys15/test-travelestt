    <!--Menu-->
    <!--================================-->
    <div id="mainnav-menu-wrap">
        <div class="nano">
            <div class="nano-content">
                @php
                    $permissions = unserialize(session('permissions'));
                    $coasts = permissionValid($permissions, 3);
                    $festivals = permissionValid($permissions, 7);
                    $museums = permissionValid($permissions, 11);
                    $night_spots = permissionValid($permissions, 15);
                    $points_of_interest = permissionValid($permissions, 19);
                    $street_markets = permissionValid($permissions, 23);
                    $shows = permissionValid($permissions, 75);
                    $natural_spaces = permissionValid($permissions, 79);
                    $experiences = permissionValid($permissions, 83);
                    $routes = permissionValid($permissions, 27);
                    $users = permissionValid($permissions, 31);
                    $roles = permissionValid($permissions, 35);
                    $countries = permissionValid($permissions, 63);
                @endphp
                <!--Profile Widget-->
                <!--================================-->
                <div id="mainnav-profile" class="mainnav-profile">
                    <div class="profile-wrap text-center">
                        <div class="pad-btm">
                            <img class="img-circle img-md" src="{{ asset('assets/images/profile-photos/male.png') }}" alt="Profile Picture">
                        </div>
                        <a href="#profile-nav" class="box-block" data-toggle="collapse" aria-expanded="false">
                            <span class="pull-right dropdown-toggle">
                                <i class="dropdown-caret"></i>
                            </span>
                            <p class="mnp-name">Aaron Chavez</p>
                            <span class="mnp-desc">aaron.cha@themeon.net</span>
                        </a>
                    </div>
                    <div id="profile-nav" class="collapse list-group bg-trans">
                        <a href="#" class="list-group-item">
                            <i class="demo-pli-male icon-lg icon-fw"></i> View Profile
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="demo-pli-gear icon-lg icon-fw"></i> Settings
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="demo-pli-information icon-lg icon-fw"></i> Help
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="demo-pli-unlock icon-lg icon-fw"></i> Logout
                        </a>
                    </div>
                </div>
        
        
                <!--Shortcut buttons-->
                <!--================================-->
                <div id="mainnav-shortcut" class="hidden">
                    <ul class="list-unstyled shortcut-wrap">
                        <li class="col-xs-3" data-content="My Profile">
                            <a class="shortcut-grid" href="#">
                                <div class="icon-wrap icon-wrap-sm icon-circle bg-mint">
                                <i class="demo-pli-male"></i>
                                </div>
                            </a>
                        </li>
                        <li class="col-xs-3" data-content="Messages">
                            <a class="shortcut-grid" href="#">
                                <div class="icon-wrap icon-wrap-sm icon-circle bg-warning">
                                <i class="demo-pli-speech-bubble-3"></i>
                                </div>
                            </a>
                        </li>
                        <li class="col-xs-3" data-content="Activity">
                            <a class="shortcut-grid" href="#">
                                <div class="icon-wrap icon-wrap-sm icon-circle bg-success">
                                <i class="demo-pli-thunder"></i>
                                </div>
                            </a>
                        </li>
                        <li class="col-xs-3" data-content="Lock Screen">
                            <a class="shortcut-grid" href="#">
                                <div class="icon-wrap icon-wrap-sm icon-circle bg-purple">
                                <i class="demo-pli-lock-2"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <!--================================-->
                <!--End shortcut buttons-->
        
        
                <ul id="mainnav-menu" class="list-group">
            
                    <!--Category name-->
                    {{-- <li class="list-header">Navigation</li> --}}
            
                    <!--Menu list item-->
                    <li>
                        <a href="{{ url(app()->getLocale().'/'.__('routes.panel')) }}">
                            <i class="demo-pli-home"></i>
                            <span class="menu-title">{{ __('language.panel') }}</span>
                            <!-- <i class="arrow"></i> -->
                        </a>
                    </li>
        
                    <!--Menu list item-->
                    <li>
                        <a href="#">
                            <i class="ti-flag-alt-2"></i>
                            <span class="menu-title">{{ __('language.destinations') }}</span>
                            <!-- <i class="arrow"></i> -->
                        </a>
                
                        <!--Submenu-->
                        <ul class="collapse">
                            @if($coasts)
                                <li>
                                    <a href="{{ url(app()->getLocale().'/'.__('routes.panel').'/'.__('routes.coasts')) }}">
                                        <span class="menu-title">{{ __('language.coasts') }}</span>
                                        <!-- <i class="arrow"></i> -->
                                    </a>
                                </li>
                            @endif()
                            @if($festivals)
                                <li>
                                    <a href="{{ url(app()->getLocale().'/'.__('routes.panel').'/'.__('routes.festivals')) }}">
                                        <span class="menu-title">{{ __('language.festivals') }}</span>
                                        <!-- <i class="arrow"></i> -->
                                    </a>
                                </li>
                            @endif
                            @if($shows)
                                <li>
                                    <a href="{{ url(app()->getLocale().'/'.__('routes.panel').'/'.__('routes.shows')) }}">
                                        <span class="menu-title">{{ __('language.shows') }}</span>
                                        <!-- <i class="arrow"></i> -->
                                    </a>
                                </li>
                            @endif
                            @if($museums)
                                <li>
                                    <a href="{{ url(app()->getLocale().'/'.__('routes.panel').'/'.__('routes.museums')) }}">
                                        <span class="menu-title">{{ __('language.museums') }}</span>
                                        <!-- <i class="arrow"></i> -->
                                    </a>
                                </li>
                            @endif
                            @if($natural_spaces)
                                <li>
                                    <a href="{{ url(app()->getLocale().'/'.__('routes.panel').'/'.__('routes.natural_spaces')) }}">
                                        <span class="menu-title">{{ __('language.natural_spaces') }}</span>
                                        <!-- <i class="arrow"></i> -->
                                    </a>
                                </li>
                            @endif
                            @if($points_of_interest)
                                <li>
                                    <a href="{{ url(app()->getLocale().'/'.__('routes.panel').'/'.__('routes.points_of_interest')) }}">
                                        <span class="menu-title">{{ __('language.points_of_interest') }}</span>
                                        <!-- <i class="arrow"></i> -->
                                    </a>
                                </li>
                            @endif
                            @if($night_spots)
                                <li>
                                    <a href="{{ url(app()->getLocale().'/'.__('routes.panel').'/'.__('routes.night_spots')) }}">
                                        <span class="menu-title">{{ __('language.night_spots') }}</span>
                                        <!-- <i class="arrow"></i> -->
                                    </a>
                                </li>
                            @endif
                            @if($routes)
                                <li>
                                    <a href="{{ url(app()->getLocale().'/'.__('routes.panel').'/'.__('routes.routes')) }}">
                                        <span class="menu-title">{{ __('language.routes') }}</span>
                                        <!-- <i class="arrow"></i> -->
                                    </a>
                                </li>
                            @endif
                            @if($street_markets)
                                <li>
                                    <a href="{{ url(app()->getLocale().'/'.__('routes.panel').'/'.__('routes.street_markets')) }}">
                                        <span class="menu-title">{{ __('language.street_markets') }}</span>
                                        <!-- <i class="arrow"></i> -->
                                    </a>
                                </li>
                            @endif   
                            @if($experiences)
                                <li>
                                    <a href="{{ url(app()->getLocale().'/'.__('routes.panel').'/'.__('routes.multi_adventure_experiences')) }}">
                                        <span class="menu-title">{{ __('language.multi_adventure_experiences') }}</span>
                                        <!-- <i class="arrow"></i> -->
                                    </a>
                                </li>
                            @endif                            
                        </ul>
                    </li>

                    <!--Menu list item-->
                    <li>
                        <a href="#">
                            <i class="ti-tag"></i>
                            <span class="menu-title">{{ __('language.location') }}</span>
                            <!-- <i class="arrow"></i> -->
                        </a>
                
                        <!--Submenu-->
                        <ul class="collapse">
                            @if($countries)
                                <li>
                                    <a href="{{ url(app()->getLocale().'/'.__('routes.panel').'/'.__('routes.countries')) }}">
                                        <span class="menu-title">{{ __('language.countries') }}</span>
                                        <!-- <i class="arrow"></i> -->
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
            
                    <!--Menu list item-->
                    <!-- <li>
                        <a href="widgets.html">
                            <i class="demo-pli-gear"></i>
                            <span class="menu-title">Widgets<span class="pull-right badge badge-warning">24</span></span>
                        </a>
                    </li> -->
        
                    <li class="list-divider"></li>
            
                    <!--Category name-->
                    <!-- <li class="list-header">Components</li> -->
            
                    <!--Menu list item-->
                    

                    <li>
                        <a href="#">
                            <i class="ti-settings"></i>
                            <span class="menu-title">{{ __('language.setting') }}</span>
                            <!-- <i class="arrow"></i> -->
                        </a>
                
                        <!--Submenu-->
                        <ul class="collapse">
                            @if($users)
                                <li>
                                    <a href="{{ url(app()->getLocale().'/'.__('routes.panel').'/'.__('routes.users')) }}">
                                        <i class="ti-user"></i>
                                        <span class="menu-title">{{ __('language.users') }}</span>
                                        <!-- <i class="arrow"></i> -->
                                    </a>
                                </li>
                            @endif
                            @if($roles)
                                <li>
                                    <a href="{{ url(app()->getLocale().'/'.__('routes.panel').'/'.__('routes.roles')) }}">
                                        <i class="ti-control-shuffle"></i>
                                        <span class="menu-title">{{ __('language.roles') }}</span>
                                        <!-- <i class="arrow"></i> -->
                                    </a>
                                </li>
                            @endif     
                        </ul>
                    </li>
        
                    <!--Menu list item-->
                    <!-- <li>
                        <a href="#">
                            <i class="demo-pli-boot-2"></i>
                            <span class="menu-title">adasd</span>
                            <i class="arrow"></i>
                        </a> -->
                
                        <!--Submenu-->
                        <!-- <ul class="collapse">
                        <li><a href="ui-buttons.html">Buttons</a></li>
                        <li><a href="ui-panels.html">Panels</a></li>
                        </ul>
                    </li> -->
                </ul>
            </div>
        </div>
    </div>
    <!--================================-->
    <!--End menu-->
        