@props(['service', 'viewMode' => 'grid'])

@if($viewMode === 'grid')
    @include('livewire.components.view-service-grid-component', ['service' => $service])
@else
    @include('livewire.components.view-service-list-component', ['service' => $service])
@endif