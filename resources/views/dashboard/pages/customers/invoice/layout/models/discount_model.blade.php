<!-- ==================== DISCOUNT MODAL ==================== -->
<div class="modal fade" id="DiscountModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; border: none; padding: 20px 25px;">
                <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1; font-size: 30px;">&times;</button>
                <h3 style="margin: 0; font-size: 22px; font-weight: 700;">
                    <i class="bi bi-tag-fill"></i> Discount Details
                </h3>
            </div>
            <div class="modal-body" style="padding: 25px;">
                <!-- Insurance Companies -->
                <h4 style="margin-bottom: 15px; color: #27ae60; font-weight: 700; padding-bottom: 10px; border-bottom: 2px solid #e0e6ed;">
                    <i class="bi bi-shield-check"></i> Insurance Companies
                </h4>
                <div class="table-responsive">
                    <table class="table table-hover" style="border-radius: 8px; overflow: hidden;">
                        <thead style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white;">
                        <tr>
                            <th style="padding: 15px; border: none; width: 5%;">#</th>
                            <th style="padding: 15px; border: none; width: 35%;">Company Name</th>
                            <th style="padding: 15px; border: none; width: 60%;">Categories & Discount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($insurances as $index => $insurance)
                            <tr style="transition: all 0.3s;">
                                <td style="padding: 15px; border-bottom: 1px solid #f0f2f5;">
                                    <strong style="color: #27ae60;">{{ $loop->index + 1 }}</strong>
                                </td>
                                <td style="padding: 15px; border-bottom: 1px solid #f0f2f5;">
                                    <strong>{{ $insurance->company_name }}</strong>
                                </td>
                                <td style="padding: 15px; border-bottom: 1px solid #f0f2f5;">
                                    @if($insurance->categories->count() > 0)
                                        @foreach($insurance->categories as $category)
                                            <span style="display: inline-block; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                                                             color: white; padding: 4px 10px; border-radius: 12px;
                                                             margin: 2px; font-size: 12px; font-weight: 600;">
                                                    {{ $category->category_name }}: {{ floatval($category->pivot->discount_percent) }}%
                                                </span>
                                        @endforeach
                                    @else
                                        <span style="color: #e74c3c; font-weight: 600;">
                                                <i class="bi bi-x-circle"></i> Not Assigned
                                            </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Cardholders -->
                <h4 style="margin-top: 30px; margin-bottom: 15px; color: #3498db; font-weight: 700; padding-bottom: 10px; border-bottom: 2px solid #e0e6ed;">
                    <i class="bi bi-credit-card-2-front"></i> Cardholder Details
                </h4>
                <div class="table-responsive">
                    <table class="table table-hover" style="border-radius: 8px; overflow: hidden;">
                        <thead style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white;">
                        <tr>
                            <th style="padding: 15px; border: none; width: 5%;">#</th>
                            <th style="padding: 15px; border: none; width: 35%;">Cardholder Name</th>
                            <th style="padding: 15px; border: none; width: 60%;">Categories & Discount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cardholders as $index => $cardholder)
                            <tr style="transition: all 0.3s;">
                                <td style="padding: 15px; border-bottom: 1px solid #f0f2f5;">
                                    <strong style="color: #3498db;">{{ $loop->index + 1 }}</strong>
                                </td>
                                <td style="padding: 15px; border-bottom: 1px solid #f0f2f5;">
                                    <strong>{{ $cardholder->cardholder_name }}</strong>
                                </td>
                                <td style="padding: 15px; border-bottom: 1px solid #f0f2f5;">
                                    @if($cardholder->categories->count() > 0)
                                        @foreach($cardholder->categories as $category)
                                            <span style="display: inline-block; background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
                                                             color: white; padding: 4px 10px; border-radius: 12px;
                                                             margin: 2px; font-size: 12px; font-weight: 600;">
                                                    {{ $category->category_name }}: {{ floatval($category->pivot->discount_percent) }}%
                                                </span>
                                        @endforeach
                                    @else
                                        <span style="color: #e74c3c; font-weight: 600;">
                                                <i class="bi bi-x-circle"></i> Not Assigned
                                            </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #f0f2f5; padding: 15px 25px;">
                <button type="button" class="btn" data-dismiss="modal"
                        style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
                               color: white; border: none; padding: 10px 25px;
                               border-radius: 8px; font-weight: 600;">
                    <i class="bi bi-x-lg"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ==================== PAYER MODAL ==================== -->
<div id="payerModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 20px 25px;">
                <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1; font-size: 30px;">&times;</button>
                <h3 style="margin: 0; font-size: 22px; font-weight: 700;">
                    <i class="bi bi-cash-stack"></i> Payer Details
                </h3>
            </div>

            <div class="modal-body" style="padding: 25px;">
                <!-- Insert Table Here -->
                <div id="discountTable"></div>
            </div>

            <div class="modal-footer" style="border-top: 2px solid #f0f2f5; padding: 15px 25px;">
                <button type="button" class="btn" data-dismiss="modal"
                        style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
                               color: white; border: none; padding: 10px 25px;
                               border-radius: 8px; font-weight: 600;">
                    <i class="bi bi-x-lg"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
        transform: translateX(3px);
    }
</style>
