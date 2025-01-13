<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}
    </x-filament-panels::form>
    
<script>
    function hideToBeHidden (event) {
        if (event.target.value === 'HADIR') {
            document.querySelector('.to-be-hidden-1').style.display='block'
            document.querySelector('.to-be-hidden-1').parentElement.style.display='block'
            document.querySelector('.to-be-hidden-2').parentElement.parentElement.parentElement.parentElement.style.display='block'
            // document.querySelector('[wire\\:key*="kegiatan"]').style.display = 'block';
        } else {
            document.querySelector('.to-be-hidden-1').parentElement.style.display='none'
            document.querySelector('.to-be-hidden-2').parentElement.parentElement.parentElement.parentElement.style.display='none'
        }
    }
    hideToBeHidden({target:document.querySelector('#status')});
    window.addEventListener('click', (e)=>{
e.stopPropagation();
hideToBeHidden({target:document.querySelector('#status')})
    });
    document.querySelector('#status').addEventListener('change', hideToBeHidden);
    
</script>
</x-filament-panels::page>
