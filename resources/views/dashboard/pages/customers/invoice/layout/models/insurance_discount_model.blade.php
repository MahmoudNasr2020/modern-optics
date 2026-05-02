<div class="modal fade" id="insuranceDiscountModal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="discountModalLabel">
                    <i class="fa fa-tag"></i> Insurance Discount Details
                </h4>
            </div>
            <div class="modal-body">

                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr style="background:#232323;color:#fff;">
                        <th>#</th>
                        <th>Insurance Company Name</th>
                        <th>Categories & Discount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($insurances as $index => $insurance)
                        <tr>
                            <td>{{ $loop->index +1  }}</td>
                            <td>{{ $insurance->company_name }}</td>
                            <td>
                                @if($insurance->categories->count() > 0)
                                    @foreach($insurance->categories as $category)
                                        <span style="color:#00acd6;">{{ $category->category_name }}:</span>
                                        <span>{{ floatval($category->pivot->discount_percent) }}%</span><br>
                                    @endforeach
                                @else
                                    <p style="color:red;">Not Assigned</p>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
