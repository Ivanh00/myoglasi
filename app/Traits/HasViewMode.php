<?php

namespace App\Traits;

trait HasViewMode
{
    public $viewMode;

    public function mountHasViewMode()
    {
        // Get view mode from session, default to 'list' if not set
        $this->viewMode = session('global_view_mode', 'list');
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        // Store in session for persistence across components
        session(['global_view_mode' => $mode]);
    }
}