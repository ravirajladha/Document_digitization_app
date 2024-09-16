<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Set;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DocumentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $documents;

    public function __construct(array $documents)
    {
        $this->documents = $documents;
        // dd($documents);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->documents);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID', 'Index Id', 'Document Name', 'Document Type', 'Category', 'Subcategory', 'Number of Pages', 
            'Current State', 'State', 'Alternate State', 'Current District', 'District', 
            'Alternate District', 'Current Village', 'Village', 'Alternate Village', 
            'Current Taluk', 'Taluk', 'Alternate Taluk', 'Locker No', 'Area', 'Dry Land', 
            'Wet Land', 'Unit', 'Set ID', 'Issued Date', 'Court Case No', 'Doc No', 'Survey No', 
            'Doc Identifier ID', 'Latitude', 'Longitude', 'Created At', 'Updated At'
        ];
    }

    /**
     * @param $document
     * @return array
     */
    public function map($document): array
    {
        // Ensure $document is an array and contains necessary keys
        $categoryNames = $this->getCategoryNames($document['category_id'] ?? '');
        $subcategoryNames = $this->getSubcategoryNames($document['subcategory_id'] ?? '');
        $setNames = $this->getSetNames($document['set_id'] ?? '');

        return [
            $document['id'] ?? '',
            $document['temp_id'] ?? '',
            $document['name'] ?? '',
            $document['document_type_name'] ?? '',
            $categoryNames,
            $subcategoryNames,
            $document['number_of_page'] ?? '',
            $document['current_state'] ?? '',
            $document['state'] ?? '',
            $document['alternate_state'] ?? '',
            $document['current_district'] ?? '',
            $document['district'] ?? '',
            $document['alternate_district'] ?? '',
            $document['current_village'] ?? '',
            $document['village'] ?? '',
            $document['alternate_village'] ?? '',
            $document['current_taluk'] ?? '',
            $document['taluk'] ?? '',
            $document['alternate_taluk'] ?? '',
            $document['locker_no'] ?? '',
            $document['area'] ?? '',
            $document['dry_land'] ?? '',
            $document['wet_land'] ?? '',
            $document['unit'] ?? '',
            $setNames,
            $document['issued_date'] ?? '',
            $document['court_case_no'] ?? '',
            $document['doc_no'] ?? '',
            $document['survey_no'] ?? '',
            $document['doc_identifier_id'] ?? '',
            $document['latitude'] ?? '',
            $document['longitude'] ?? '',
            isset($document['created_at']) ? Carbon::parse($document['created_at'])->format('d-M-Y H:i') : '--',
            isset($document['updated_at']) ? Carbon::parse($document['updated_at'])->format('d-M-Y H:i') : '--',
        ];
    }

    /**
     * Get category names by IDs
     *
     * @param string $categoryIds
     * @return string
     */
    protected function getCategoryNames($categoryIds)
    {
        if (!empty($categoryIds) && $categoryIds !== '--') {
            $ids = explode(',', $categoryIds);
            return Category::whereIn('id', $ids)->pluck('name')->implode(', ');
        }
        return '--';
    }

    /**
     * Get subcategory names by IDs
     *
     * @param string $subcategoryIds
     * @return string
     */
    protected function getSubcategoryNames($subcategoryIds)
    {
        if (!empty($subcategoryIds) && $subcategoryIds !== '--') {
            $ids = explode(',', $subcategoryIds);
            return Subcategory::whereIn('id', $ids)->pluck('name')->implode(', ');
        }
        return '--';
    }

    /**
     * Get set names by IDs
     *
     * @param string $setIds
     * @return string
     */
    protected function getSetNames($setIds)
    {
        if (!empty($setIds) && $setIds !== '--') {
            $ids = json_decode($setIds, true);
            if (is_array($ids) && !is_null($ids)) {
                return Set::whereIn('id', $ids)->pluck('name')->implode(', ');
            }
        }
        return '--';
    }
}