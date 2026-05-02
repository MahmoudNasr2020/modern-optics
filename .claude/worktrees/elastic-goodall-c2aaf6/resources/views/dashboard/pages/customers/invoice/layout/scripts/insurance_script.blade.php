<script>
    $('#applyInsurance').on('click', function () {

        if ($('.product_invoice_table table tbody tr').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Products',
                text: 'Please add products to the invoice before applying insurance.',
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

                // remove discount attr
                $row.removeAttr('data-insurance-discount');
            });

            // remove value of input
            $('#insurance_id_input').val('');
            $('#insurance_discounts_input').val('');
            $('#insurance_approval_amount_input').val('');

            $btn.text('Apply').removeClass('btn-danger').addClass('btn-primary');
            $btn.data('applied', false);

            updateTotalNet();
            updateTotalTotal();

        } else {
            // ===== APPLY DISCOUNT =====
            let selectedValue = $('#insurance').val();

            if (!selectedValue || selectedValue == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Select Insurance',
                    text: 'Please select an insurance first.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            let selectedOption = $('#insurance').find(':selected');
            let selectedDiscounts = selectedOption.data('discounts');

            let approval = 0;
            if ($('#approval_amount').length) {
                approval = parseFloat($('#approval_amount').val()) || 0;
            }

            $('#insurance_id_input').val(selectedValue);
            $('#insurance_approval_amount_input').val($('#approval_amount').val());

            // حساب الإجمالي بعد الخصم
            let totalAfterDiscount = 0;

            $('.product_invoice_table table tbody tr').each(function () {
                let $row = $(this);
                let categoryId = $row.data('category');
                let discount = selectedDiscounts[String(categoryId)];

                let originalNet = parseFloat($row.attr('data-original-net') || $row.find('.NET').text());
                let newNet = originalNet;

                if (discount !== undefined) {
                    newNet = newNet - (newNet * (discount / 100));
                    $row.attr('data-insurance-discount', discount);
                }

                $row.find('.NET').text(newNet.toFixed(2));

                let tax = parseFloat($row.find('.TAX').text());
                let newTotal = newNet + tax;
                $row.find('.TOTALS').text(newTotal.toFixed(2));

                totalAfterDiscount += newTotal;
            });

           
            if (approval > 0) {
                totalAfterDiscount -= approval;
                if (totalAfterDiscount < 0) totalAfterDiscount = 0;
            }

            // تحديث قيمة الإجمالي بعد الخصم في الـ TotalNet
            $('#TotalNet').text(totalAfterDiscount.toFixed(2));

            $btn.text('Delete Discount').removeClass('btn-primary').addClass('btn-danger');
            $btn.data('applied', true);  // تغيير data-applied إلى true

            updateTotalNet();
            updateTotalTotal();
        }
    });

</script>
