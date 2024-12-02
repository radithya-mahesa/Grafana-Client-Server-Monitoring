<header>
        <nav class="navbar navbar-dark sticky-top" style="background: transparent;">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <a class="navbar-brand" href="#">
                    <img src="/../../assets/ic-dial-logo.png" alt="Logo" width="80" height="45" class="d-inline-block align-text-top">
                </a>
                <div class="d-flex align-items-center">
                    <form action="services.php" method="post">
                        <button class="btn ms-5" type="submit" name="logout" style="background: #3F83F8; color:#fff; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
                            Log Out
                        </button>
                    </form>
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
            <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header" style="background-color: #05a2e4;">
                    <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body" style="background-color: #fff;">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-4">
                        <li class="nav-item">
                            <h5><a class="nav-link" href="#" class="btn ms-5" data-bs-toggle="modal" data-bs-target="#sessionModal" style="color: #3F83F8;">Session History</a></h5>
                        </li>
                        <button type="button" class="btn ms-5" data-bs-toggle="modal" data-bs-target="#addTableModal" style="background: #3F83F8; color:#fff">
                            Add Table
                        </button>
                    </ul>
                </div>
            </div>
        </nav>
    </header>