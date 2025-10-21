<script src="{{ asset('assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        App.init();
    });
</script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

<script src="{{ asset('plugins/sweetalerts/sweetalert2.min.js') }}"></script>
<script src="{{ asset('plugins/notification/snackbar/snackbar.min.js') }}"></script>
<script src="{{ asset('plugins/nicescroll/nicescroll.js') }}"></script>
<script src="{{ asset('plugins/currency/currency.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/dash_2.js') }}"></script>
<script src="https://kit.fontawesome.com/3add1bb5eb.js" crossorigin="anonymous"></script>
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->


<script>
    function noty (msg, option = 1) {
        Snackbar.show({
            text: msg.toUpperCase(),
            actionText: 'CERRAR',
            actionTextColor: '#fff',
            backgroundColor: option == 1 ? '#3bf5c' : '#e7515a',
            pos: 'top-right'
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.livewire.on('record-created', msg => {
            Success('Registrado', msg)
            $('#theModal').modal('hide')
        });
        window.livewire.on('record-updated', msg => {
            Success('Actualizado', msg)
            $('#theModal').modal('hide')
        });
        window.livewire.on('record-deleted', msg => {
            Deleted('Eliminado', msg)
            $('#theModal').modal('hide')
        });
        window.livewire.on('record-warning', msg => {
            Warning('Cuidado', msg)
        });
        window.livewire.on('record-info', msg => {
            Info('Información', msg)
        });
    });

    function Confirm (id, itemsLength = 0, itemsMessage = '') {
        if (itemsLength > 0)
        {
            swal(itemsMessage)
            return;
        }

        swal({
            title: 'CONFIRMAR',
            text: "¿ESTÁ SEGURO QUE DESEA BORRAR EL REGISTRO?",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function (result) {
            if (result.value)
            {
                window.livewire.emit('Destroy', id)
                swal.close()
                Deleted('Eliminado', 'Se ha eliminado el registro')
            }
        })
    }

    function Deleted (title, message) {
        swal({
            type: "warning",
            title,
            text: message,
            showConfirmButton: false
        })
    }

    function Success (title, message) {
        swal({
            type: "success",
            title,
            text: message,
            showConfirmButton: false,
        })
    }

    function Info (title, message) {
        swal({
            type: "info",
            title,
            text: message,
            showConfirmButton: false,
        })
    }

    function Warning (title, message) {
        swal({
            type: "warning",
            title,
            text: message,
            showConfirmButton: false,
        })
    }
</script>

<script>
    (function () {
        // Unique ID for this component instance
        const bell = document.getElementById('notification-bell');
        const dropdown = document.getElementById('notification-dropdown');

        if (!bell || !dropdown) return;

        // Toggle dropdown on bell click
        bell.addEventListener('click', function (e) {
            e.stopPropagation();
            const isVisible = dropdown.style.display === 'block';
            dropdown.style.display = isVisible ? 'none' : 'block';
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!bell.contains(e.target) && !dropdown.contains(e.target))
            {
                dropdown.style.display = 'none';
            }
        });

        // Optional: Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape')
            {
                dropdown.style.display = 'none';
            }
        });
    })();
</script>



@livewireScripts