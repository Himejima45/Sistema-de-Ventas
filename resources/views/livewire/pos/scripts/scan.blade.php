<script>

try {

    onScan.attachTo(document, {
    suffixKeyCodes: [13],
    onScan: function(barcode) {
        console.log(barcode)
        window.livewire.emit('scan-code', barcode)
    },

    onScanError: function(e){
        console.log(e) // acomodar solo un ejercicio
    }

})

console.log('Scanner ready!')

} catch (e) {
    console.log('Error de lectura: ', e)
}

</script>