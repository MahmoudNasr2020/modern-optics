@extends('dashboard.layouts.master')
@section('content')

    <section class="content-header">
        <h1>
            Dashboard
            <small>Eye Test</small> <small></small>
        </h1>
    </section>

        <input type="hidden" name="customerId" id="customerId" value="">
        <input type="hidden" name="doctorId" id="doctorId" value="">
        <div class="box box-primary">
            <div class="box-header">
                <!-- tools box -->
                <i class="fa fa-tasks"></i>
                <h3 class="box-title">Add New Test</h3>
            </div>

            <div class="box-body">
                @include('dashboard.partials._errors')
                <div class="row">
                    <div class="col-md-6">

                        <!-- ---- Visit Section Start ---- -->
                        <div class="box box-warning">

                            <div class="box-body">
                                <div class="row">

                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="visitDate">Date</label>
                                            <input type="date" class="form-control" name="visitDate"
                                                   value="<?php echo $eyeTest->visit_date;?>"
                                                   id="visitDate" disabled>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div><!-- ---- Visit Section Start ---- -->

                        <!-- ---- Doctor Section Start ---- -->
                        <div class="box box-warning">
                            <div class="box-header">
                                <h3 class="box-title">Doctor</h3>
                            </div>

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="doctor_id">ID</label>
                                            <input type="text" class="form-control" disabled name="doctor_id" value="{{ $doctor->code }}"
                                                   id="doctorIdInput">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="doctor_name">Name</label>
                                            <input type="text" class="form-control" name="doctor_name" value="{{ $doctor->name }}"
                                                   id="doctorName" disabled>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div><!-- ---- Doctor Section End ---- -->

                        <!-- ---- Customer Section Start ---- -->
                        <div class="box box-warning">
                            <div class="box-header">
                                <h3 class="box-title">Customer</h3>
                            </div>

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customer_id">ID</label>
                                            <input type="text" class="form-control" disabled name="customer_id"
                                                   value="{{ $customer->customer_id }}" id="customer_ID">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer_name">Name</label>
                                            <input type="text" class="form-control" name="customer_name"
                                                   readonly
                                                   id="customerName" value="{{ $customer->english_name }}">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div><!-- ---- Customer Section End ---- -->

                        <!-- ---- Remarks Section Start ---- -->
                        <div class="box box-warning">
                            <div class="box-header">
                                <h3 class="box-title">Remarks</h3>
                            </div>

                            <div class="box-body">
                                <div class="form-group">
                                    <textarea name="" class="form-control" id="" rows="5" disabled></textarea>
                                </div>
                            </div>
                        </div><!-- ---- Remarks Section Start ---- -->


                    </div>
                    <div class="col-md-6">
                        <!-- CUSTOMER SEARCH SECTION ============================ -->
                        <div class="box box-warning">
                            <div class="box-header">
                                <h3 class="box-title">Measurement</h3>
                            </div>

                            <div class="box-body" style="padding: 5px 80px 11px 80px">
                                <!-- Main Row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Sph Righ Sign</label>
                                                <input type="text" class="form-control" value="{{ $eyeTest->sph_right_sign }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Sph Right Value</label>
                                                <input type="text" class="form-control" value="{{ $eyeTest->sph_right_value }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Sph Left Sign</label>
                                                <input type="text" class="form-control" value="{{ $eyeTest->sph_left_sign }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Sph Left Value</label>
                                                <input type="text" class="form-control" value="{{ $eyeTest->sph_left_value }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Cyl Right Sign</label>
                                                <input type="text" class="form-control" value="{{ $eyeTest->cyl_right_sign }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Cyl Right Value</label>
                                                <input type="text" class="form-control" value="{{ $eyeTest->cyl_right_value }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Cyl Left Sign</label>
                                                <input type="text" class="form-control" value="{{ $eyeTest->cyl_left_sign }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Cyl Left Value</label>
                                                <input type="text" class="form-control" value="{{ $eyeTest->cyl_left_value }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Axis Right</label>
                                                <input type="text" class="form-control" value="{{ $eyeTest->axis_right }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Axis Left</label>
                                                <input type="text" class="form-control" value="{{ $eyeTest->axis_left }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Addition Right</label>
                                                <input type="text" class="form-control" value="{{ $eyeTest->addition_right }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Addition Left</label>
                                                <input type="text" class="form-control" value="{{ $eyeTest->addition_left }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">PD Right</label>
                                                <input type="text" class="form-control" value="{{ $eyeTest->pd_right }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">PD Left</label>
                                                <input type="text" class="form-control" value="{{ $eyeTest->pd_left }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>

                            </div>
                        </div>

                        <!-- ---- Diagnosis Section Start ---- -->
                        <div class="box box-warning">
                            <div class="box-header">
                                <h3 class="box-title">Diagnosis</h3>
                            </div>

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customer_id">Right</label>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" value="{{ ($eyeTest->right_diagnosis != 'null' ? $eyeTest->right_diagnosis : '-') }}" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customer_id">Left</label>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" value="{{ ($eyeTest->left_diagnosis != 'null' ) ? $eyeTest->left_diagnosis : '-' }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div><!-- ---- Diagnosis Section End ---- -->

                        <!-- ---- Glasses Type Section Start ---- -->
                        <div class="box box-warning">
                            <div class="box-header">
                                <h3 class="box-title">Glasses Type</h3>
                            </div>

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" value="{{ $eyeTest->glasses }}" disabled>

                                    </div>
                                </div>
                            </div>
                        </div><!-- ---- Diagnosis Section End ---- -->
                    </div>
                </div>

                <a href="{{ route('dashboard.print-eye-test',$eyeTest->id) }}" target="_blank"
                   class="btn btn-info">
                    <i class="fa fa-print"></i> <strong>Print</strong>
                </a>
            </div>


        </div><!-- /.box -->

    <!-- DOCTOR MODAL -->
    <div id="DoctorModal" class="modal fade" role="dialog">
        <div class="modal-dialog box-item">
            <!-- Modal content-->
            <div class="modal-content">
                {{-- <div class="modal-header box-item-head">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3>Select Doctor</h3>
                </div> --}}

                <div class="modal-body  box-item-content">
                    <div class="row">
                        <!-- Left Panel -->
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <!-- Default panel contents -->
                                <div class="panel-heading">Doctors</div>
                                <div class="panel-body">
                                    {{-- <table class="table table-bordered table-hover">
                                        <thead style="color: white;background: #333;">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                        </tr>
                                        </thead>

                                        @foreach($doctors as $key => $item)
                                            <tbody>
                                            <td>
                                                <button name="doctorId" id="doctorId" style="height: 15px;"
                                                        value="{!! $item->id !!}"></button>
                                                {{$item->code}}
                                            </td>
                                            <td>
                                                {{$item->name}}
                                            </td>
                                            </tbody>
                                        @endforeach
                                    </table> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CUSTOMER MODAL -->
    <div id="customerModal" class="modal fade" role="dialog">
        <div class="modal-dialog box-item">
            <!-- Modal content-->
            <div class="modal-content">
                {{-- <div class="modal-header box-item-head">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3>Select Customer</h3>
                </div>

                <div class="modal-body  box-item-content">
                    <div class="row">
                        <!-- Left Panel -->
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <!-- Default panel contents -->
                                <div class="panel-heading">Customers</div>
                                <div class="panel-body">
                                    <table class="table table-bordered table-hover">
                                        <thead style="color: white;background: #333;">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                        </tr>
                                        </thead>

                                        @foreach($customers as $key => $item)
                                            <tbody>
                                            <td>
                                                <button name="customerId" id="customerId" style="height: 15px;"
                                                        value="{!! $item->customer_id !!}"></button>
                                                {{$item->customer_id}}

                                            </td>
                                            <td>
                                                {{$item->english_name}}
                                            </td>
                                            </tbody>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>


    <script src="{{asset('assets/js/jquery-2.0.2.min.js')}}" type="text/javascript"></script>
    <script>

        // Print Button
        let printBTN = document.querySelector('.print-eyetest');
        printBTN.addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    </script>
@stop
