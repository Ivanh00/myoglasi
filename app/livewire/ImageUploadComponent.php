<?php

namespace App\Livewire;

use Livewire\Component;

class ImageUploadComponent extends Component
{
    public function render()
    {
        return view('livewire.image-upload');
    }

    public $images = [];
    public $maxImages = 10;
    
    protected $rules = [
        'images.*' => 'image|max:2048'
    ];
    
    public function updatedImages()
    {
        $this->validate();
        
        if (count($this->images) > $this->maxImages) {
            session()->flash('error', "Maksimalno {$this->maxImages} slika.");
            $this->images = array_slice($this->images, 0, $this->maxImages);
        }
    }
    
    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }
}
