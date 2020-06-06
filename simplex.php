<?php
/**
 * Class Simplex
 * @author Janaina Ferreira da Silva
 * @author Israel dos Santos Elias
 * @author Juliana Nascimento Silva
 */

class Simplex {
    protected $_objectiveFunction;
    protected $_restrictions;
    protected $_boards;
    protected $_allDecisionVariables;
    protected $_allSlackVariables;
    protected $_min;
    const MAX_INTERACTION = 20;

    /**
     * Simplex constructor.
     */
    public function __construct()
    {
        $this->_objectiveFunction = $_POST['objective_function'];
        $this->_restrictions = $_POST['restriction'];
        $this->_min = $_POST['min_max'];
        $this->_initialBoard();
    }

    /**
     * @return int
     */
    public function getVariableQty()
    {
        return count($this->_objectiveFunction);
    }

    public function getRestrictionQty()
    {
        return count($this->_restrictions);
    }

    /**
     * @return mixed
     */
    public function getMin()
    {
        return $this->_min;
    }

    /**
     * @return mixed
     */
    public function getSimplexResolution()
    {
        $count = 0;
        while ($this->_validateInteraction(end($this->_boards)) && $count < self::MAX_INTERACTION) {
            $this->_interaction();
            $count++;
        }

        return end($this->_boards);
    }

    /**
     * @return mixed
     */
    public function allBoards()
    {
        return $this->_boards;
    }

    /**
     * Retorna as variaiveis e seus respectivos valores após finalizar o simples
     */
    public function getFormattedResponse($board = null)
    {
        $board = $board ?: $finalBoard = end($this->_boards);
        $result = [];
        $variables = array_merge($this->_allDecisionVariables, $this->_allSlackVariables);
        foreach ($variables as $variable) {
            $result[$variable] = 0;
            foreach ($board as $row => $columns) {
                if ($columns[0] == $variable) {
                    $result[$variable] = end($columns);
                    continue;
                }
            }
        }

        $result['Z'] = $board[$this->getRestrictionQty()][$this->getRestrictionQty() + $this->getVariableQty() + 1];
        return $result;
    }
    /**
     * Realiza a interação que monta os quadros do simplex
     */
    protected function _interaction()
    {
        $newBoard = [];
        $oldBoard = end($this->_boards);
        $inColumnIndex = $this->_getInColumn($oldBoard);
        $outRowIndex = $this->_getOutRow($oldBoard, $inColumnIndex);

        $rowQty = $this->getRestrictionQty() + 1;
        $columnQty = $this->getVariableQty() + $rowQty;

        $pivo = $oldBoard[$outRowIndex][$inColumnIndex];

        $newBoard[$outRowIndex][0] = $inColumnIndex <= $this->getVariableQty() ? "X{$inColumnIndex}" : "F{$inColumnIndex}";
        for ($i = 1; $i <= $columnQty; $i++)
            $newBoard[$outRowIndex][$i] = $oldBoard[$outRowIndex][$i] / $pivo;

        for ($i = 0; $i < $rowQty; $i++) {
            for ($j = 0; $j <= $columnQty; $j++) {
                if ($i == $outRowIndex)
                    continue;

                if ($j == 0) {
                    $newBoard[$i][$j] = $oldBoard[$i][$j];
                    continue;
                }
                $newBoard[$i][$j] = $newBoard[$outRowIndex][$j] * ($oldBoard[$i][$inColumnIndex] * -1) + $oldBoard[$i][$j];
            }
        }

        $this->_boards[] = $newBoard;
    }

    /**
     * Valida se o simplex ainda possui um valor negativo na linha da função objetiva
     * @param $board
     * @return bool
     */
    protected function _validateInteraction($board)
    {
        $lastRow = end($board);

        for ($i = 0; $i < count($lastRow); $i++) {
            if ($lastRow[$i] < 0)
                return true;
        }

        return false;
    }

    /**
     * Retorna o index da coluna que entrara
     * @param $board
     * @return false|int|string
     */
    protected function _getInColumn($board)
    {
        $lastRow = end($board);
        $minValue = min($lastRow);
        return array_search($minValue, $lastRow);
    }

    /**
     * @param $board
     * @param $inIndex
     * @return false|int|string
     */
    protected function _getOutRow($board, $inIndex)
    {
        $divisions = [];
        $lastColumn = count($board[0])-1;

        for ($i = 0; $i < count($board) - 1; $i++) {
            $divisions[$i] = '';
            if ($board[$i][$inIndex])
                $divisions[$i] = $board[$i][$lastColumn] / $board[$i][$inIndex];
        }

        $min = max($divisions);
        foreach ($divisions as $division) {
            $min = $division > 0 && $min > $division ? $division : $min;
        }

        return array_search($min, $divisions);
    }

    /**
     * Gera o quadro inicial
     */
    protected function _initialBoard()
    {
        $board = [];
        $variablesQty = $this->getVariableQty();

        $slackVariables = $this->_getSlackVariables();

        for ($i = 1; $i <= $variablesQty; $i++)
            $this->_allDecisionVariables[] = "X{$i}";

        for ($i = 1; $i <= $this->getRestrictionQty(); $i++)
            $this->_allSlackVariables[] = "F{$i}";


        foreach ($this->_restrictions as $key => $restriction) {
            $boardRow = [];
            $boardRow[] = "F" . ($key+1);

            for ($i = 0; $i < $variablesQty; $i++)
                $boardRow[] = $restriction['variables'][$i];

            $boardRow = array_merge($boardRow, $slackVariables[$key]);
            $boardRow[] = $restriction['value'];
            $board[] = $boardRow;
        }

        $ofRow[] = "Z";
        $multiple = $this->_min ? 1 : -1;
        for ($i = 0; $i < $variablesQty; $i++)
            $ofRow[] = $this->_objectiveFunction[$i] * $multiple;

        $ofRow = array_merge($ofRow, end($slackVariables));
        $ofRow[] = 0;
        $board[] = $ofRow;

        $this->_boards[] = $board;
    }

    /**
     * Gera um array das varivaeis de folga iniciais
     * @return array
     */
    protected function _getSlackVariables()
    {
        $restrictionQty = $this->getRestrictionQty();

        $slackVariables = [];
        for ($i = 0; $i < $restrictionQty + 1; $i++) {
            $slackRow = [];
            for ($j = 0; $j < $restrictionQty; $j++)
                $slackRow[] = $i == $j ? 1 : 0;

            $slackVariables[] = $slackRow;
        }

        return $slackVariables;
    }
}
