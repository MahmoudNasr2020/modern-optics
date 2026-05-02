<div id="DoctorModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #16a085 0%, #138d75 100%); color: white; border: none; padding: 20px 25px;">
                <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1; font-size: 30px;">&times;</button>
                <h3 style="margin: 0; font-size: 22px; font-weight: 700;">
                    <i class="bi bi-hospital"></i> Select Doctor
                </h3>
            </div>

            <div class="modal-body" style="padding: 25px;">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Search Box -->
                        <div style="margin-bottom: 20px;">
                            <input type="text" id="doctor-search" class="form-control" placeholder="Search doctor by name or code..."
                                   style="border: 2px solid #e0e6ed; border-radius: 8px; padding: 12px 15px;">
                        </div>

                        <!-- Doctors Table -->
                        <div class="table-responsive">
                            <table class="table table-hover" style="border-radius: 8px; overflow: hidden;">
                                <thead style="background: linear-gradient(135deg, #16a085 0%, #138d75 100%); color: white;">
                                <tr>
                                    <th style="padding: 15px; border: none; font-weight: 700; text-transform: uppercase; font-size: 12px;">Code</th>
                                    <th style="padding: 15px; border: none; font-weight: 700; text-transform: uppercase; font-size: 12px;">Doctor Name</th>
                                    <th style="padding: 15px; border: none; font-weight: 700; text-transform: uppercase; font-size: 12px; text-align: center;">Action</th>
                                </tr>
                                </thead>
                                <tbody id="doctors-table-body">
                                @foreach($doctors as $key => $item)
                                    <tr style="transition: all 0.3s; cursor: pointer;" class="doctor-row">
                                        <td style="padding: 15px; border-bottom: 1px solid #f0f2f5;">
                                            <strong style="color: #16a085;">{{$item->code}}</strong>
                                        </td>
                                        <td style="padding: 15px; border-bottom: 1px solid #f0f2f5;">
                                            <strong>{{$item->name}}</strong>
                                        </td>
                                        <td style="padding: 15px; border-bottom: 1px solid #f0f2f5; text-align: center;">
                                            <button name="doctorId" id="doctorId"
                                                    class="btn btn-sm select-doctor-btn"
                                                    value="{!! $item->id !!}"
                                                    data-code="{{$item->code}}"
                                                    data-name="{{$item->name}}"
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

                        @if(count($doctors) == 0)
                            <div style="text-align: center; padding: 40px 20px;">
                                <i class="bi bi-hospital" style="font-size: 60px; color: #ddd;"></i>
                                <h4 style="color: #999; margin-top: 15px;">No doctors found</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .doctor-row:hover {
        background: linear-gradient(135deg, #e8f8f5 0%, #fff 100%);
        transform: translateX(3px);
    }

    .select-doctor-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
    }
    #doctorId{
        width: auto !important;
        height: auto !important;
    }
</style>

<script>
    $(document).ready(function() {
        // Search functionality
        $('#doctor-search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#doctors-table-body tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });


    });
</script>
