    </body>

    </html>

    <script src="{{ asset('bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    @session('successNotif')
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: `{{ session('successNotif') }}`,
        })
    </script>
    @endsession
    @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Validation Error!',
            html: `
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            `,
        })
    </script>
    @endif