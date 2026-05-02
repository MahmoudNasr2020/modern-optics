<script>

    function formatMethods(paymentBoxes) {
        let paymentDetails = [];
        totalPaied = 0;
        paymentBoxes.forEach(box => {
            if (box.innerHTML != '') {
                let oneWay = {
                    type: box.querySelector('.payment_type').value,
                    bank: box.querySelector('#Bank').value,
                    card_number: box.querySelector('#Card_No').value,
                    expiration_date: box.querySelector('#expiration_date').value,
                    payed_amount: box.querySelector('#paied').value,
                };
                totalPaied += (+oneWay.payed_amount);
                paymentDetails.push(oneWay);

            }
        });
        return paymentDetails;
    }

    // Update Quantity after adding or deleiting invoice Item
    function updateQuantity(totalQTY) {
        let QTY = document.querySelectorAll('.QTY');
        totalQTY = 0;
        QTY.forEach(q => {
            totalQTY += parseInt(q.innerHTML);
        });

        $('#TotalQTY').html(totalQTY);
        document.getElementById('TotalQuantity').value = totalQTY;
        return totalQTY;
    }

    // Update Total Price after adding or deleting invoice Item
    function updateTotalPrice() {
        let PRICE = document.querySelectorAll('.PRICE');
        let QUNTITY = document.querySelectorAll('.QTY');
        let totalPRICE = 0;
        /*PRICE.forEach(q => {
            totalPRICE += parseInt(q.innerHTML);
        });*/
        PRICE.forEach((p, i) => {
            let price = parseFloat(p.innerHTML) || 0;
            let qty = parseFloat(QUNTITY[i].innerHTML) || 0;
            totalPRICE += price * qty;
        });

        $('#TotalPrice').html(totalPRICE);
        document.getElementById('T_Price').value = totalPRICE;
        return totalPRICE;
    }

    // Update Total NET after adding or deleting invoice Item
    function updateTotalNet() {
        let NET = document.querySelectorAll('.NET');
        let totalNET = 0;
        let payerType = $('#payer_type').val();
        let approvalAmount = parseFloat($('#approval_amount').val()) || 0;

        NET.forEach(q => {
            totalNET += parseFloat(q.innerHTML);
        });


        if (payerType === 'insurance' &&  approvalAmount > 0) {
            totalNET -= approvalAmount;
            if (totalNET < 0) totalNET = 0;
        }

        $('#TotalNet').html(totalNET.toFixed(2));
        document.getElementById('T_Net').value = totalNET.toFixed(2);
        return totalNET;
    }


    // Update Total TAX after adding or deleting invoice Item
    function updateTotalTax() {
        let TAX = document.querySelectorAll('.TAX');
        totalTAX = 0;
        TAX.forEach(q => {
            totalTAX += parseInt(q.innerHTML);
        });
        $('#TotalTax').html(totalTAX);
        document.getElementById('T_Tax').value = totalTAX;
        return totalTAX;
    }

    // Update Total TOTAL after adding or deleting invoice Item
    function updateTotalTotal() {
        let TOTALS = document.querySelectorAll('.TOTALS');
        let T = 0;
        let approvalAmount = parseFloat($('#approval_amount').val()) || 0;
        let payerType = $('#payer_type').val();

        // حساب إجمالي TOTALS
        TOTALS.forEach(q => {
            T += parseFloat(q.innerHTML);
        });

        // خصم سعر الموافقة من الإجمالي الكلي فقط إذا كان زر "Apply Insurance" قد تم الضغط عليه
        if (payerType === 'insurance' &&  approvalAmount > 0) {
            T -= approvalAmount;
            if (T < 0) T = 0;
        }

        // تحديث القيمة في الخلية التي تحتوي على id="Totals"
        $('#Totals').html(T.toFixed(2));
        document.getElementById('T_Totals').value = T.toFixed(2);  // تحديث الحقل المخفي مع القيمة الجديدة
        return T;
    }


    //delete product from session
    function deleteProductFromSession(product_id) {
        let route = "{{ route('dashboard.delete-data-from-session') }}";
        $.ajax({
            url: route,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                product_id: product_id
            },
            success: function (response) {
                if (response.success) {
                    new Noty({
                        text: '✅ Product was successfully removed from the session.',
                        type: 'success',
                        killer: true,
                        timeout: 2000
                    }).show();

                    styleNotyLayout('success');
                } else {
                    new Noty({
                        text: '⚠️ Failed to remove product from the session.',
                        type: 'warning',
                        killer: true,
                        timeout: 3000
                    }).show();
                    styleNotyLayout('danger');
                }
            },
            error: function (xhr) {

            }
        });
    }

</script>
