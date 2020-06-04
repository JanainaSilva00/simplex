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
                        <h1 class="display-4">SIMPLEX</h1>
                    </div>
                    <form class="first-step-form" action="resolution.php" method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="variables_qty">Quantidade de Variáveis</label>
                                <input type="number" class="form-control" id="variables_qty" min="1" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="restriction_qty">Quantidade de Restrições</label>
                                <input type="number" class="form-control" id="restriction_qty" min="1" required>
                            </div>
                            <div class="form-group col-md-4" >
                                <label for="min_max">Tipo</label>
                                <select name="min_max" id="min_max" class="browser-default custom-select" >
                                    <option value="0">Maximizar</option>
                                    <option value="1">Minimizar</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 align-self-end">
                                <button type="button" class="btn btn-primary first-step-button" onclick="nextStep()">
                                    Próximo Passo
                                </button>
                            </div>
                        </div>

                        <div id="objective_function_div" class="formula-div" style="visibility: hidden">
                            <label>Função Objetiva</label>
                            <div id="objective_function" class="formula-row input-group">
                            </div>
                        </div>

                        <div id="restrictions_div" clas
                             hs="formula-div" style="visibility:hidden;">
                            <label>Restrições</label>
                            <div id="restrictions">
                            </div>
                        </div>

                        <div id="resolution_button" class="d-flex simplex-button" style="visibility: hidden">
                            <button type="button" onclick="cleanSimplexData()" class="btn btn-secondary first-step-button">
                                Limpar
                            </button>

                            <button type="submit" class="btn btn-primary first-step-button">
                                Resolver
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>