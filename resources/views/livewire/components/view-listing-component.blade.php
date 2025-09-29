@props(['listing', 'viewMode' => 'grid'])

@if($viewMode === 'grid')
    @include('livewire.components.view-listing-grid-component', ['listing' => $listing])
@else
    @include('livewire.components.view-listing-list-component', ['listing' => $listing])
@endif