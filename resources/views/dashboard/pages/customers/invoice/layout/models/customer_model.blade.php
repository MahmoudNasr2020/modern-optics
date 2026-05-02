<div id="customerModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 20px 25px;">
                <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1; font-size: 30px;">&times;</button>
                <h3 style="margin: 0; font-size: 22px; font-weight: 700;">
                    <i class="bi bi-people-fill"></i> Select Customer
                </h3>
            </div>

            <div class="modal-body" style="padding: 25px;">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Search Box -->
                        <div style="margin-bottom: 20px;">
                            <input type="text" id="customer-search" class="form-control" placeholder="Search customer by ID or name..."
                                   style="border: 2px solid #e0e6ed; border-radius: 8px; padding: 12px 15px;">
                        </div>

                        <!-- Customers Table -->
                        <div class="table-responsive">
                            <table class="table table-hover" style="border-radius: 8px; overflow: hidden;">
                                <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                <tr>
                                    <th style="padding: 15px; border: none; font-weight: 700; text-transform: uppercase; font-size: 12px;">Customer ID</th>
                                    <th style="padding: 15px; border: none; font-weight: 700; text-transform: uppercase; font-size: 12px;">Customer Name</th>
                                    <th style="padding: 15px; border: none; font-weight: 700; text-transform: uppercase; font-size: 12px; text-align: center;">Action</th>
                                </tr>
                                </thead>
                                <tbody id="customers-table-body">
                                @foreach($customers as $key => $item)
                                    <tr style="transition: all 0.3s; cursor: pointer;" class="customer-row">
                                        <td style="padding: 15px; border-bottom: 1px solid #f0f2f5;">
                                            <strong style="color: #667eea;">{{$item->customer_id}}</strong>
                                        </td>
                                        <td style="padding: 15px; border-bottom: 1px solid #f0f2f5;">
                                            <strong>{{$item->english_name}}</strong>
                                        </td>
                                        <td style="padding: 15px; border-bottom: 1px solid #f0f2f5; text-align: center;">
                                            <button name="customerId" id="customerId"
                                                    class="btn btn-sm select-customer-btn"
                                                    value="{!! $item->customer_id !!}"
                                                    data-name="{{$item->english_name}}"
                                                    style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
                                                               color: white; border: none; padding: 8px 20px;
                                                               border-radius: 6px; font-weight: 600; transition: all 0.3s;">
                                                <i class="bi bi-check-circle"></i> Select
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(count($customers) == 0)
                            <div style="text-align: center; padding: 40px 20px;">
                                <i class="bi bi-people-fill" style="font-size: 60px; color: #ddd;"></i>
                                <h4 style="color: #999; margin-top: 15px;">No customers found</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .customer-row:hover {
        background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
        transform: translateX(3px);
    }

    .select-customer-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
    }
</style>

<script>
    $(document).ready(function() {
        // Search functionality
        $('#customer-search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#customers-table-body tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Select customer
        $('.select-customer-btn').on('click', function(e) {
            e.preventDefault();
            var customerId = $(this).val();
            var customerName = $(this).data('name');

            $('#customer_id').val(customerId);
            $('#customer_name').val(customerName);
            $('input[name="customer_id"]').val(customerId);

            $('#customerModal').modal('hide');
        });
    });
</script>
