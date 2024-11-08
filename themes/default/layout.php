<?php
    $layout_version = time();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $site_title; ?></title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
        <link rel="stylesheet" href="/themes/default/css/basic.css?v=<?php echo $layout_version; ?>">
        <link rel="stylesheet" href="/themes/default/css/button.css?v=<?php echo $layout_version; ?>">
        <link rel="stylesheet" href="/themes/default/css/content.css?v=<?php echo $layout_version; ?>">
        <link rel="stylesheet" href="/themes/default/css/news.css?v=<?php echo $layout_version; ?>">
        <link rel="stylesheet" href="/themes/default/css/basic-mobile.css?v=<?php echo $layout_version; ?>" media="only screen and (max-width: 767px)">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="/themes/default/js/initialize.js?v=<?php echo $layout_version; ?>"></script>
    </head>
    <body>
        <div id="preloader">
            <img src="/themes/default/images/background/logo.png" width="400px"/>
            <span>Carregando...</span>
        </div>
        <div class="mobile mobile-topbar">
            <button onclick="$('.mobile-menu').toggleClass('hidden');">
                <i class="fa-solid fa-bars"></i>
            </button>
            <a href="/account-manager">
                <i class="fa-solid fa-user"></i>
            </a>
        </div>
        <div class="mobile mobile-menu hidden">
            <?php 
                foreach (Menu::getMenuList() as $menuTopic => $menuSubtopics) {
                    echo '
                        <div class="menu-holder">
                            <div class="menu-item">
                                <img src="../../pages/'.$menuTopic.'/icon.gif"/>
                                <span>'.$menuTopic.'</span>
                            </div>
                            <div class="menu-submenus">
                    ';
                    
                    foreach ($menuSubtopics as $menuSubtopic) {
                        $isActive = false;
                        if (str_replace('-', ' ', $topic) == $menuSubtopic) {
                            $isActive = true;
                        }
                        
                        echo '
                            <a href="/'.str_replace(' ', '-', $menuSubtopic).'" class="submenu-item">
                                <span '.($isActive ? 'class="active"' : '').'>'.$menuSubtopic.'</span>
                            </a>
                        ';
                    }

                    echo '
                            </div>
                        </div>
                    ';
                }
            ?>
        </div>
        <div class="main-grid">
            <div class="grid-header"></div>
            <div class="grid-topbar">
                <div class="topbar-container cornered">
                    <div class="corner top-left"></div>
                    <div class="corner top-right"></div>
                    <div class="corner bottom-left"></div>
                    <div class="corner bottom-right"></div>
                    <div class="topbar-content">
                        <div class="content">
                            <div class="fast-menu">
                                <a href="/characters">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <span>Search Players</span>
                                </a>
                                <span>|</span>
                                <a href="/download">
                                    <i class="fa-solid fa-download"></i>
                                    <span>Download Client</span>
                                </a>
                            </div>
                            <div class="online">
                                <a href="#">
                                    <i class="fa-brands fa-facebook"></i>
                                    <span>Facebook</span>
                                </a>
                                <span>|</span>
                                <a href="#">
                                    <i class="fa-brands fa-instagram"></i>
                                    <span>Instagram</span>
                                </a>
                                <span>|</span>
                                <a href="/who-is-online">
                                    <i class="fa-solid fa-users"></i> <?php echo $players_online; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid-menu">
                <div class="menu-container">
                    <div class="content">
                        <?php if (Account::isLogged()) { ?>
                        <a href="/account-manager" class="mbutton">My Account</a>
                        <?php } else { ?>
                        <a href="/account-manager" class="mbutton">Login</a>
                        <a href="/create-account" class="nbutton">Create Account</a>
                        <?php } ?>
                    </div>
                </div>
                <div class="menu-container">
                    <div class="content">
                        <a href="/download" class="mbutton green">Download</a>
                    </div>
                </div>
                <div class="menu-container">
                    <?php 
                        foreach (Menu::getMenuList() as $menuTopic => $menuSubtopics) {
                            echo '
                                <div class="menu-holder">
                                    <div class="menu-item">
                                        <img src="../../pages/'.$menuTopic.'/icon.gif"/>
                                        <span>'.$menuTopic.'</span>
                                    </div>
                                    <div class="menu-submenus">
                            ';
                            
                            foreach ($menuSubtopics as $menuSubtopic) {
                                $isActive = false;
                                if (str_replace('-', ' ', $topic) == $menuSubtopic) {
                                    $isActive = true;
                                }
                                
                                echo '
                                    <a href="/'.str_replace(' ', '-', $menuSubtopic).'" class="submenu-item">
                                        <span '.($isActive ? 'class="active"' : '').'>'.$menuSubtopic.'</span>
                                    </a>
                                ';
                            }

                            echo '
                                    </div>
                                </div>
                            ';
                        }
                    ?>
                </div>
            </div>
            <div class="grid-themeboxes">

                <?php

                    $tbData = [];
                    $tbData['level_rank'] = Player::getPlayersOrderedBy('level', 0, 5);


                    // level rank display
                    echo '
                    <div class="themebox-container">
                        <div class="title">
                            <img src="https://tibiawiki.com.br/images/c/cc/Arboreal_Tome.gif"/>
                            <span>Rank Level</span>
                        </div>
                        <div class="content">
                            <div class="list">
                    ';

                    $i = 0;
                    foreach ($tbData['level_rank'] as $player) {
                        $i++;
                        echo '
                                <div class="list-item">
                                    <img class="outfit" src="https://outfit-images.ots.me/1285_walk_animation/animoutfit.php?id='.$player['looktype'].'&addons='.$player['lookaddons'].'&head='.$player['lookhead'].'&body='.$player['lookbody'].'&legs='.$player['looklegs'].'&feet='.$player['lookfeet'].'&mount=0&direction=3"/>
                                    <img class="medal" src="/themes/default/images/content/medal-'.($i < 4 ? $i : 0).'.gif"/>
                                    <div class="data">
                                        <span class="name">'.$player['name'].'</span>
                                        <span class="info">Level: '.$player['level'].'</span>
                                    </div>
                                </div>
                        ';
                    }
                    unset($i);

                    echo '
                            </div>
                        </div>
                    </div>
                    ';


                    unset($tbData);

                ?>
            </div>
            <div class="grid-content">
                <div class="topic-container cornered">
                    <div class="corner top-left"></div>
                    <div class="corner top-right"></div>
                    <div class="corner bottom-left"></div>
                    <div class="corner bottom-right"></div>

                    <div class="content-title">
                        <span><?php echo ucwords(str_replace('-', ' ', $topic)); ?></span>
                    </div>
                    <div class="content-holder">
                        <div class="scroll">
                            <?php echo $main_content; ?>
                        </div>
                    </div>
                </div>
                <footer>
                    <div>Website for <?php echo $site_name;?>, edited by <a href="#">OTSAssets</a> since 2023 to <?php echo date("Y"); ?>.</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        <span>|</span>
                        <a href="#">Help Support</a>
                        <span>|</span>
                        <a href="#">Donation Terms</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>