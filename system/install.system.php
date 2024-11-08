<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    $install_content = '';

    $install_tables = ['zdsite_news', 'zdsite_donationhistory', 'zdsite_shop', 'zdsite_shophistory'];
    if (!$SQL->isConnected || !$SQL->checkTables($install_tables) || $config->getConfigValue('general.installed') != "yes") {
        $config->setConfigValue('general.installed', "no");

        if (!$SQL->isConnected) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['host']) && isset($_POST['port']) && isset($_POST['username']) && isset($_POST['dbname'])) {
                    $config->setConfigValue('database.host', $_POST['host']);
                    $config->setConfigValue('database.port', $_POST['port']);
                    $config->setConfigValue('database.username', $_POST['username']);
                    $config->setConfigValue('database.password', (isset($_POST['password']) ? $_POST['password'] : ''));
                    $config->setConfigValue('database.dbname', $_POST['dbname']);
                    header('Refresh: 0');
                    exit();
                } else {
                    $install_content .= '<b>Erro: Você precisa enviar todos os dados.</b>';
                }
            }
    
            $install_step = 1;
            $install_content .= '
                <form method="post">
                    <p class="mb-1">Informe o banco de dados que será utilizado:</p>
                    <input type="text" placeholder="Servidor" name="host" value="'.$config->getConfigValue('database.host').'" autofocus required/>
                    <input type="text" placeholder="Porta" name="port" value="'.$config->getConfigValue('database.port').'" required/>
                    <input type="text" placeholder="Usuário" name="username" value="'.$config->getConfigValue('database.username').'" required/>
                    <input type="text" placeholder="Senha" name="password"/>
                    <input type="text" placeholder="Database" name="dbname" value="'.$config->getConfigValue('database.dbname').'" required/>
                    <button class="submitButton">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Salvar Configurações</span>
                    </button>
                </form>
            ';
        } elseif (!$SQL->checkTables($install_tables)) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if ($SQL->createDefaultTables()) {
                    header('Refresh: 0');
                    exit();
                } else {
                    $install_content .= '<b>Erro: Algo deu errado na criação das tabelas...</b>';
                }
            }

            $install_step = 2;
            $install_content .= '
                <div class="content">
                    <table class="check-table">
                        <tr>
                            <th>#</th>
                            <th>Tabela</th>
                            <th>Status</th>
                        </tr>
            ';

            foreach ($install_tables as $install_table) {
                $tableExists = $SQL->tableExists($install_table);
                $install_content .= '
                    <tr class="'.($tableExists ? 'success' : 'failure').'">
                        <td><i class="fa-solid fa-'.($tableExists ? 'circle-check' : 'circle-xmark').'"></i></td>
                        <td>'.$install_table.'</td>
                        <td>'.($tableExists ? 'Existe' : 'Falha').'</td>
                    </tr>
                ';
                unset($tableExists);
                unset($install_table);
            }

            $install_content .= '
                    </table>
                    <form method="post">
                        <input type="hidden" name="type" value="addTables"/>
                        <button class="submitButton">
                            <i class="fa-solid fa-database"></i>
                            <span>Adicionar Tabelas</span>
                        </button>
                    </form>
                </div>
            ';
        } else {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['theme'])) {
                    $config->setConfigValue('general.installed', "yes");
                    $config->setConfigValue('general.theme', $_POST['theme']);
                    $config->setConfigValue('general.servername', $_POST['servername']);
                    header('Refresh: 0');
                    exit();
                }
            }

            $install_step = 3;
            $install_content .= '
                <div class="content">
                    <table class="check-table">
                        <tr>
                            <th>#</th>
                            <th>Etapa</th>
                            <th>Status</th>
                        </tr>
                        
                        <tr class="success">
                            <td><i class="fa-solid fa-circle-check"></i></td>
                            <td>Database</td>
                            <td>Funcional</td>
                        </tr>
                        
                        <tr class="success">
                            <td><i class="fa-solid fa-circle-check"></i></td>
                            <td>Tabelas</td>
                            <td>Funcional</td>
                        </tr>

                    </table>
                    
                    <h3 class="mt-3 mb-1">Definições Gerais</h3>
                    <p class="mb-1">Configurações como a de tema podem ser personalizadas posteriormente, consulte a página administrativa após finalização da instalação.</p>

                    <form method="post">
                        <input type="text" placeholder="Nome do servidor" name="servername" required/>
                        <select name="theme" required>
                            <option value="">Selecione um tema</option>
            ';

            $dir = 'themes/'; $files = scandir($dir);
            $folders = array_filter($files, function($item) use ($dir) {
                return is_dir($dir . $item) && $item !== '.' && $item !== '..';
            });
            foreach ($folders as $folder) {
                $install_content .= "<option value=\"$folder\">".ucfirst($folder)."</option>";
            }

            $install_content .= '
                        </select>
                        <button class="submitButton">
                            <i class="fa-solid fa-check"></i>
                            <span>Finalizar Configurações</span>
                        </button>
                    </form>
                </div>
            ';
        }

        include("globals/install/layout.php");
        exit();
    }

    unset($install_content);
    unset($install_step);
    unset($install_tables);