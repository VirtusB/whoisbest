<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary" id="navbar1">
    <div class="container">
        <a class="navbar-brand mr-1 mb-1 mt-0" href="../"><i class="fas fa-crosshairs"></i> Who Is Best</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse justify-content-center" id="collapsingNavbar">
            <ul class="navbar-nav ml-auto">

                <?php
                if (Auth::isLoggedIn()) {
                    echo '<li class="nav-item"><a href="profile" style="color: #fff;" class="nav-link"><i class="fas fa-user"></i> My profile</a></li>';
                }

                echo '<li class="nav-item"><a href="top-ten" class="nav-link"><i class="fas fa-trophy-alt"></i> Top 10</a></li>';

                ?>

                <li class="nav-item">
                    <?php
                    if (Auth::isLoggedIn()) {
                        echo '<a class="nav-link" href="?logout">
                                Logout
                              </a>';
                    } else {
                        echo '<a class="nav-link" href="?login">
                                <img id="login-img" src="http://pubg.aerowolforg.com/assets/images/steamIcon.png" alt="Login">
                              </a>';
                    }
                    ?>
                </li>
            </ul>
        </div>
    </div>
</nav>