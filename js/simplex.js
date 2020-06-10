var variableQty = 0;
var restrictionQty = 0;
var simplexBoard = [];

function nextStep() {
    cleanSimplexData();
    variableQty = parseInt(document.getElementById('variables_qty').value);
    restrictionQty = parseInt(document.getElementById('restriction_qty').value);
    objectiveFunctionFields();
    restrictionFields();
}

function objectiveFunctionFields() {
    document.getElementById('objective_function_div').style.visibility = 'visible';
    let obElement = document.getElementById('objective_function');

    for (let i = 0; i < variableQty; i++) {
        let inputEl = document.createElement('input');
        inputEl.setAttribute('type', 'number');
        inputEl.setAttribute('required', 'required');
        inputEl.setAttribute('name', 'objective_function[' + i + ']');

        let spanEl = document.createElement('span');
        let textValue =  `X${i + 1}` + (i + 1 < variableQty ? ' + ' : '');
        let textEl = document.createTextNode(textValue);
        spanEl.appendChild(textEl);

        obElement.appendChild(inputEl);
        obElement.appendChild(spanEl);
    }
}

function restrictionFields() {
    document.getElementById('restrictions_div').style.visibility = 'visible';
    let resElement = document.getElementById('restrictions');

    for (let i = 0; i < restrictionQty; i++) {
        let restrictionRow = document.createElement('div');
        restrictionRow.className = 'formula-row input-group';
        for (let j = 0; j < variableQty; j++) {
            let inputEl = document.createElement('input');

            inputEl.setAttribute('type', 'number');
            inputEl.setAttribute('required', 'required');

            inputEl.setAttribute('name', 'restriction[' + i + '][variables][' + j + ']');

            let spanEl = document.createElement('span');
            let textValue =  `X${j + 1}` + (j + 1 < variableQty ? ' + ' : '');
            let textEl = document.createTextNode(textValue);
            spanEl.appendChild(textEl);

            restrictionRow.appendChild(inputEl);
            restrictionRow.appendChild(spanEl);
        }

        let resValueInput = document.createElement('input');

        resValueInput.setAttribute('type', 'number');
        resValueInput.min = '0';
        resValueInput.setAttribute('name', 'restriction[' + i + '][value]');

        let spanValueSpan = document.createElement('span');
        let textEl = document.createTextNode(' <= ');
        spanValueSpan.appendChild(textEl);

        restrictionRow.appendChild(spanValueSpan);
        restrictionRow.appendChild(resValueInput);

        resElement.appendChild(restrictionRow);
    }
    document.getElementById('resolution_button').style.visibility = 'visible';
}

function cleanSimplexData() {
    document.getElementById('objective_function').innerHTML = '';
    document.getElementById('restrictions').innerHTML = '';
    document.getElementById('objective_function_div').style.visibility = 'hidden';
    document.getElementById('restrictions_div').style.visibility = 'hidden';
}

function showStepBoards() {
    document.getElementById('step_boards').style.display = 'block';
}