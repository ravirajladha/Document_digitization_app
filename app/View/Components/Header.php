<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Master_doc_type;
use App\Models\Notification;

class Header extends Component
{
    /**
     * The document types.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $doc_types;
    public $notifications;
    public $notificationsCount;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->doc_types = \App\Models\Master_doc_type::all();
        $this->doc_types = Master_doc_type::all();
        $this->notifications = Notification::latest()->take(5)->get();
        $this->notificationsCount = Notification::where('is_read', 0)->count();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render(): View|Closure|string
    {
        return view('components.header');
    }
}
