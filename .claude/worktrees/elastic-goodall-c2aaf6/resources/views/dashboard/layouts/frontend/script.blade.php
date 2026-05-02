<!-- jQuery 2.0.2 -->
{{-- <scripts src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></scripts> --}}
<script src="{{asset('assets/js/jquery-2.0.2.min.js')}}" type="text/javascript"></script>

<!-- jQuery UI 1.10.3 -->
<script src="{{asset('assets/js/jquery-ui-1.10.3.min.js')}}" type="text/javascript"></script>
<!-- Bootstrap -->
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<!-- Morris.js charts -->
{{-- <scripts src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></scripts>
<scripts src="{{asset('assets/js/plugins/morris/morris.min.js')}}" type="text/javascript"></scripts> --}}
<!-- Sparkline -->
<script src="{{asset('assets/js/plugins/sparkline/jquery.sparkline.min.js')}}" type="text/javascript"></script>
<!-- jvectormap -->
<script src="{{asset('assets/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"
        type="text/javascript"></script>
<!-- fullCalendar -->
<script src="{{asset('assets/js/plugins/fullcalendar/fullcalendar.min.js')}}" type="text/javascript"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('assets/js/plugins/jqueryKnob/jquery.knob.js')}}" type="text/javascript"></script>
<!-- daterangepicker -->
<script src="{{asset('assets/js/plugins/daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{asset('assets/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"
        type="text/javascript"></script>
<!-- iCheck -->
<script src="{{asset('assets/js/plugins/iCheck/icheck.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/plugins/bootstrap-select-1.13.14/dist/js/bootstrap-select.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/plugins/bootstrap-select-1.13.14/dist/js/i18n/defaults-*.min.js')}}" type="text/javascript"></script>
{{--<scripts src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></scripts>--}}
{{--<scripts src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></scripts>--}}

<!-- AdminLTE App -->
<script src="{{asset('assets/js/AdminLTE/app.js')}}" type="text/javascript"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('assets/js/AdminLTE/dashboard.js')}}" type="text/javascript"></script>

<!-- NOTY -->
<script src="{{asset('assets/js/noty.min.js')}}" type="text/javascript"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

@yield('scripts')

<script>
    // Delete Confirmation
    /*  $(document).on('click','.delete',function (e) {

          let that = $(this);

          e.preventDefault();
          let n = new Noty({
              text: 'Are you sure you want to delete this?',
              killer: true,
              type: 'warning',
              buttons: [
                  Noty.button('Yes', 'btn btn-success mr-2', function () {
                      that.closest('form').submit();
                  }),

                  Noty.button('No', 'btn btn-primary mr-2', function () {
                      n.close();
                  })
              ]

          }).show();

          let aquiringMessage = document.querySelector('.noty_layout');
          aquiringMessage.classList.add('alert', 'alert-danger');
          aquiringMessage.style.padding = '10px';
          aquiringMessage.querySelector('.btn-primary').style.marginLeft = '10px';
      });*/

    $(document).on('click', '.delete', function (e) {
        e.preventDefault();

        let that = $(this);

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel',
            customClass: {
                confirmButton: 'btn btn-danger ml-2',
                cancelButton: 'btn btn-primary mr-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                that.closest('form').submit();
            }
        });
    });


    // Preview Image
    $(".image").change(function () {

        if (this.files && this.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.imag-preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]); // convert to base64 string
        }
    });


    function styleNotyLayout(type = 'success') {
        let aquiringMessage = document.querySelector('.noty_layout');
        if (!aquiringMessage) return;

        aquiringMessage.classList.remove('alert-success', 'alert-danger', 'alert-warning');

        aquiringMessage.classList.add('alert');

        if (type === 'success') {
            aquiringMessage.classList.add('alert-success');
        } else if (type === 'danger') {
            aquiringMessage.classList.add('alert-danger');
        } else if (type === 'warning') {
            aquiringMessage.classList.add('alert-warning');
        }

        aquiringMessage.style.padding = '10px';
    }
</script>


