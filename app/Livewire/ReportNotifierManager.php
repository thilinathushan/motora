<?php

namespace App\Livewire;

use App\Models\FaultsPredictionReports;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReportNotifierManager extends Component
{
    public $activeReportIds = [];
    protected $listeners = ['newReportStarted' => 'addReport'];
    public $authUser = null;

    public function mount()
    {
        $this->authUser = Auth::guard('organization_user')->check() ? Auth::guard('organization_user')->user() : Auth::guard('web')->user();

        // On page load, find any reports that are still running for this user
        $this->activeReportIds = FaultsPredictionReports::where('user_id', $this->authUser->id)
            ->whereIn('status', ['pending', 'processing'])
            ->pluck('id')->toArray();
    }

    public function addReport($reportId)
    {
        if (!in_array($reportId, $this->activeReportIds)) {
            $this->activeReportIds[] = $reportId;
        }
    }

    public function render()
    {
        return view('livewire.report-notifier-manager');
    }
}