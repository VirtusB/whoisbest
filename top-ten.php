<?php

require_once 'head.php';
require_once 'nav.php';

$userManager = new UserManager();


?>

    <link rel="stylesheet" href="/assets/css/flag-icon.min.css">

    <main style="position:relative; top: 120px;">
        <section id="more" class="container pb-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive ">
                        <table class="table table-hover table-sm">
                            <tbody>
                            <tr>
                                <th class="w-20">Picture</th>
                                <th class="w-20">Username</th>
                                <th class="w-20">Rank</th>
                                <th class="w-20">Real Name</th>
                                <th class="w-5">Country</th>
                                <th class="w-15">Created</th>
                            </tr>
                            <?php
                                $players = $userManager->getTopTenPlayers();
                                usort($players, function ($item1, $item2) {
                                    return $item1['rank'] <=> $item2['rank'];
                                });

                                foreach ($players as $player) {
                                    $imgSrc = $player['avatarmedium'];
                                    $username = $player['personaname'];
                                    $rank = $player['rank'];
                                    $realName = array_key_exists('realname', $player) ? $player['realname'] : '';
                                    $country = array_key_exists('loccountrycode', $player) ? $player['loccountrycode'] : '';
                                    $country = strtolower($country);
                                    $created = date('d/m/Y', $player['timecreated']);

                                    $thisIsYouHTML = '';
                                    if (Auth::isLoggedIn() && $_SESSION['steamid'] === $player['steamid']) {
                                        $thisIsYouHTML = "data-toggle='tooltip' id='this-is-you' data-animation='false' data-placement='left' title='' data-original-title='This is you' class='red-tooltip'";
                                    }


                                    echo "
                                        <tr $thisIsYouHTML>
                                            <td><img src='$imgSrc'></td>
                                            <td>$username</td>
                                            <td>$rank</td>
                                            <td>$realName</td>
                                            <td><span class='flag-icon flag-icon-$country'></span></td>
                                            <td>$created</td>
                                        </tr>
                                    ";

                                    if ($thisIsYouHTML !== '') {
                                        echo "<script>
                                                document.getElementById('this-is-you').addEventListener('mouseleave', function (e) {
                                                    e.stopImmediatePropagation();
                                                    e.stopPropagation();
                                                    e.preventDefault();
                                                });
                                        </script>";
                                    }


                                }

                            ?>
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </section>

    </main>

<script src="/assets/js/top-ten.js"></script>

<?php
require_once 'footer.php';