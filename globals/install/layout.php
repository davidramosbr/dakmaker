<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/globals/install/css/basic.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/globals/install/css/all.css">
    <script src="initialize.js"></script>
</head>
<body>
    <header>
        <div class="container">
            <div class="step-bar">
                <div class="step <?php echo ($install_step == 1 ? 'active' : '');?>">
                    <div class="icon"><i class="fa-solid fa-database"></i></div>
                    <span>Database</span>
                </div>
                <div class="step <?php echo ($install_step == 2 ? 'active' : '');?>">
                    <div class="icon"><i class="fa-solid fa-table"></i></div>
                    <span>Tabelas</span>
                </div>
                <div class="step <?php echo ($install_step == 3 ? 'active' : '');?>">
                    <div class="icon"><i class="fa-solid fa-pencil"></i></div>
                    <span>Definições</span>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="card">
                <?php echo $install_content; ?>
            </div>
        </div>
    </main>
    
</body>
</html>