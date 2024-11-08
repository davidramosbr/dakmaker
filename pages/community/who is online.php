<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    $min = 0; $max = 100;
    if ($subtopic != null && intval($subtopic) > 0) {
        $page = intval($subtopic);
        $min *= $page;
        $max *= $page;
    }
    $onlineList = Player::getPlayersOnline($min, $max);

    $main_content .= '
        <div class="content-box">
            <div class="heading">
                <div class="inner-heading">
                    <span>Online List</span>
                </div>
            </div>
            <div class="content">
                <div class="inner-content">
    ';

    $main_content .= '
                    <div class="shadow-box">
                        <div class="inner-shadow-box unbordered">
                            <table class="spaced">
    ';
    if (count($onlineList) > 0) {
        foreach ($onlineList as $player) {
        $main_content .= '
                                    <tr>
                                        <td>' . htmlspecialchars($player['name']) . '</td>
                                        <td width="50" align="center">' . htmlspecialchars($player['level']) . '</td>
                                    </tr>
        ';
        }
    } elseif (Player::countOnlinePlayers() == 0) {
        $main_content .= '
            <tr>
                <td align="center">There is no players online from now.</td>
            </tr>
        ';
    } else {
        $main_content .= '
            <tr>
                <td align="center">Seems like you reached the end of the list.</td>
            </tr>
        ';
    }
    $main_content .= '
                            </table>
                        </div>
                    </div>
    ';
    $main_content .= '
                </div>
            </div>
        </div>
    ';