<script>

        let discountApplied = false;
        let originalTotal = 0;
        let originalNet = 0;

        // Apply Discount
        $('#applyDiscount').on('click', function(e) {
            e.preventDefault();
            console.log(10);

            if (discountApplied) {
                alert('Discount already applied');
                return;
            }

            let discountType = $('#discount_type').val();
            let discountValue = parseFloat($('#discount_value').val()) || 0;

            if (!discountType || discountValue <= 0) {
                alert('Please select discount type and enter a valid discount value');
                return;
            }

            if (!discountApplied) {
                originalTotal = parseFloat($('#Totals').text()) || 0;
                originalNet = parseFloat($('#TotalNet').text()) || 0;
            }

            let currentTotal = parseFloat($('#Totals').text()) || 0;
            let currentNet = parseFloat($('#TotalNet').text()) || 0;
            console.log(currentNet);
            let newTotal = currentTotal;
            let newNet = currentNet;
            let discountAmount = 0;

            if (discountType === 'fixed') {
                discountAmount = discountValue;
                newTotal = currentTotal - discountValue;
                newNet = currentNet - discountValue;
            } else if (discountType === 'percentage') {
                discountAmount = (currentTotal * discountValue) / 100;
                newTotal = currentTotal - discountAmount;
                newNet = currentNet - discountAmount;
            }


            $('#Totals').text(newTotal.toFixed(2));
            $('#TotalNet').text(newNet.toFixed(2));

            let T = newTotal;
            discountApplied = true;

            $('#regular_discount_type').val(discountType);
            $('#regular_discount_value').val(discountValue);
            $('#payments').css('display', 'none');
            $('#applyDiscount').removeClass('btn-primary').addClass('btn-success').text('Applied ✓');


            alert(`Discount Applied!\nDiscount Amount: ${discountAmount.toFixed(2)} QAR\nNew Total: ${newTotal.toFixed(2)} QAR`);
        });

        // Remove Discount
        $('#removeDiscount').on('click', function(e) {
            e.preventDefault();

            if (!discountApplied) {
                alert('No discount applied');
                return;
            }

            // إرجاع القيمة الأصلية
            $('#Totals').text(originalTotal.toFixed(2));
            $('#TotalsNet').text(originalNet.toFixed(2));

            // تحديث الـ session
           /* let totalQTY = updateQuantity();
            let totalPRICE = updateTotalPrice();
            let totalNET = updateTotalNet();
            let totalTAX = updateTotalTax();
            let T = originalTotal;

            let totals = {
                totalQTY: totalQTY,
                totalPRICE: totalPRICE,
                totalNET: totalNET,
                totalTAX: totalTAX,
                Totals: T,
            };*/

            $('#discount_type').val('');
            $('#discount_value').val('');

            discountApplied = false;
            originalTotal = 0;
            originalNet = 0;

            $('#regular_discount_type').val('');
            $('#regular_discount_value').val('');

            $('#payments').css('display', 'block');

            $('#applyDiscount').removeClass('btn-success').addClass('btn-primary').text('Apply Discount');

            alert('Discount Removed!');

            updateTotalNet();
            updateTotalTotal();
        });

        //$('#discount_value').off('keypress');

        $('#discount_value').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
            }
        });


</script>
