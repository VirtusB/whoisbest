<?php
/**
 * Created by PhpStorm.
 * User: Focuz
 * Date: 27-12-2018
 * Time: 06:53
 */

class User {
    private $_hasScore;
    private $_score;

    public function __construct() {
        if (!empty($_SESSION['steamid'])) {
            $this->_getGameStats();
            $this->_hasScore();
        }

        if (!empty($_SESSION['steamid']) && (empty($_SESSION['steam_uptodate']) || empty($_SESSION['steam_personaname']))) {

            $json = file_get_contents('https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . CONFIG_API_KEY . '&steamids=' . $_SESSION['steamid']);


            $content = json_decode($json, true);
            $_SESSION['steam_steamid'] = $content['response']['players'][0]['steamid'];
            $_SESSION['steam_communityvisibilitystate'] = $content['response']['players'][0]['communityvisibilitystate'];
            $_SESSION['steam_profilestate'] = $content['response']['players'][0]['profilestate'];
            $_SESSION['steam_personaname'] = $content['response']['players'][0]['personaname'];
            $_SESSION['steam_lastlogoff'] = $content['response']['players'][0]['lastlogoff'];
            $_SESSION['steam_profileurl'] = $content['response']['players'][0]['profileurl'];
            $_SESSION['steam_avatar'] = $content['response']['players'][0]['avatar'];
            $_SESSION['steam_avatarmedium'] = $content['response']['players'][0]['avatarmedium'];
            $_SESSION['steam_avatarfull'] = $content['response']['players'][0]['avatarfull'];
            $_SESSION['steam_personastate'] = $content['response']['players'][0]['personastate'];

            if (isset($content['response']['players'][0]['realname'])) {
                $_SESSION['steam_realname'] = $content['response']['players'][0]['realname'];
            } else {
                $_SESSION['steam_realname'] = '';
            }

            $_SESSION['steam_primaryclanid'] = $content['response']['players'][0]['primaryclanid'];
            $_SESSION['steam_timecreated'] = $content['response']['players'][0]['timecreated'];
            $_SESSION['steam_uptodate'] = time();
        }
    }

    private function _hasScore() {
        $hasScore = DB::getInstance()->query('SELECT score FROM scores WHERE steamid = ?', [$_SESSION['steamid']])->results();

        if (count($hasScore) === 0) {
            $this->_hasScore = false;
            $this->_createScore();
        } else {
            $this->_hasScore = true;
            $this->_score = $hasScore[0]->score;
            $this->_updateScore();
        }
    }

    private function _createScore() {
        $score = $this->_calculateScore();
        DB::getInstance()->query('INSERT INTO scores (steamid, score) VALUES (?, ?)', [$_SESSION['steamid'], $score]);
    }

    private function _updateScore() {
        $score = $this->_calculateScore();
        if ($score !== $this->_score) {
            DB::getInstance()->query('UPDATE scores SET score = ? WHERE steamid = ?', [$score, $_SESSION['steamid']]);
        }
    }

    private function _calculateScore() {
        $headShotPercentage = round($_SESSION['stats']['total_kills_headshot'] / $_SESSION['stats']['total_kills'] * 100, 2);
        $winRatio = round($_SESSION['stats']['total_wins'] / $_SESSION['stats']['total_rounds_played'] * 100, 2);
        $kdRatio = round($_SESSION['stats']['total_kills'] / $_SESSION['stats']['total_deaths'], 2);
        $mvps = array_key_exists('total_mvps', $_SESSION['stats']) ? $_SESSION['stats']['total_mvps'] : 0;

        return $_SESSION['stats']['total_matches_played'] + $_SESSION['stats']['total_kills'] - $_SESSION['stats']['total_deaths'] + $_SESSION['stats']['total_kills_headshot'] + $mvps + $_SESSION['stats']['total_time_played'] * ($headShotPercentage + $_SESSION['stats']['total_accuracy'] + $kdRatio + $winRatio);
    }

    public function getCountOfPlayers() {
        return DB::getInstance()->query('SELECT count(*) as playerCount FROM scores')->first()->playerCount;
    }

    public function getRankOfPlayer() {
        return DB::getInstance()->query('SELECT score, FIND_IN_SET( score, (    
                                             SELECT GROUP_CONCAT( score
                                             ORDER BY score DESC ) 
                                             FROM scores )
                                             ) AS rank
                                             FROM scores
                                             WHERE steamid =  ?', [$_SESSION['steamid']])->first()->rank;
    }

    public function getPercentileOfPlayer() {
        $percentiles = [0.1, 0.25, 0.5, 0.75, 0.9, 0.95, 0.99];
        $playerPercentile = null;

        foreach ($percentiles as $percentile) {
            $count = DB::getInstance()->query('SELECT m1.score, count(m2.score) 
                                                  FROM scores m1 INNER JOIN scores m2 ON m2.score<m1.score
                                                WHERE m1.steamid = ?
                                                GROUP BY 
                                                   m1.score,m1.score
                                                ORDER BY 
                                                   ABS(?-(count(m2.score)/(select count(*) from scores)))
                                                LIMIT 1', [$_SESSION['steamid'], $percentile])->count();
            if ($count !== 0) {
                $playerPercentile = $percentile;
            }
        }
        return $playerPercentile;
    }

    private function _getGameStats() {
        $json = @file_get_contents('http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=730&key=' . CONFIG_API_KEY . '&steamid=' . $_SESSION['steamid']);
        $code = $this->_getHttpCode($http_response_header);
        if ($code === 500) {
            die('Your game statistics have to be public. Change it on steamcommunity.com');
        }

        $content = json_decode($json, true);

        /** @var array $stats */
        $stats = $content['playerstats']['stats'];

        foreach ($stats as $stat) {
            $_SESSION['stats'][$stat['name']] = $stat['value'];
        }

        $_SESSION['stats']['total_accuracy'] = $_SESSION['stats']['total_shots_hit'] / $_SESSION['stats']['total_shots_fired'];

        foreach (WEAPONS as $weapon) {
            $name = 'accuracy_' . $weapon;
            $hits = 'total_hits_' . $weapon;
            $shots = 'total_shots_' . $weapon;


            if (!array_key_exists($hits, $_SESSION['stats'])) {
                continue;
            }

            if (!array_key_exists($shots, $_SESSION['stats'])) {
                continue;
            }

            $_SESSION['stats'][$name] = $_SESSION['stats'][$hits] / $_SESSION['stats'][$shots];

            $_SESSION['stats']['weapons']['hits'][$weapon][$hits] = $_SESSION['stats'][$hits];
            $_SESSION['stats']['weapons']['shots'][$weapon][$shots] = $_SESSION['stats'][$shots];

            $_SESSION['stats']['weapons']['accuracy'][$weapon][$name] = $_SESSION['stats'][$hits] / $_SESSION['stats'][$shots];

        }

    }

    private function _getHttpCode($http_response_header)
    {
        if(is_array($http_response_header))
        {
            $parts=explode(' ',$http_response_header[0]);
            if (count($parts)>1) //HTTP/1.0 <code> <text>
            {
                return intval($parts[1]);
            }
        }
        return 0;
    }


}