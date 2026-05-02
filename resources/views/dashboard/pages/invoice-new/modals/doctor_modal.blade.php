{{-- resources/views/dashboard/pages/invoices/modals/doctor_modal.blade.php --}}

<div id="doctorModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">

            <!-- Modal Header -->
            <div class="modal-header" style="background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%); color: white; border: none; padding: 20px 25px;">
                <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1; font-size: 30px;">&times;</button>
                <h3 style="margin: 0; font-size: 22px; font-weight: 700;">
                    <i class="bi bi-hospital"></i> Select Doctor
                </h3>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" style="padding: 25px;">

                <!-- Search Box -->
                <div style="margin-bottom: 20px;">
                    <input type="text" id="doctor-search" class="form-control"
                           placeholder="🔍 Search doctor by code or name..."
                           style="border: 2px solid #e0e6ed; border-radius: 8px; padding: 12px 15px; font-size: 15px;">
                </div>

                <!-- Doctors Table -->
                <div class="table-responsive">
                    <table class="table table-hover" style="border-radius: 8px; overflow: hidden;">
                        <thead style="background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%); color: white;">
                        <tr>
                            <th style="padding: 15px; border: none; font-weight: 700;">Code</th>
                            <th style="padding: 15px; border: none; font-weight: 700;">Doctor Name</th>
                            <th style="padding: 15px; border: none; text-align: center; font-weight: 700;">Action</th>
                        </tr>
                        </thead>
                        <tbody id="doctors-table-body">
                        @foreach($doctors as $doctor)
                            <tr style="transition: all 0.3s; cursor: pointer;" class="doctor-row"
                                onmouseover="this.style.backgroundColor='#f0fdf4'"
                                onmouseout="this.style.backgroundColor=''">
                                <td style="padding: 15px; border-bottom: 1px solid #f0f2f5;">
                                    <strong style="color: #1abc9c; font-size: 14px;">{{ $doctor->code }}</strong>
                                </td>
                                <td style="padding: 15px; border-bottom: 1px solid #f0f2f5;">
                                    <strong style="font-size: 14px;">{{ $doctor->name }}</strong>
                                </td>
                                <td style="padding: 15px; border-bottom: 1px solid #f0f2f5; text-align: center;">
                                    <button type="button" class="btn btn-sm select-doctor-btn"
                                            data-code="{{ $doctor->code }}"
                                            data-name="{{ $doctor->name }}"
                                            style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
                                                       color: white; border: none; padding: 8px 20px;
                                                       border-radius: 6px; font-weight: 600; transition: all 0.3s;"
                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(39,174,96,0.4)'"
                                            onmouseout="this.style.transform=''; this.style.boxShadow=''">
                                        <i class="bi bi-check-circle"></i> Select
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if($doctors->isEmpty())
                    <div class="alert alert-info text-center" style="margin-top: 20px;">
                        <i class="bi bi-info-circle"></i> No doctors found
                    </div>
                @endif

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer" style="border-top: 2px solid #f0f2f5; padding: 15px 25px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Close
                </button>
            </div>

        </div>
    </div>
</div>


