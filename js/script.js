(function($){

	$(document).ready(function() {
        getProducts();

        function getProducts() {
			$('#products-grid, .save-success').hide();
			$('.prices-loading').show();
			var data = {
				action: 'get_products',
				nonce: $('#wooprices_nonce').val(),
				category: $('#select-category option:selected').val(),
				taxonomy: $('#select-taxonomy option:selected').val(),
				term_id: $('#select-term option:selected').val()
			};
			$.post(ajaxurl, data, function(json) {
				var data = jQuery.parseJSON(json);
				if (data) {
					$('.prices-loading').hide();
					$('#products-grid').show();
					grid.setData(data);
					// Select all rows 
					var rows = [];
					for (var i = 0; i < data.length; i++) {
						rows.push(i);
					}
					grid.setSelectedRows(rows);
					grid.render(); 
				}
			});		
		}

        function saveProducts() {
            grid.getEditController().commitCurrentEdit();
            // Get selected products
            var selected = grid.getSelectedRows();
            var data = grid.getData();
            var products = [];
            jQuery.each(selected, function (index, i) {			
                products.push(data[i]);
            });
            // Save products
            var data = {
                action: 'save_products',
                nonce: $('#wooprices_nonce').val(),		
                products: products
            };
            $('.save-success').hide();
            $('.saving-products').css('display', 'inline-block');
            $.post(ajaxurl, data, function(response) {
                $('.saving-products').hide();
                $('.save-success').css('display', 'inline-block');
            })		
        }	

        $('.save-button').click(function() {
			var save = confirm('Do you want to save these changes?');
			if (save) {
				saveProducts();
			}
		});
    });

})(jQuery);