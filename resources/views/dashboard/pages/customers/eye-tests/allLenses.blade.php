@extends('dashboard.layouts.master')
@section('content')

    <style>
        .form-group {
            margin-right: 15px
        }

        .loader {
            border: 5px solid #f14e79;
            border-top: 5px solid #5cb85c;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            animation: spin 2s linear infinite;
            margin: auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="box box-success">
        <div class="box-header">
            <h3 class="box-title">Lenses Wizard (Customer: ID {{ $customer->customer_id }})</h3>
            <div class="pull-right box-tools">
                <a href="{{ route('dashboard.add-eye-test', ['id' => $customer->id]) }}"
                   class="btn btn-primary btn-sm pull-right"
                   style="margin-right: 5px; color: #fff; font-size: 14px"><i class="fa fa-eye" style="margin-right: 5px"></i>Add New Test</a>
            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            <!-- MultiStep Form -->
            <div class="row">
                <div class="col-md-12">
                    <form id="msform" class="lensForm" method="get"
                          action="{{route('dashboard.store-many-data-in-session')}}">
                        {{csrf_field()}}
                        <!-- progressbar -->
                        <ul id="progressbar">
                            <li class="active">Eye Test</li>
                            <li>Lenses</li>
                        </ul>
                        <!-- fieldsets -->

                        <!-- Choose Customer Lense -->
                        <fieldset>
                            <!-- Lenses Table -->
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th colspan="4" class="text-center">Details</th>
                                    <th colspan="5" class="text-center">Right</th>
                                    <th colspan="5" class="text-center">Left</th>
                                </tr>
                                <tr class="mid_th">
                                    <td>View</td>
                                    <td>Doctor</td>
                                    <td>Date</td>
                                    <td>Source</td>
                                    <td>Sign</td>
                                    <td>Sph.</td>
                                    <td>Cyl.</td>
                                    <td>Axis</td>
                                    <td>Add</td>
                                    <td>Sign</td>
                                    <td>Sph.</td>
                                    <td>Cyl.</td>
                                    <td>Axis</td>
                                    <td>Add</td>
                                    <td>Show Test</td>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($lenses))
                                    @foreach($lenses as $lens)
                                            <?php $doctor = App\Doctor::where('code', $lens->doctor_id)->first() ? App\Doctor::where('code', $lens->doctor_id)->first()->name : '-'; ?>
                                        <tr>
                                            <td><input class="form-control" type="radio" name="lens" id="lensId"
                                                       value="{{$lens->id}}"></td>
                                            <td>{{$doctor}}</td>
                                            <td>{{$lens->visit_date}}</td>
                                            <td>E</td>
                                            <td>{{$lens->sph_right_sign.' '. $lens->cyl_right_sign}}</td>
                                            <td>{{$lens->sph_right_value??'-'}}</td>
                                            <td>{{$lens->cyl_right_value??'-'}}</td>
                                            <td>{{$lens->axis_right??'-'}}</td>
                                            <td>{{$lens->addition_right??'-'}}</td>
                                            <td>{{$lens->sph_left_sign.' '. $lens->cyl_left_sign}}</td>
                                            <td>{{$lens->sph_left_value??'-'}}</td>
                                            <td>{{$lens->cyl_left_value??'-'}}</td>
                                            <td>{{$lens->axis_left??'-'}}</td>
                                            <td>{{$lens->addition_left??'-'}}</td>
                                            <td><a href="{{ route('dashboard.get-customer-eye', ['id' => $lens->id]) }}"
                                                   target="_blank" class="btn btn-success btn-flat">Show <i
                                                        class="fa fa-file"></i></a></td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>

                            </table>
                            <input type="button" name="next" class="next action-button" value="Next"/>

                        </fieldset>

                        <!-- Questions Fieldset -->
                        <fieldset>
                            <h2 class="fs-title">Select Lenses</h2>
                            <br><br>
                            <div class="row">

                                <div class="col-md-12" style="display: flex; flex-direction: row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="type">Frame Type</label>
                                            <select name="frame_type" class="form-control" id="">
                                                <option value=""></option>
                                                <option value="HBC Frame">HBC Frame</option>
                                                <option value="Full Frame">Full Frame</option>
                                                <option value="Nilor">Nilor</option>
                                                <option value="Rimless">Rimless</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="lense_type">Lense Type</label>
                                            <select name="lense_type" class="form-control" id="">
                                                <option value=""></option>
                                                <option value="All Distance Lense">All Distance Lense</option>
                                                <option value="Biofocal">Biofocal</option>
                                                <option value="Single Vision">Single Vision</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="life_style">Life Style</label>
                                            <select name="life_style" class="form-control" id="">
                                                <option value=""></option>
                                                <option value="Normal">Normal</option>
                                                <option value="Active">Active</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customer_activity">Customer Activity</label>
                                            <select name="customer_activity" class="form-control" id="">
                                                <option value=""></option>
                                                <option value="Clear / Tintable">Clear / Tintable</option>
                                                <option value="Transition">Transition</option>
                                                <option value="Glare Free">Glare Free</option>
                                                <option value="POLARIZED">POLARIZED</option>
                                                <option value="TINTED">TINTED</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-md-12" style="display: flex; flex-direction: row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="lense_tech">Lense Technology</label>
                                            <select name="lense_tech" class="form-control" id="">
                                                <option value=""></option>
                                                <option value="HD / Digital Lense">HD / Digital Lense</option>
                                                <option value="Basic">Basic</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="glasses_type">Description</label>
                                            <input type="text" name="description" class="form-control description"
                                                   id="">
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="type">Lense Production</label>
                                            <select name="production" id="production" class="form-control" id="">
                                                <option value=""></option>
                                                <option value="Stock">Stock</option>
                                                <option value="RX">RX</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="type">Brand</label>
                                            <select name="brand" id="brand" class="form-control" id="">
                                                <option value=""></option>
                                                <option value="Essilor">Essilor</option>
                                                <option value="KODAC">KODAC</option>
                                                <option value="TECHLINE">TECHLINE</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row" style="display: flex; flex-direction: row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Index</label>
                                            <select name="index" class="form-control" id="index">
                                                <option value=""></option>
                                                <option value="1.5">1.5</option>
                                                <option value="1.53">1.53</option>
                                                <option value="1.56">1.56</option>
                                                <option value="1.59">1.59</option>
                                                <option value="1.6">1.6</option>
                                                <option value="1.61">1.61</option>
                                                <option value="1.67">1.67</option>
                                                <option value="1.74">1.74</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered lenses-table">
                                        <thead class="text-center">
                                        <th>Code</th>
                                        <th>Index</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Left</th>
                                        <th>Right</th>
                                        </thead>

                                        <tbody>
                                        @foreach ($glass_lenses as $lense)
                                            <tr>
                                                    <span>
                                                        <td>{{ $lense->product_id }}</td>
                                                        <td>{{ $lense->index }}</td>
                                                        <td>{{ $lense->description }}</td>
                                                        <td>{{ $lense->retail_price }}</td>
                                                    </span>
                                                <td><i class="fa fa-plus leftGlass" id="leftGlass"
                                                       style="cursor: pointer"></i>
                                                </td>
                                                <td><i class="fa fa-plus rightGlass" id="rightGlass"
                                                       style="cursor: pointer"></i>
                                                </td>
                                            </tr>
                                            <input type="hidden" id="lensStock" name="lensStock"
                                                   value="{{$lense->amount}}">

                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <br><br>

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="box box-warning">
                                        <div class="box-header">
                                            <h3 class="box-title">Summary</h3>
                                        </div>

                                        <div class="box-body">
                                            <table class="table summary-table" style="font-size: 16px">
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="loader text-center" style="display: none"></div>
                            <input type="button" name="previous" class="previous action-button-previous"
                                   value="Previous"/>
                            <button type="submit" class="btn action-button" id="submitLens">submit</button>
                        </fieldset>
                    </form>
                </div>
            </div>
            <!-- /.MultiStep Form -->
            <br>
        </div><!-- /.box-body -->

    </div>

    <script src="{{asset('assets/js/jquery-2.0.2.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/multi-step-form.js') }}" type="text/javascript"></script>

    <script type="text/javascript">

        let glassesLensesTable = document.querySelector('.lenses-table tbody');
        let leftglassess = Array.from(document.querySelectorAll('#leftGlass'));
        let rightlassess = Array.from(document.querySelectorAll('#rightGlass'));
        let lensStock = document.getElementById('lensStock').value;

        let summartyTabl = document.querySelector('.summary-table tbody');
        let row = document.createElement('tr');

        // Select all LEFT Rows
        glassesLensesTable.addEventListener('click', function (e) {
            let direction;

            if (((e.target.tagName) == 'I') && (e.target.classList[2] == 'leftGlass')) {
                direction = 'L';

                let code = e.target.parentElement.parentElement.querySelector('td:first-child').innerText;
                let name = e.target.parentElement.parentElement.querySelector('td:nth-child(3)').innerText;
                let price = e.target.parentElement.parentElement.querySelector('td:nth-child(4)').innerText;

                row.innerHTML = `
                    <td>${direction}</td>
                    <td>${code}</td>
                    <td>${name}</td>
                    <td>${price}</td>
                    <td><i class="fa fa-trash-o remove-glass" style="cursor: pointer; color: #ee0979"></i></td>
                `;

                summartyTabl.innerHTML += row.innerHTML;

            } else if (((e.target.tagName) == 'I') && (e.target.classList[2] == 'rightGlass')) {
                direction = 'R';

                let code = e.target.parentElement.parentElement.querySelector('td:first-child').innerText;
                let name = e.target.parentElement.parentElement.querySelector('td:nth-child(3)').innerText;
                let price = e.target.parentElement.parentElement.querySelector('td:nth-child(4)').innerText;

                row.innerHTML = `
                    <td>${direction}</td>
                    <td>${code}</td>
                    <td>${name}</td>
                    <td>${price}</td>
                    <td><i class="fa fa-trash-o remove-glass" style="cursor: pointer; color: #ee0979"></i></td>
                `;

                summartyTabl.innerHTML += row.innerHTML;

            } else {
                return false;
            }


        });


        // Delete Rows From Summary Table
        summartyTabl.addEventListener('click', function (e) {

            if (e.target.tagName == 'I') {
                let row = e.target.parentElement.parentElement;
                console.log('SR: ', row);
                summartyTabl.removeChild(row);
            }

        });


        // Filter By DESCRIPTION And INDEX
        let description = document.querySelector('.description');
        let summaryRows = Array.from(summartyTabl.querySelectorAll('tr'));

        description.addEventListener('keyup', function (e) {
            updateLensesTable(e.target.value);
        });

        function updateLensesTable(searchValue) {
            let lensesTable = Array.from(document.querySelectorAll('.lenses-table tbody tr'));

            matchedRows = lensesTable.filter(row => {
                return row.innerText.match(searchValue);
            })

            lensesTable.forEach(r => {
                if (matchedRows.includes(r)) {
                    r.style.display = 'table-row';
                    console.log('RINX: ', r.innerText);
                } else {
                    r.style.display = 'none';
                    console.log(r);
                }
            });

        }

        // Filter By Other Values
        let frame_type = document.querySelector('[name=frame_type]');
        let lense_type = document.querySelector('[name=lense_type]');
        let life_style = document.querySelector('[name=life_style]');
        let customer_activity = document.querySelector('[name=customer_activity]');
        let lense_tech = document.querySelector('[name=lense_tech]');
        let glasses_brand = document.querySelector('[name=brand]');
        let glasses_production = document.querySelector('[name=production]');
        let index = document.querySelector('#index');

        // On Frmae Type Change
        frame_type.addEventListener('change', function (e) {
            filterLenses();
        });

        // On Lense Type Change
        lense_type.addEventListener('change', function (e) {
            filterLenses();
        });

        // On Life Style Change
        life_style.addEventListener('change', function (e) {
            filterLenses();
        });

        // On Customer Activity Change
        customer_activity.addEventListener('change', function (e) {
            filterLenses();
        });

        // On Lense Technology Change
        lense_tech.addEventListener('change', function (e) {
            filterLenses();
        });

        // On Glasses Brand Change
        glasses_brand.addEventListener('change', function (e) {
            filterLenses();
        });

        // On Glasses Productin Change
        glasses_production.addEventListener('change', function (e) {
            filterLenses();
        });

        // On INDEX Change
        index.addEventListener('change', function (e) {
            filterLenses();
        });

        // Filterization Function
        function filterLenses() {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "POST",
                url: '{{route("dashboard.fliter-lenses")}}',
                data: {
                    frame_type: frame_type.value,
                    index: index.value,
                    lense_type: lense_type.value,
                    life_style: life_style.value,
                    customer_activity: customer_activity.value,
                    lense_tech: lense_tech.value,
                    glasses_brand: glasses_brand.value,
                    glasses_production: +glasses_production.value,
                },
                success: function (response) {
                    console.log('Responsa: ', response);
                    let row = document.createElement('tr');
                    glassesLensesTable.innerHTML = '';
                    // debugger;
                    response.forEach(r => {
                        row.innerHTML = `
                            <td>${r.product_id}</td>
                            <td>${r.index}</td>
                            <td>${r.description}</td>
                            <td>${r.retail_price}</td>
                            <td><i class="fa fa-plus leftGlass" id="leftGlass" style="cursor: pointer"></i></td>
                            <td><i class="fa fa-plus rightGlass" id="rightGlass" style="cursor: pointer"></i></td>
                        `;
                        console.log(row);
                        glassesLensesTable.innerHTML += row.innerHTML;
                        // lensesTable.append(row);
                    });
                },
                error: function (error) {
                    console.log('Error: ', error);
                }
            });

        }

        let lensForm = document.querySelector('.lensForm');
        let lensAction = lensForm.getAttribute('action');
        // let lens = document.querySelector('#submitLens');

        // Add Lenses To The Invoice
        lensForm.addEventListener('submit', function (e) {
            e.preventDefault();

            document.querySelector('#submitLens').setAttribute('disabled', 'true');
            document.querySelector('.loader').style.display = 'block';

            let summaryRows = Array.from(summartyTabl.querySelectorAll('tr'));
            console.log(summaryRows);
            summaryRows.forEach(row => {
                console.log(row);
                let Product = {
                    id: 1,
                    product_id: row.querySelector('td:nth-child(2)').innerText,
                    description: row.querySelector('td:nth-child(3)').innerText,
                    product_quantity: 1,
                    price: row.querySelector('td:nth-child(4)').innerText,
                    net: row.querySelector('td:nth-child(4)').innerText,
                    tax: 0,
                    total: row.querySelector('td:nth-child(4)').innerText,
                    stock: lensStock,
                    branch_name: row.querySelector('td:nth-child(1)').innerText,
                    type: 'lens'
                }
                console.log(Product);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: "GET",
                    url: lensAction,
                    data: {Product},
                    success: function (response) {
                        console.log(response);
                    }
                });

            });
            var Id = window.localStorage.getItem("Id");

            setTimeout(function () {
                window.location.href = '{{url('dashboard/show-customer-invoice').'/'}}' + Id;
            }, 2000);

        });

    </script>
@stop
