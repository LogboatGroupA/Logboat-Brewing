<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a id="homeLogoLink" class="navbar-brand" href="<?= getBaseUrl(); ?>">
                <img id="homeLogo" src="http://static1.squarespace.com/static/54f0cacce4b094783bb94952/t/55677e8ce4b0abaf8a0f4182/1449002915219/?format=1500w" alt="Home">
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="<?= getBaseUrl(); ?>ingredient/showAll.php">Inventory</a></li>
            <li><a href="<?= getBaseUrl(); ?>schedule/showCalendar.php">Scheduling</a></li>
            <li><a href="<?= getBaseUrl(); ?>recipe/showAll.php">Recipes</a></li>
            <li><a href="<?= getBaseUrl(); ?>keg/showAll.php">Keg Rentals</a></li>
            <li><a href="<?= getBaseUrl(); ?>ingredient/showLow.php">Orders</a></li>
            <li><a href="<?= getBaseUrl(); ?>analytics/selectBrew.php">Analytics</a></li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
              <?php if(!isLoggedIn()) { ?>

              <li><a href="<?= getBaseUrl() ?>user/login.php">Login</a></li>

              <?php } else { ?>

                <?php if(isUserAdmin()) { ?>

                <li><a href="<?= getBaseUrl(); ?>user/showAll.php">Users</a></li>

                <li style="padding:15px">|</li>

                <?php } ?>

                <li style="height:50px; padding:0px 15px">
                    <div class="center-vertical">
                        <p class="center-block text-center" style="margin:0">Logged in as:</p>
                        <a class="center-block text-center" style="padding:0" href="<?= getBaseUrl(); ?>user/user.php?id=<?= $_SESSION['userId']; ?>"><?= $_SESSION['username'] ?></a>
                    </div>
                </li>
                <li><a href="<?= getBaseUrl() ?>api/user/logout.php">Logout</a></li>

              <?php } ?>
          </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
