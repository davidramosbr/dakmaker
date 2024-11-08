<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    if (Account::isLogged()) {
        header('Location: /account-manager');
        exit;
    }

    if (isset($_POST['account']) && isset($_POST['password']) && isset($_POST['monsterCaptcha'])) {
        $account = trim($_POST['account']);
        $password = trim($_POST['password']);
        $captchaCode = md5(strtolower(trim($_POST['monsterCaptcha'])));
    
        function isValid($input): bool {
            return strlen($input) >= 4 && strlen($input) <= 15 && strpos($input, ' ') === false;
        }

        function isValidCaptcha(string $captchaCode): bool {
            return (Functions::getCaptchaMonsterByCode($captchaCode) !== null ? true : false);
        }
    
        if (isValid($account) && isValid($password) && isValidCaptcha($captchaCode)) {
            if (Account::createAccount($account, $password)) {
                Account::doLogin($account, $password);
                header('Location: /account-manager/create-character');
                exit();
            } else {
                $main_content .= 'Failed to create account. Please try again.';
            }
        } else {
            $main_content .= 'Account name and password must be between 4 and 15 characters long and cannot contain spaces. Check also your captcha.';
        }
    }

    $captchaMonster = Functions::sortCaptchaMonster();

    $main_content .= '
        <div class="content-box">
            <div class="heading">
                <div class="inner-heading">
                    <span>Account Register</span>
                </div>
            </div>
            <div class="content">
                <div class="inner-content">
                    <div class="shadow-box">
                        <div class="inner-shadow-box">

                            <form method="post">
                                <input type="text" placeholder="Account" name="account" required/>
                                <input type="text" placeholder="Password" name="password" required/>
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
                                <div class="button-end">
                                    <button class="sbutton">Create Account</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    ';