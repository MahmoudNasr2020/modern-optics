@extends('dashboard.layouts.master')
@section('content')

    <style>
        .search-picks { border: 2px solid #082625; text-transform: uppercase }
    </style>

    <section class="content-header">
        <h1>
            Dashboard
            <small>Item Inquiry</small>
        </h1>
    </section>

    <div class="box box-primary">
        <div class="box-header">
            <!-- tools box -->
            <div class="pull-right box-tools">
                <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip"
                        title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
            </div>

            <i class="fa fa-search"></i>
            <h3 class="box-title">Item Price Inquiry</h3>

        </div><!-- /.box-header -->
        <div class="box-body">
            
            <form action="">
                <div class="alert alert-danger" style="display: none; margin: 10px; width: 63%">
                    <p class=""></p>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Product ID">Product ID</label>
                            <input type="text" id="product_id" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                        <div class="form-group" style="margin-top: 20px">
                            <input type="button" name="search" id="search" class="search form-control btn btn-primary" value="Search">
                        </div>
                    </div>

                </div>
            </form>

            <br><br>
            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Product ID</label>
                        <input type="text" name="product_id" id="" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Product Price</label>
                        <input type="text" name="price" id="" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Tax</label>
                        <input type="text" name="tax" id="" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Total Price</label>
                        <input type="text" name="total_price" id="" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Discount Type</label>
                        <input type="text" name="discount_type" id="" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Discount Value</label>
                        <input type="text" name="discount_value" id="" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Price After Discount</label>
                        <input type="text" name="sale_price" id="" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Retail Price</label>
                        <input type="text" name="retail_price" id="" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Product Description</label>
                        <textarea name="desc" id="desc" cols="30" rows="4" class="form-control"></textarea>
                    </div>
                </div>
            </div>

        </div>

    </div><!-- /.box -->



    <script src="{{ asset('assets/js/jquery-2.0.2.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        
        $(document).ready(function () {
            let searchBTN = document.querySelector('#search');
            searchBTN.addEventListener('click', function(e) {
                let productID = document.querySelector('#product_id').value;

                // Get Fields
                let productIDINp = document.querySelector('[name="product_id"]');
                let price = document.querySelector('[name="price"]');
                let tax = document.querySelector('[name="tax"]');
                let total_price = document.querySelector('[name="total_price"]');
                let sal_price = document.querySelector('[name="sale_price"]');
                let retail_price = document.querySelector('[name="retail_price"]');
                let discount_type = document.querySelector('[name="discount_type"]');
                let discount_value = document.querySelector('[name="discount_value"]');
                let desc = document.querySelector('[name="desc"]');


                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: "POST",
                    url: '{{route("dashboard.search-item-inq")}}',
                    data: {product_id: productID},
                    success: function (response) {
                        if (response.message == 'No Item Found With this ID!') {
                            $('.success-message').css('display', 'none');
                            $('.alert-danger').css('display', 'block');
                            $('.alert-danger p').html('No Item Found With this ID!');
                        } else {
                            $('.alert-danger').css('display', 'none');
                            productIDINp.value = response.product_id;
                            price.value = response.price;
                            tax.value = response.tax;
                            price.value = response.price;
                            total_price.value = response.price;
                            sal_price.value = response.retail_price;
                            retail_price.value = response.retail_price;
                            discount_type.value = response.discount_type;
                            discount_value.value = response.discount_value;
                            desc.innerText = response.description;
                        }

                    },

                });
            });
        });
    

    </script>

@stop
