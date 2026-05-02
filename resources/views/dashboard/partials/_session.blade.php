
@if(session('success'))

    <script src="{{asset('assets/js/noty.min.js')}}" type="text/javascript"></script>
    <script>

        new Noty({
            type: 'success',
            layout: 'topRight',
            text: '{{session("success")}}',
            timeout: 3800,
            killer: true
        }).show();

        document.querySelector('.noty_bar').classList.add('alert');
        document.querySelector('.noty_bar').classList.add('alert-success');

    </script>

@endif

@if(session('error'))
    <script src="{{asset('assets/js/noty.min.js')}}" type="text/javascript"></script>

    <script>

        new Noty({
            type: 'error',
            layout: 'topRight',
            text: '{{ session("error") }}',
            timeout: 4000,
            killer: true
        }).show();

        document.querySelector('.noty_bar').classList.add('alert');
        document.querySelector('.noty_bar').classList.add('alert-danger');

    </script>

@endif
