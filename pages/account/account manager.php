<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    if (!Account::isLogged()) {
        if (isset($_POST['account']) && isset($_POST['password'])) {
            $account = trim($_POST['account']);
            $password = trim($_POST['password']);
            if (Account::doLogin($account, $password)) {
                header('Refresh:0;');
                exit;
            }
        }
    
        // formulário de login
        $main_content .= '
            <div class="content-box">
                <div class="heading">
                    <div class="inner-heading">
                        <span>Account Login</span>
                    </div>
                </div>
                <div class="content">
                    <div class="inner-content">
                        <div class="shadow-box">
                            <div class="inner-shadow-box">

                                <form method="post">
                                    <input type="text" placeholder="Account" name="account" required/>
                                    <input type="text" placeholder="Password" name="password" required/>
                                    <div class="button-spaced">
                                        <a href="/create-account" class="sbutton red">Create Account</a>
                                        <button class="sbutton">Login</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ';
    } else {
        if ($subtopic == "logout") {
            Account::doLogout();
            header('Location: /account-manager');
            exit;
        } elseif ($subtopic == "create-character") {
            if (isset($_POST['charname']) && isset($_POST['charsex']) && isset($_POST['monsterCaptcha'])) {
                $charname = ucfirst(trim($_POST['charname']));
                $charsex = (int) $_POST['charsex'];
                $captchaCode = md5(strtolower(trim($_POST['monsterCaptcha'])));

                function isValid(string $input): bool {
                    return strlen($input) > 3 && ctype_alpha($input);
                }

                function isValidCaptcha(string $captchaCode): bool {
                    return (Functions::getCaptchaMonsterByCode($captchaCode) !== null ? true : false);
                }
            
                if (isValid($charname) && isValidCaptcha($captchaCode)) {
                    if (Account::createCharacter($charname, $charsex)) {
                        $main_content .= 'The character '.$charname.' has been created!';
                    } else {
                        $main_content .= 'There was a failure to create the character.';
                    }
                } else {
                    if (isValid($charname) == false) {
                        $main_content .= 'The character name "'.$charname.'" is not valid!';
                    }
                    if (isValidCaptcha($captchaCode) == false) {
                        $main_content .= 'The captcha "'.$captchaCode.'" is not valid!';
                    }
                    $main_content .= 'The name must have more than 3 characters and cannot have any special characters or numbers. Please be careful when filling out the captcha.';
                }
            } else {
                $captchaMonster = Functions::sortCaptchaMonster();
                $main_content .= '
                    <div class="content-box">
                        <div class="heading">
                            <div class="inner-heading">
                                <span>Create Character</span>
                            </div>
                        </div>
                        <div class="content">
                            <div class="inner-content">
                                <div class="shadow-box">
                                    <div class="inner-shadow-box">

                                        <form method="post">
                                            <table>
                                                <tr>
                                                    <td style="padding: 0;">
                                                        <input type="text" placeholder="Character name" name="charname" autocomplete="off" style="width:100%" required/>
                                                    </td>
                                                    <td width="1%">
                                                        <div class="inline-radio">
                                                            <label for="sexMaleInput">
                                                                <input type="radio" name="charsex" id="sexMaleInput" value="1" required/>
                                                                <span>Male</span>
                                                            </label>
                                                            <label for="sexFemaleInput">
                                                                <input type="radio" name="charsex" id="sexFemaleInput" value="0" required/>
                                                                <span>Female</span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th colspan="2" align="center" style="padding: 6px;">Monster Captcha</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 40px;position: relative;background: #ccc;">
                                                            <img src="https://outfit-images.ots.me/latest_walk/animoutfit.php?id='.$captchaMonster[0].'&direction=3" style="position: absolute;top: -30px;left: -24px;">
                                                        </td>
                                                        <td style="padding: 0;background: white;">
                                                            <img src="/script/captcha/'.$captchaMonster[1].'" style="width:100%;height: 32px;box-sizing: border-box;">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <input type="text" placeholder="What is the name of the monster?" name="monsterCaptcha" required/>
                                            <div class="button-spaced">
                                                <a href="/account-manager" class="sbutton red">Go Back</a>
                                                <button class="sbutton">Create Character</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
            }
        } elseif ($subtopic == "delete-character") {
            if (isset($_POST['charname']) && isset($_POST['monsterCaptcha'])) {
                $charname = ucfirst(trim($_POST['charname']));
                $captchaCode = strtolower(trim($_POST['monsterCaptcha']));

                function isValid(string $input): bool {
                    return strlen($input) > 3 && ctype_alpha($input);
                }

                function isValidCaptcha(string $captchaCode): bool {
                    return (Functions::getCaptchaMonsterByCode($captchaCode) !== null ? true : false);
                }
            
                if (isValid($charname) && isValidCaptcha($captchaCode)) {
                    if (Account::deleteCharacter($charname)) {
                        $main_content .= 'O personagem '.$charname.' foi deletado!';
                    } else {
                        $main_content .= 'Houve uma falha ao deletar o personagem.';
                    }
                } else {
                    $main_content .= 'O nome precisa ter mais de 3 caracteres e não pode ter nenhum caractere especial ou número. Tenha atenção ao preencher o captcha.';
                }
            } else {
                $captchaMonster = Functions::sortCaptchaMonster();
                $main_content .= '
                    <div class="content-box">
                        <div class="heading">
                            <div class="inner-heading">
                                <span>Delete Character</span>
                            </div>
                        </div>
                        <div class="content">
                            <div class="inner-content">
                                <div class="shadow-box">
                                    <div class="inner-shadow-box">

                                        <form method="post">
                                            <input type="text" placeholder="Character name" name="charname" autocomplete="off" style="width:100%" required/>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th colspan="2" align="center" style="padding: 6px;">Monster Captcha</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 40px;position: relative;background: #ccc;">
                                                            <img src="https://outfit-images.ots.me/latest_walk/animoutfit.php?id='.$captchaMonster[0].'&direction=3" style="position: absolute;top: -30px;left: -24px;">
                                                        </td>
                                                        <td style="padding: 0;background: white;">
                                                            <img src="/script/captcha/'.$captchaMonster[1].'" style="width:100%;height: 32px;box-sizing: border-box;">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <input type="text" placeholder="What is the name of the monster?" name="monsterCaptcha" required/>
                                            <div class="button-spaced">
                                                <a href="/account-manager" class="sbutton red">Go Back</a>
                                                <button class="sbutton">Create Character</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
            }
        } elseif ($subtopic == null) {
            

            // character list
            $charList = Player::getPlayersByAccountID(Account::getSelfId());
            $main_content .= '
                <div class="content-box">
                    <div class="heading">
                        <div class="inner-heading">
                            <span>Character List</span>
                        </div>
                    </div>
                    <div class="content">
                        <div class="inner-content">
                            <div class="shadow-box">
                                <div class="inner-shadow-box unbordered">
                                    <table>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th width="1%">View</th>
                                        </tr>
            ';
            foreach ($charList as $player) {
              $main_content .= '
                                        <tr>
                                            <td class="outfit">    
                                                <img src="https://outfit-images.ots.me/1285_walk_animation/animoutfit.php?id='.$player['looktype'].'&addons='.$player['lookaddons'].'&head='.$player['lookhead'].'&body='.$player['lookbody'].'&legs='.$player['looklegs'].'&feet='.$player['lookfeet'].'&mount=0&direction=3"/>
                                            </td>
                                            <td class="pnameLevel">
                                                <b>'.$player['name'].'</b>
                                                <span>Level: '.$player['level'].'</span>
                                            </td>
                                            <td><a href="/characters/'.strtolower($player['name']).'" class="sbutton">View Character</a>
                                        </tr>
              ';
            }
            $main_content .= '
                                    </table>
                                </div>
                            </div>                            
                            <div class="shadow-box">
                                <div class="inner-shadow-box">
                                    <div class="button-spaced">
                                        <a href="/account-manager/delete-character" class="sbutton red">Delete Character</a>
                                        <a href="/account-manager/create-character" class="sbutton green">Create Character</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ';

        } else {
            header("Location:/account-manager");
            exit;
        }
    }