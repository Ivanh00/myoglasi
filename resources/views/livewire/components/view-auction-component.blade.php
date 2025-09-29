@props(['auction', 'viewMode' => 'grid'])

@if($viewMode === 'grid')
    @include('livewire.components.view-auction-grid-component', ['auction' => $auction])
@else
    @include('livewire.components.view-auction-list-component', ['auction' => $auction])
@endif