<?php

require_once 'head.php';
require_once 'nav.php';


if (!Auth::isLoggedIn()) {
    header('Location: /');
}

?>


    <main style="position:relative; top: 120px;">
        <section class="container">
            <div class="row mb-2">
                <div class="col-12 text-center">
                    <h2>You are rank <span class="text-primary">#<?= $user->getRankOfPlayer() ?></span> out of <span class="text-primary"><?= $user->getCountOfPlayers() ?></span> total players</h2>
                    <?php
                    $percentile = $user->getPercentileOfPlayer();
                    if ($percentile !== null) {
                        $highestPercentileHTML = $percentile === 0.99 ? "<span style='font-size: 13px; vertical-align: text-top;' class='badge badge-primary copyable'>whoa</span>" : '';
                        $percentile *= 100;
                        echo '<h2>You are in the <span class="text-primary">' . $percentile . 'th</span> percentile' . $highestPercentileHTML . '</h2>';
                    }
                    ?>
                </div>
            </div>
            <div class="row ">
                <div class="col-12 my-auto">
                    <div class="row text-center">
                        <div class="col-lg-4 mb-4">
                            <div class="card ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-snow-outline"></span></h1>
                                    <h4 class="card-title text-primary"><i class="fas fa-crosshairs"></i> Total Accuracy</h4>
                                    <p class="card-text"><?= round($_SESSION['stats']['total_accuracy'] * 100, 2) ?> %</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <div class="card ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-tablet-portrait-outline"></span></h1>
                                    <h4 class="card-title text-primary"><i class="fas fa-clock"></i> Time Played</h4>
                                    <p class="card-text"><?= number_format(round($_SESSION['stats']['total_time_played'] / 60 / 60, 0)) ?> hrs.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <div class="card  ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-checkmark-circle-outline"></span></h1>
                                    <h4 class="card-title text-primary"><i class="fas fa-heart-broken"></i> Total Damage</h4>
                                    <p class="card-text"><?= number_format($_SESSION['stats']['total_damage_done']) ?></p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row text-center">
                        <div class="col-lg-4 mb-4">
                            <div class="card  ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-checkmark-circle-outline"></span></h1>
                                    <h4 class="card-title text-primary"><i class="gi gi-gun"></i> Total Kills</h4>
                                    <p class="card-text"><?= number_format($_SESSION['stats']['total_kills']) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <div class="card  ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-checkmark-circle-outline"></span></h1>
                                    <h4 class="card-title text-primary"><i class="fas fa-tombstone"></i> Total Deaths</h4>
                                    <p class="card-text"><?= number_format($_SESSION['stats']['total_deaths']) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <div class="card  ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-checkmark-circle-outline"></span></h1>
                                    <h4 class="card-title text-primary"><i class="fas fa-money-check-alt"></i> Total Money</h4>
                                    <p class="card-text"><?= money_format('%(#10n', $_SESSION['stats']['total_money_earned']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-lg-4 mb-4">
                            <div class="card  ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-checkmark-circle-outline"></span></h1>
                                    <h4 class="card-title text-primary"><img style="max-height: 31px" src="/assets/img/headshot.png" alt=""> Total Headshots</h4>
                                    <p class="card-text"><?= number_format($_SESSION['stats']['total_kills_headshot']) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <div class="card  ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-checkmark-circle-outline"></span></h1>
                                    <h4 class="card-title text-primary"><i class="fas fa-bullseye"></i> Headshot Percent</h4>
                                    <p class="card-text"><?= round($_SESSION['stats']['total_kills_headshot'] / $_SESSION['stats']['total_kills'] * 100, 2) ?> %</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <div class="card  ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-checkmark-circle-outline"></span></h1>
                                    <h4 class="card-title text-primary"><i class="fas fa-trophy"></i> Win Ratio</h4>
                                    <p class="card-text"><?= round($_SESSION['stats']['total_wins'] / $_SESSION['stats']['total_rounds_played'] * 100, 2) ?> %</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-lg-4 mb-4">
                            <div class="card  ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-checkmark-circle-outline"></span></h1>
                                    <h4 class="card-title text-primary"><i class="fas fa-flag-checkered"></i> Total Matches</h4>
                                    <p class="card-text"><?= number_format($_SESSION['stats']['total_matches_played']) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-4">
                            <div class="card  ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-checkmark-circle-outline"></span></h1>
                                    <h4 class="card-title text-primary"><i class="fas fa-stopwatch"></i> Wins/hr.</h4>
                                    <p class="card-text"><?= round($_SESSION['stats']['total_wins'] /  ($_SESSION['stats']['total_time_played'] / 60 / 60), 2) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-4">
                            <div class="card  ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-checkmark-circle-outline"></span></h1>
                                    <h4 class="card-title text-primary"><i class="fas fa-star"></i> Total MVPs</h4>
                                    <p class="card-text"><?= number_format(array_key_exists('total_mvps', $_SESSION['stats']) ? $_SESSION['stats']['total_mvps'] : 0) ?></p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row text-center">
                        <div class="col-lg-4 mb-4">
                            <div class="card  ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-checkmark-circle-outline"></span></h1>
                                    <h4 class="card-title text-primary"><i class="fas fa-thermometer-three-quarters"></i> Headshots/round</h4>
                                    <p class="card-text"><?= round($_SESSION['stats']['total_kills_headshot'] / $_SESSION['stats']['total_rounds_played'], 2) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-4">
                            <div class="card  ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-checkmark-circle-outline"></span></h1>
                                    <h4 class="card-title text-primary"><i class="gi gi-ammo"></i> K/D ratio</h4>
                                    <p class="card-text"><?= round($_SESSION['stats']['total_kills'] / $_SESSION['stats']['total_deaths'], 2) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-4">
                            <div class="card  ">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h1 class="display-2 text-primary"><span class="ion ion-ios-checkmark-circle-outline"></span></h1>
                                    <h4 class="card-title text-primary"><i class="gi gi-medal-star"></i> MVPs/round</h4>
                                    <p class="card-text"><?= round(array_key_exists('total_mvps', $_SESSION['stats']) ? $_SESSION['stats']['total_mvps'] / $_SESSION['stats']['total_rounds_played'] : 0 / $_SESSION['stats']['total_rounds_played'], 2) ?></p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </section>

        <section id="more" class="container pb-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive ">
                        <table class="table table-hover table-sm">
                            <tbody>
                            <tr>
                                <th class="w-25">Weapon</th>
                                <th class="w-25">Shots Fired</th>
                                <th class="w-25">Shots Hit</th>
                                <th class="w-25">Accuracy</th>
                            </tr>
                            <?php

                            $max_hits = ['weapon_name' => '', 'hits' => 0];
                            $max_shots = ['weapon_name' => '', 'shots' => 0];
                            $max_accuracy = ['weapon_name' => '', 'accuracy' => 0];


                            foreach ($_SESSION['stats']['weapons']['hits'] as $wep) {
                                foreach ($wep as $w) {
                                    if ($w > $max_hits['hits']) {
                                        $max_hits['hits'] = $w;
                                        $max_hits['weapon_name'] = key($wep);
                                    }
                                }
                            }

                            foreach ($_SESSION['stats']['weapons']['shots'] as $wep) {
                                foreach ($wep as $w) {
                                    if ($w > $max_shots['shots']) {
                                        $max_shots['shots'] = $w;
                                        $max_shots['weapon_name'] = key($wep);
                                    }
                                }
                            }

                            foreach ($_SESSION['stats']['weapons']['accuracy'] as $wep) {
                                foreach ($wep as $w) {
                                    if ($w > $max_accuracy['accuracy']) {
                                        $max_accuracy['accuracy'] = $w;
                                        $max_accuracy['weapon_name'] = key($wep);
                                    }
                                }
                            }



                            foreach (WEAPONS as $weapon) {
                                $name = 'accuracy_' . $weapon;
                                $shots = 'total_shots_' . $weapon;
                                $hits = 'total_hits_' . $weapon;

                                if (!array_key_exists($hits, $_SESSION['stats'])) {
                                    continue;
                                }

                                if (!array_key_exists($shots, $_SESSION['stats'])) {
                                    continue;
                                }

                                $special_shots = '';
                                $special_hits = '';
                                $special_accuracy = '';
                                if ($shots === $max_shots['weapon_name']) {
                                    $special_shots = "<span class='badge badge-primary copyable'>your #1</span>";
                                }
                                if ($hits === $max_hits['weapon_name']) {
                                    $special_hits = "<span class='badge badge-primary copyable'>your #1</span>";
                                }
                                if ($name === $max_accuracy['weapon_name']) {
                                    $special_accuracy = "<span class='badge badge-primary copyable'>your #1</span>";
                                }

                                $shots = number_format($_SESSION['stats'][$shots]);
                                $hits = number_format($_SESSION['stats'][$hits]);
                                $accuracy = round($_SESSION['stats'][$name] * 100, 0);



                                echo "<tr>
                                    <td>$weapon</td>
                                    <td>$shots $special_shots</td>
                                    <td>$hits $special_hits</td>
                                    <td>$accuracy % $special_accuracy</td>
                                </tr>";
                            }
                            ?>
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </section>

    </main>


<?php
require_once 'footer.php';