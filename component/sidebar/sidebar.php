<nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header"><a href="dashboard/index-2.html" class="b-brand text-primary">
                    <img src="http://html.phoenixcoded.net/flatable/assets/images/logo-white.svg" alt="logo image" class="logo-lg"> 
                    <span class="badge bg-primary rounded-pill ms-2 theme-version">v3.0</span></a>
            </div>
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="nav-user-image">
                        <a data-bs-toggle="collapse" href="#navuserlink">
                            <img src="<?=$_SESSION['user_info']['picture']?>" alt="user-image" class="user-avtar rounded-circle">
                        </a>
                    </div>
                    <div class="pc-user-collpsed collapse" id="navuserlink">
                        <h4 class="mb-0">Jonh Smith</h4><span>Administrator</span>
                        <ul>
                            <li>
                                <a class="pc-user-links">
                                    <i class="ph-duotone ph-user"></i>
                                    <span>My Account</span>
                                </a>
                            </li>
                            <li>
                                <a class="pc-user-links">
                                    <i class="ph-duotone ph-gear"></i> 
                                    <span>Settings</span>
                                </a>
                            </li>
                            <li>
                                <a class="pc-user-links">
                                    <i class="ph-duotone ph-lock-key"></i> 
                                    <span>Lock Screen</span>
                                </a>
                            </li>
                            <li>
                                <a class="pc-user-links">
                                    <i class="ph-duotone ph-power"></i> 
                                    <span>Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item pc-caption">
                        <label>System </label>
                        <span>Application Connection</span>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ph-duotone ph-file-video"></i></span>
                            <span class="pc-mtext">Recode System</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="record">Packing record</a></li>
                            <li class="pc-item"><a class="pc-link" href="setting_record">Setting record</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ph-duotone ph-qr-code"></i></span>
                            <span class="pc-mtext">QR-Code System</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="qrcode">Create QR-Code</a></li>
                            <li class="pc-item"><a class="pc-link" href="setting_qrcode">Settings QR-Code</a></li>
                        </ul>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Profile</label>
                        <i class="ph-duotone ph-chart-pie"></i>
                        <span>Member Profile</span>
                    </li>
                    <li class="pc-item">
                        <a href="widget/w_statistics.html" class="pc-link">
                            <span class="pc-micon"><i class="ph-duotone ph-projector-screen-chart"></i></span>
                            <span class="pc-mtext">Statistics</span>
                        </a>
                    </li>
                </ul>
                
                <div class="card nav-action-card">
                    <div class="card-body">
                        <h5 class="text-white">Startup-Dee</h5>
                        <p class="text-white text-opacity-75">Help for beginners.</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>