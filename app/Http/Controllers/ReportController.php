<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use DB;
use App\Models\job;
use App\Models\company;
use App\Models\country;
use DateTime;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $objCompany = company::orderBy('name')->get();
        $objCountry = country::orderBy('country_name')->get();
        if ($request->ajax()) {
            $objDb = job::
            join('company', 'company.id','job.company_id')
            ->join('job_processing', 'job_processing.job_id','job.id')
            ->join('number as number', 'number.id','job_processing.number_id')
            ->join('country_code', 'country_code.id','number.country_code_id')
            ->groupBy('start_time')
            ->select([
                DB::raw("DISTINCT COUNT('job_processing') as procjob"),
                DB::raw("COUNT(DISTINCT CASE WHEN job_processing.call_description_id <> NULL THEN job_processing.call_description_id ELSE 0 END) -1 as failjob"),
                DB::raw('DATE(job.start_time) as start_time'),
                DB::raw('company.name as company_name'),
                DB::raw('country_code.country_name as country_name'),
                DB::raw('job_processing.call_description_id as call_description'),
                DB::raw('job_processing.call_start_time as call_start_time'),
                DB::raw('job_processing.call_connect_time as call_connect_time'),
                DB::raw('company.id as company_id'),
                DB::raw('country_code.id as country_code_id'),
            ]);
            return Datatables::of($objDb)
                    ->addIndexColumn()
                    ->addColumn('company', function ($row) {
                        return (isset($row->company_name) ? $row->company_name : 'NA');
                    })
                    ->editColumn('start_time', function ($row) {
                        return isset($row->start_time) ? date('Y-m-d', strtotime($row->start_time)) : "";
                    })
                    ->addColumn('country', function($row){
                        return isset($row->country_name) ? $row->country_name : 'NA';
                    })
                    ->addColumn('no_of_test', function($row){
                        return isset($row->procjob) ? $row->procjob : 0;
                    })
                    ->addColumn('no_of_fails', function($row){
                        return isset($row->failjob) ? $row->failjob : 0;
                    })
                    ->addColumn('conn_rate', function($row){
                        $connected_test = $row->procjob - $row->failjob;
                        return (($connected_test * 100) / $row->procjob) . '%';
                    })
                    ->addColumn('pdd', function($row){
                        if(!empty($row->call_description)){
                            $call_start_time = new DateTime($row->call_start_time);
                            $call_connect_time = new DateTime($row->call_connect_time);
                            $diff = $call_start_time->diff($call_connect_time);
                            return $diff->format('%H:%I:%S');  // returns difference in hr min and sec
                           
                        }
                        return 0;
                    })
                    ->addColumn('action', function($row){
   
                           $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
     
                            return $btn;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('min_start_date'))) {
                            $instance->where('start_time', '>=',$request->get('min_start_date'));
                        }
                        if (!empty($request->get('max_start_date'))) {
                            $instance->where('start_time', '<=',$request->get('max_start_date'));
                        }
                        if (!empty($request->get('country_id'))) {
                            $instance->where('country_code_id', '=',$request->get('country_id'));
                        }
                        if (!empty($request->get('company_id'))) {
                            $instance->where('company.id', '=',$request->get('company_id'));
                        }
                    })
                    ->rawColumns(['country', 'no_of_test', 'no_of_fails', 'conn_rate', 'pdd', 'action'])
                    ->make(true);
        }
      
        return view('reports.index')->with(['objCompany' => $objCompany, 'objCountry' => $objCountry]);
    }
}
