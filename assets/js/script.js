let selectProduct = document.querySelector('#cpp_wc_products');

selectProduct.onchange = function(){   
    let selectOption = this.selectedOptions[0].getAttribute('data-product-type');
    let selectOptionId = this.selectedOptions[0].id;

    let variableTable = document.querySelectorAll('.cpp_wc_variable_table');
    variableTable.forEach(table => {
        if (selectOption == 'simple' && table.classList.contains('active')) {
            table.classList.remove('active')
        }

        let variableTableId = table.getAttribute('data-id');

        if (variableTableId == selectOptionId) {
            table.classList.add('active');
        } else {
            table.classList.remove('active');
        }
    })
};

let variableKey = document.querySelectorAll('.variable_key');
let variableChecked = [];

variableKey.forEach(key => {
    key.addEventListener('change', () => {
        variableChecked.push(key.value);
        console.log(variableChecked);
    })
})