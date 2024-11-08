<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    if ($subtopic !== null || isset($_POST['pname'])) {
        $pname = $subtopic !== null ? $subtopic : $_POST['pname'];

        $player = Player::getPlayerByName($pname);
        if ($player) {

            $main_content .= '
                <div class="content-box">
                    <div class="heading">
                        <div class="inner-heading">
                            <span>Player Informations</span>
                        </div>
                    </div>
                    <div class="content">
                        <div class="inner-content">
            ';

            $main_content .= '
                <div class="shadow-box">
                    <div class="inner-shadow-box unbordered">
                        <table class="spaced">
                            <tr>
                                <td width="190" style="font-weight:bolder;">Name:</td>
                                <td>' . htmlspecialchars($player['name']) . '</td>
                            </tr>
            ';
            if (intval($player['group_id']) > 1) {
                $main_content .= '
                            <tr>
                                <td width="190" style="font-weight:bolder;">Title:</td>
                                <td>Admin</td>
                            </tr>
                ';
            }
            $main_content .= '
                            <tr>
                                <td width="190" style="font-weight:bolder;">Sex:</td>
                                <td>' . htmlspecialchars($player['sex']) . '</td>
                            </tr>
                            <tr>
                                <td width="190" style="font-weight:bolder;">Vocation:</td>
                                <td>' . htmlspecialchars($player['vocation']) . '</td>
                            </tr>
                            <tr>
                                <td width="190" style="font-weight:bolder;">Level:</td>
                                <td>' . htmlspecialchars($player['level']) . '</td>
                            </tr>
                            <tr>
                                <td width="190" style="font-weight:bolder;">World:</td>
                                <td>' . htmlspecialchars($player['world_id']) . '</td>
                            </tr>
                            <tr>
                                <td width="190" style="font-weight:bolder;">Town:</td>
                                <td>' . htmlspecialchars($player['town_id']) . '</td>
                            </tr>
                            <tr>
                                <td width="190" style="font-weight:bolder;">Last Login:</td>
                                <td>' . htmlspecialchars(date('Y-m-d H:i:s', $player['lastlogin'])) . '</td>
                            </tr>
            ';
            $false = false;
            if ($false == true) {
                $main_content .= '
                            <tr>
                                <td width="190" style="font-weight:bolder;">Last IP:</td>
                                <td>' . htmlspecialchars(long2ip($player['lastip'])) . '</td>
                            </tr>
                ';
            }
            $main_content .= '
                            <tr>
                                <td width="190" style="font-weight:bolder;">Account Status:</td>
                                <td>' . (htmlspecialchars($player['premend']) > 0 ? 'Premium Account' : 'Free Account') . '</td>
                            </tr>
                            <tr>
                                <td width="190" style="font-weight:bolder;">Online:</td>
                                <td>' . (htmlspecialchars($player['online']) > 0 ? 'Online' : 'Offline') . '</td>
                            </tr>
                            <tr>
                                <td width="190" style="font-weight:bolder;">Broadcasting:</td>
                                <td>' . (htmlspecialchars($player['broadcasting']) > 0 ? 'Online (' . htmlspecialchars($player['viewers']) . ' viewers)' : 'Offline') . '</td>
                            </tr>
                        </table>
                    </div>
                </div>
            ';
        

            $main_content .= '
                        </div>
                    </div>
                </div>
            ';

        } else {
            $main_content .= '
                <div class="info-box">
                    <div class="inner-info-box failure">

                        This player does not exists...

                    </div>
                </div>
            ';
        }
    }

    $main_content .= '
        <div class="content-box">
            <div class="heading">
                <div class="inner-heading">
                    <span>Search Character</span>
                </div>
            </div>
            <div class="content">
                <div class="inner-content">
                    <div class="shadow-box">
                        <div class="inner-shadow-box">

                            <form method="post" action="/characters">
                                <input type="text" placeholder="Character name" name="pname" required/>
                                <div class="button-end">
                                    <button class="sbutton">Search</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    ';