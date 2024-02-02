<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Master_doc_type;

class DocumentTypeSelect extends Component
{
    /**
     * Create a new component instance.
     */
    public $documentTypes;

    public function __construct()
    {
        // $this->documentTypes = Master_doc_type::all();
        $this->documentTypes = Master_doc_type::orderBy('name')->get();
      
        // Fetch all document types
    }
  

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.document-type-select');
    }
}
