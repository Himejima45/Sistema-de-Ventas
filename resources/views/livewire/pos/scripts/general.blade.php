<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('error-modal', msg => {
            ErrorModal(msg)
        });
    });

    function Confirm(id, eventName, text) {
        swal({
            title: 'CONFIRMAR',
            text: text,
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function(result) {
            if (result.value) {
                window.livewire.emit(eventName, id)
                swal.close()
            }
        })
    }

    function NotFound(eventName, text) {
        swal({
            title: 'NO ENCONTRADO',
            text: text,
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function(result) {
            if (result.value) {
                window.livewire.emit(eventName, id)
                swal.close()
            }
        })
    }

    function ErrorModal(text) {
        swal({
            title: 'HUBO UN ERROR',
            text: text,
            type: 'warning',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        })
    }
</script>
