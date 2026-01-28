<!-- Core JS -->
<script src="{{ asset('/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('/assets/vendor/js/bootstrap.js') }}"></script> <!-- Bootstrap might still be needed for some JS plugins or legacy modals if any remaining -->
<script src="{{ asset('/assets/vendor/libs/popper/popper.js') }}"></script>



<!-- UI / Feedback -->
<!-- UI / Feedback -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Input Masks & Formatting -->
<script src="{{ asset('assets/vendor/js/jquery.maskMoney.js') }}"></script>
<script src="{{ asset('assets/js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('assets/vendor/js/easy-number-separator.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

<!-- Icons (FontAwesome is already in head, but if feather/ionicons used) -->
<script src="{{ asset('assets/vendor/js/feather.min.js') }}"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<!-- Flatpickr (Datepicker - Good to have globally) -->
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>

<!-- Main Global JS -->
<!-- <script src="{{ asset('/assets/js/main.js') }}"></script> -->

<!-- Global Toastr Logic -->
@if ($message = Session::get('success'))
    <script>
        $(function() {
            toastr.options = { "progressBar": true, "timeOut": 3000 };
            toastr.success("Berhasil", "{{ $message }}");
        });
    </script>
@endif

@if ($message = Session::get('error'))
    <script>
        $(function() {
            toastr.options = { "progressBar": true, "timeOut": 3000 };
            toastr.error("Gagal", "{{ $message }}");
        });
    </script>
@endif

@if ($message = Session::get('warning'))
    <script>
        $(function() {
            toastr.options = { "progressBar": true, "timeOut": 3000 };
            toastr.warning("Warning", "{{ $message }}");
        });
    </script>
@endif

@if ($errors->any())
    <script>
        $(function() {
            toastr.options = { "progressBar": true, "timeOut": 3000 };
            @foreach ($errors->all() as $error)
                toastr.error("Gagal", "{{ $error }}");
            @endforeach
        });
    </script>
@endif

<script>
    // Global Delete Confirm with SweetAlert
    $(function() {
        $(document).on('click', '.delete-confirm', function(event) {
            var form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: `Apakah Anda Yakin?`,
                text: "Data yang dihapus tidak dapat dikembalikan.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#4F46E5", // Indigo 600
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Formatting money class
        $(".money").maskMoney();
    });
</script>

<!-- Stack for page specific scripts -->
@stack('myscript')
