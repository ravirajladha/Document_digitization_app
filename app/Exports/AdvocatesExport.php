<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdvocatesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = DB::table('advocate_documents')
            ->join('advocates', 'advocate_documents.advocate_id', '=', 'advocates.id')
            ->join('master_doc_datas', 'advocate_documents.doc_id', '=', 'master_doc_datas.id');

        if (!empty($this->filters['advocate_id'])) {
            $query->where('advocate_documents.advocate_id', $this->filters['advocate_id']);
        }

        if (!empty($this->filters['doc_id'])) {
            $query->where('master_doc_datas.id', $this->filters['doc_id']);
        }

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('advocate_documents.created_at', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('advocate_documents.created_at', '<=', $this->filters['end_date']);
        }

        return $query->get([
            'advocate_documents.id as assignment_id',
            'advocates.name as advocate_name',
            'advocate_documents.created_at as created_at',
            'master_doc_datas.name as document_name',
            'master_doc_datas.category_id as category_id',
            'master_doc_datas.subcategory_id as subcategory_id',
            'master_doc_datas.location as location',
            'master_doc_datas.locker_id as locker_id',
            'master_doc_datas.category as category',
            'master_doc_datas.document_type_name as document_type_name',
            'master_doc_datas.current_state as current_state',
            'master_doc_datas.state as state',
            'master_doc_datas.alternate_state as alternate_state',
            'master_doc_datas.current_district as current_district',
            'master_doc_datas.district as district',
            'master_doc_datas.alternate_district as alternate_district',
            'master_doc_datas.current_taluk as current_taluk',
            'master_doc_datas.taluk as taluk',
            'master_doc_datas.alternate_taluk as alternate_taluk',
            'master_doc_datas.current_village as current_village',
            'master_doc_datas.village as village',
            'master_doc_datas.alternate_village as alternate_village',
            'master_doc_datas.issued_date as issued_date',
            'master_doc_datas.area as area',
            'master_doc_datas.dry_land as dry_land',
            'master_doc_datas.wet_land as wet_land',
            'master_doc_datas.unit as unit',
            'master_doc_datas.old_locker_number as old_locker_number',
            'master_doc_datas.latitude as latitude',
            'master_doc_datas.longitude as longitude',
            'master_doc_datas.court_case_no as court_case_no',
            'master_doc_datas.survey_no as survey_no',
        ]);
    }

    public function headings(): array
    {
        return [
            'Assignment ID',
            'Advocate Name',
            'Created At',
            'Document Name',
            'Category ID',
            'Subcategory ID',
            'Location',
            'Locker ID',
            'Category',
            'Document Type Name',
            'Current State',
            'State',
            'Alternate State',
            'Current District',
            'District',
            'Alternate District',
            'Current Taluk',
            'Taluk',
            'Alternate Taluk',
            'Current Village',
            'Village',
            'Alternate Village',
            'Issued Date',
            'Area',
            'Dry Land',
            'Wet Land',
            'Unit',
            'Old Locker Number',
            'Latitude',
            'Longitude',
            'Court Case No',
            'Survey No',
        ];
    }

    public function map($document): array
    {
        return [
            $document->assignment_id,
            $document->advocate_name,
            $document->created_at,
            $document->document_name,
            $document->category_id,
            $document->subcategory_id,
            $document->location,
            $document->locker_id,
            $document->category,
            $document->document_type_name,
            $document->current_state,
            $document->state,
            $document->alternate_state,
            $document->current_district,
            $document->district,
            $document->alternate_district,
            $document->current_taluk,
            $document->taluk,
            $document->alternate_taluk,
            $document->current_village,
            $document->village,
            $document->alternate_village,
            $document->issued_date,
            $document->area,
            $document->dry_land,
            $document->wet_land,
            $document->unit,
            $document->old_locker_number,
            $document->latitude,
            $document->longitude,
            $document->court_case_no,
            $document->survey_no,
        ];
    }
}
