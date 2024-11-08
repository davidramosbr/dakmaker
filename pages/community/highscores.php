<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    $min = 0; $max = 100; $orderlist = 'level';
    if ($subtopic != null && intval($subtopic) > 0) {
        $page = intval($subtopic);
        $min *= $page;
        $max *= $page;
    }
    $rankList = Player::getPlayersOrderedBy($orderlist, $min, $max);

    $main_content .= '
        <div class="content-box">
            <div class="heading">
                <div class="inner-heading">
                    <span>Ranking</span>
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
    foreach ($rankList as $player) {
    $main_content .= '
                                <tr>
                                    <td>' . htmlspecialchars($player['name']) . '</td>
                                    <td width="50" align="center">' . htmlspecialchars($player['level']) . '</td>
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