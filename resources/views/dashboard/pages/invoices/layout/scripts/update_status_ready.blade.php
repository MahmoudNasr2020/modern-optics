<script>
    $('.ready').on('ifChanged', function(event){
        var invoiceItemId = $(this).data('invoice_item_id');
        var status = $(this).is(':checked') ? 'checked' : 'unchecked';

        $.ajax({
            url: '/dashboard/update-status-itemInvoice/'+invoiceItemId,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                invoiceItem_id: invoiceItemId,
                status: status
            },
            success: function(response) {
                var n = new Noty({
                    text: 'Status changed successfully!',
                    killer: true,
                    type: 'success',
                    timeout:3000
                }).show();

                styleNotyLayout('success');
            },
            error: function() {
                console.log('Error in the request');
            }
        });
    });
</script>


<script>
    $('.delivery').on('ifChanged', function(event){
        var invoiceItemId = $(this).data('invoice_item_id');
        var status = $(this).is(':checked') ? 'delivery' : 'not_delivery';
        $.ajax({
            url: '/dashboard/update-status-itemInvoiceDelivery/'+invoiceItemId,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                invoiceItem_id: invoiceItemId,
                status: status
            },
            success: function(response) {
                var n = new Noty({
                    text: 'Status updated successfully!',
                    killer: true,
                    type: 'success',
                    timeout: 3000
                }).show();

                styleNotyLayout('success');
            },
            error: function() {
                console.log('Error in the request');
            }
        });
    });
</script>

