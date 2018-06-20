<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Family;
use App\Models\Job;
use App\Models\JobType;
use App\Models\Specialty;
use App\Revisionable\Revision;
use Illuminate\Http\Request;
use App\Http\Resources\Tenant\Revision as RevisionResource;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function show(Request $request)
    {
        $teacherTotals = json_encode([
            'Total',
            'Reals'
        ]);
        $teacherTotalsData = json_encode([
            Job::where('type_id',JobType::findByName('Professor/a')->id)->count(),
            110
        ]);
        $teacherTotalsColors = json_encode(['#F7464A','#46BFBD']);
        $tipusJornades = json_encode([
            'Completa',
            'Mitja',
            '1/3'
        ]); // TODO
        $teacherTypes = JobType::all()->pluck('name');
        $families = Family::all()->pluck('code');
        $specialties = Specialty::all()->pluck('code');
        $auditLogItems = $this->auditLogs();
        return view('tenants.home',compact('auditLogItems',
            'teacherTotals',
            'teacherTotalsData',
            'teacherTotalsColors',
            'teacherTypes',
            'families',
            'specialties',
            'tipusJornades'
            ));
    }

    /**
     * Audit logs.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function auditLogs() {
        return collect(RevisionResource::collection(
            Revision::orderBy('created_at', 'desc')->with(['user'])->get()));
    }
}
