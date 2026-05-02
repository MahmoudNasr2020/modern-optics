<script>
    let selectedDiscounts = {};

    $('#applyCardholder').on('click', function () {

        if ($('.product_invoice_table table tbody tr').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Products',
                text: 'Please add products to the invoice before applying a cardholder.',
                confirmButtonText: 'OK'
            });
            return;
        }

        let $btn = $(this);

        if ($btn.data('applied') === true) {
            // ===== DELETE DISCOUNT =====
            $('.product_invoice_table table tbody tr').each(function () {
                let $row = $(this);
                let originalNet = $row.attr('data-original-net');
                let originalTotal = $row.attr('data-original-total');

                $row.find('.NET').text(originalNet);
                $row.find('.TOTALS').text(originalTotal);

                //remove discount attr
                $row.removeAttr('data-cardholder-discount');
            });

            //remove value of input
            $('#cardholder_id_input').val('');
            $('#cardholder_discounts_input').val('');

            $btn.text('Apply').removeClass('btn-danger').addClass('btn-primary');
            $btn.data('applied', false);

            updateTotalNet();
            updateTotalTotal();

        }
        else {
            // ===== APPLY DISCOUNT =====
            let selectedValue = $('#cardholder').val();

            if (!selectedValue || selectedValue == 0) {
                //  SweetAlert
                Swal.fire({
                    icon: 'warning',
                    title: 'Select Cardholder',
                    text: 'Please select a cardholder first.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            let selectedOption = $('#cardholder').find(':selected');
            let selectedDiscounts = selectedOption.data('discounts');


            $('#cardholder_id_input').val(selectedValue);
            $('#cardholder_discounts_input').val(JSON.stringify(selectedDiscounts));

            $('.product_invoice_table table tbody tr').each(function () {
                let $row = $(this);
                let categoryId = $row.data('category');
                let discount = selectedDiscounts[String(categoryId)];

                if (discount !== undefined) {
                    let originalNet = parseFloat($row.attr('data-original-net') || $row.find('.NET').text());
                    let newNet = originalNet - (originalNet * (discount / 100));
                    $row.find('.NET').text(newNet.toFixed(2));

                    let tax = parseFloat($row.find('.TAX').text());
                    let newTotal = newNet + tax;
                    $row.find('.TOTALS').text(newTotal.toFixed(2));

                    //add discount attr to row
                    $row.attr('data-cardholder-discount', discount);
                }
            });

            $btn.text('Delete Discount').removeClass('btn-primary').addClass('btn-danger');
            $btn.data('applied', true);

            updateTotalNet();
            updateTotalTotal();
        }
    });
</script>
