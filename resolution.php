<?php
include 'simplex.php';
$simplex = new Simplex();
$finalBoardData = $simplex->getSimplexResolution();
$variables = $simplex->getFormattedResponse();
$multiple = $simplex->getMin() ? -1 : 1;
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Simplex</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/simplex.css" rel="stylesheet">
        <script src="js/simplex.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <div class="mx-auto">
                <div class="jumbotron">
                    <div class="d-flex justify-content-center">
                        <h1 class="display-4">SIMPLEX - Resultado</h1>
                    </div>

                    <div>
                        <h3>Solução Ótima</h3>
                        <ul class="list-group">
                            <?php foreach ($variables as $key => $label) : ?>
                                <?php $label = $key == 'Z' ? $label * $multiple : $label?>
                                <li class="list-group-item"><?= "{$key} = {$label}"?></li>
                            <?php endforeach; ?>
                        </ul>

                    </div>
                    <h3>Quadro Final do Simplex</h3>
                    <table class="table table-bordered table-light">
                        <thead>
                        <tr>
                            <th scope="col">BASE</th>
                                <?php foreach ($variables as $key => $label) : ?>
                                    <th scope="col"><?= $key == 'Z' ? 'B' : $key ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($simplex->getSimplexResolution() as $key => $row) : ?>
                            <tr>
                                <?php foreach ($row as $rowKey => $label) : ?>
                                    <th scope="row"><?= $label ?></th>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div id="step_by_step" class="justify-content-center" style="display: block !important">
                        <button type="button" onclick="showStepBoards()" class="btn first-step-button">
                            Exibir passo a passo 
                        </button>

                        <div id="step_boards" style="display: none">
                            <?php $count = 0; ?>                
                            <?php foreach($simplex->allBoards() as $board) : ?>
                            <h4><?= !$count ? 'Quadro Inicial' : "Quadro" . $count; ?></h4>
                            <table class="table table-bordered table-light">
                                <thead>
                                    <tr>
                                        <th scope="col">BASE</th>
                                            <?php $variables = $simplex->getFormattedResponse($board); ?>
                                            <?php foreach ($variables as $key => $label) : ?>
                                                <th scope="col"><?= $key == 'Z' ? 'B' : $key ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                <tbody>
                                        
                                    <?php foreach ($board as $key => $row) : ?>
                                        <tr>
                                            <?php foreach ($row as $key => $label) : ?>
                                                <th scope="row"><?= $label ?></th>
                                                <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>       
                            <br>
                            <?php $count++;?>
                            <?php endforeach;?>
                        </div>
                     </div>

                    <div id="resolution_button" class="d-flex justify-content-center">
                        <button type="button" onclick="window.location.href='index.php'" class="btn btn-primary first-step-button">
                            Voltar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </body>
    
</html>