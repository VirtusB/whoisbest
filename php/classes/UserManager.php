<?php
/**
 * Created by PhpStorm.
 * User: Focuz
 * Date: 30-12-2018
 * Time: 00:04
 */

class UserManager {
    public function getTopTenPlayers() : array {
        $steamIdsAndRanks = $this->_getTopTenSteamIdsAndRanks();
        $commaSeperatedSteamIds = '';

        foreach ($steamIdsAndRanks as $steamIdsAndRank) {
            if ($commaSeperatedSteamIds === '') {
                $commaSeperatedSteamIds .= $steamIdsAndRank->steamid;
            } else {
                $commaSeperatedSteamIds .= ',' . $steamIdsAndRank->steamid;
            }
        }

        $json = file_get_contents('https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . CONFIG_API_KEY . '&steamids=' . $commaSeperatedSteamIds);

        $content = json_decode($json, true);

        /** @var array $players */
        $players = $content['response']['players'];
        $playersWithRank = [];

        foreach ($players as $player) {
            foreach ($steamIdsAndRanks as $steamIdsAndRank) {
                if ($player['steamid'] === $steamIdsAndRank->steamid) {
                    $playerWithRank = $player;
                    $playerWithRank['rank'] = $steamIdsAndRank->rank;
                    $playersWithRank[] = $playerWithRank;
                }
            }
        }

        return $playersWithRank;
    }

    private function _getTopTenSteamIdsAndRanks() : array {
        $steamIdsAndRanks = DB::getInstance()->query('SELECT steamid, FIND_IN_SET( score, (    
                                             SELECT GROUP_CONCAT( score
                                             ORDER BY score DESC ) 
                                             FROM scores )
                                             ) AS rank
                                             FROM scores
											ORDER BY rank ASC
                                            				LIMIT 10')->results();

        return $steamIdsAndRanks;
    }
}