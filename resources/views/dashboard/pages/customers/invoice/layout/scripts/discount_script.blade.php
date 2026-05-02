<script>


        const $type = $('#insurance_cardholder_type');
        const $payer = $('#payer_select');
        const $label = $('#payer_label');
        const $approvalWrap = $('#approval_amount_wrap');
        const $payerTypeHidden = $('#payer_type');

        function resetPayerSelect(placeholderText = 'Choose one') {
            $payer.empty()
                .append(`<option value="" disabled selected>${placeholderText}</option>`)
                .prop('disabled', true);
        }

        function toggleApprovalAmount(show) {
            const approvalWrap = document.getElementById('approval_amount_wrap');
            if (show) {
                approvalWrap.style.display = 'block';
            } else {
                approvalWrap.style.display = 'none';
            }
        }


        $type.on('change', function() {
            const val = $(this).val();
            //$payerTypeHidden.val(val);

            if (!val) {
                resetPayerSelect();
                toggleApprovalAmount(false);
                return;
            }

            if (val === 'insurance') {
                $label.text('Choose Insurance Company');
                toggleApprovalAmount(true);
            } else {
                $label.text('Choose Cardholder');
                toggleApprovalAmount(false);
            }

            resetPayerSelect(val === 'insurance' ? 'Choose Insurance Company' : 'Choose Cardholder');

            $.ajax({
                url: '{{ route("dashboard.discount-get-type") }}',
                method: 'GET',
                data: { type: val },
                success: function(resp) {
                    const items = (resp && resp.data) ? resp.data : [];
                    if (items.length === 0) {
                        if (window.Swal) Swal.fire({icon:'info', title:'No data', text:'لا توجد نتائج متاحة لهذا النوع.'});
                        return;
                    }
                    items.forEach(function(item){
                        let option = new Option(item.name, item.id);

                        let categoriesDiscounts = item.categories.map(function(cat) {
                            return { [cat.id]: cat.pivot.discount_percent };
                        });

                        let discountsData = JSON.stringify(categoriesDiscounts);

                        option.setAttribute('data-discounts', discountsData);

                        $payer.append(option);
                    });
                    $payer.prop('disabled', false);
                },
                error: function(xhr) {
                    if (window.Swal) {
                        Swal.fire({icon:'error', title:'Error', text:'حدث خطأ أثناء تحميل البيانات.'});
                    } else {
                        alert('Error loading data');
                    }
                }
            });
        });


        // when click show
        $('#showPayer').on('click', function() {

            let payerType = $('#insurance_cardholder_type').val();
            let payerId = $('#payer_select').val();

            if (!payerId || !payerType) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Data',
                    text: 'Please select a payer type and payer.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            $.ajax({
                url: '{{ route('dashboard.discount-get-single-type') }}',
                type: 'GET',
                data: {
                    payer_type: payerType,
                    payer_id: payerId,
                },
                success: function(response) {
                    if (response.success) {
                        let tableHTML = `
                        <table class="table table-bordered table-hover table-striped table-success">
                            <thead>
                                <tr style="background:#428bca;color:#fff;font-size: 17px;">
                                    <th>Name</th>
                                    <td colspan="${response.data.categories.length}" style="font-weight: bold;">${response.data.name}</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background:#f4f4f4;">
                                    <th>Category</th>`;

                        response.data.categories.forEach(function(category) {
                            tableHTML += `<td>${category.category_name}</td>`;
                        });

                        tableHTML += `</tr>
                                <tr>
                                    <th>Discounts</th>`;

                        response.data.categories.forEach(function(category) {
                            tableHTML += `<td>${category.pivot.discount_percent}%</td>`;
                        });

                        tableHTML += `</tr>
                            </tbody>
                        </table>`;

                        $('#discountTable').html(tableHTML);
                        $('#payerModal').modal('show');

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Could not fetch payer details.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while fetching data.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });



        // apply discount
        let selectedDiscounts = {};

        $('#insurance_cardholder_type').on('change', function() {
            let payerType = $(this).val(); // Get selected payer type (insurance or cardholder)

            if (payerType === 'insurance') {
                // Enable the payer select for insurance
                $('#payer_select').prop('disabled', false);
                $('#approval_amount_wrap').show(); // Show approval amount for insurance
            } else if (payerType === 'cardholder') {
                // Enable the payer select for cardholder
                $('#payer_select').prop('disabled', false);
                $('#approval_amount_wrap').hide(); // Hide approval amount for cardholder
            } else {
                $('#payer_select').prop('disabled', true); // Disable the payer select
                $('#approval_amount_wrap').hide(); // Hide approval amount in case no type is selected
            }
        });

        $('#applyPayer').on('click', function() {
            let payerType = $('#payer_type');
            if ($('.product_invoice_table table tbody tr').length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Products',
                    text: 'Please add products to the invoice before applying a payer.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            let $btn = $(this);

            if ($btn.data('applied') === true) {
                // DELETE DISCOUNT
                $('.product_invoice_table table tbody tr').each(function() {
                    let $row = $(this);
                    let originalNet = $row.attr('data-original-net');
                    let originalTotal = $row.attr('data-original-total');

                    $row.find('.NET').text(originalNet);
                    $row.find('.TOTALS').text(originalTotal);

                    $row.attr('data-payer-discount',0);
                });

                $('#payer_type').val('');
                $('#payer_type_id').val('');
                $('#payer_select').val('');
                $('#approval_amount').val('');
                $('#payer_type_approval_amount').val('');
                $('#discount').css('display', 'block');

                $btn.text('Apply').removeClass('btn-danger').addClass('btn-primary');
                $btn.data('applied', false);

                updateTotalNet();
                updateTotalTotal();
            } else {
                // APPLY DISCOUNT
                let selectedValue = $('#payer_select').val();

                if (!selectedValue || selectedValue == 0) {
                    // SweetAlert
                    Swal.fire({
                        icon: 'warning',
                        title: 'Select Payer',
                        text: 'Please select a payer first.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                let selectedOption = $('#payer_select').find(':selected');
                let selectedDiscounts = selectedOption.data('discounts');

                let selectType = $('#insurance_cardholder_type').find(':selected');

              //$('#payer_select').val(selectedValue);
                payerType.val(selectType.text().toLowerCase());
                $('#payer_type_id').val(selectedValue);

                let totalAfterDiscount=0;
                // Apply discount to the rows based on payer type
                $('.product_invoice_table table tbody tr').each(function() {

                    let $row = $(this);
                    let categoryId = $row.data('category');
                    console.log(categoryId);
                    let discount = selectedDiscounts.reduce((acc, item) => {
                        if (item.hasOwnProperty(String(categoryId))) {
                            return item[String(categoryId)];
                        }
                        return acc;
                    }, null);


                    if (discount !== undefined) {
                        let originalNet = parseFloat($row.attr('data-original-net') || $row.find('.NET').text());
                        let newNet = originalNet - (originalNet * (discount / 100));
                        $row.find('.NET').text(newNet.toFixed(2));
                        let tax = parseFloat($row.find('.TAX').text());
                        let newTotal = newNet + tax;
                        $row.find('.TOTALS').text(newTotal.toFixed(2));

                        $row.attr('data-payer-discount', discount);
                        totalAfterDiscount += newTotal;

                    }
                });

                if (payerType.val() === 'insurance') {
                    let approvalAmount = parseFloat($('#approval_amount').val()) || 0;

                   // $('#approval_amount').val(approvalAmount);
                    if(approvalAmount > 0){
                        totalAfterDiscount -= approvalAmount;
                        if (totalAfterDiscount < 0) totalAfterDiscount = 0;
                        $('#payer_type_approval_amount').val(approvalAmount);
                    }

                }


                $('#TotalNet').text(totalAfterDiscount.toFixed(2));

                $btn.text('Delete Discount').removeClass('btn-primary').addClass('btn-danger');
                $btn.data('applied', true);
                $('#discount').css('display', 'none');

                updateTotalNet();
                updateTotalTotal();
            }
        });


</script>
