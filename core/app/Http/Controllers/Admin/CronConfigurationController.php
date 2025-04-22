<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\CronJob;
use App\Models\CronJobLog;
use App\Models\CronSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CronConfigurationController extends Controller
{
    public function cronJobs()
    {
        $pageTitle = 'Cron Jobs';
        $crons     = CronJob::with('schedule', 'logs')->get();
        $schedules = CronSchedule::active()->orderBy('interval')->get();
        return view('admin.cron.index', compact('pageTitle', 'crons', 'schedules'));
    }

    public function cronJobStore(Request $request)
    {
        $request->validate([
            'name'             => 'required',
            'next_run'         => 'required',
            'cron_schedule_id' => 'required|integer',
            'url'              => 'required|url',
        ]);

        $cronJob                   = new CronJob();
        $cronJob->name             = $request->name;
        $cronJob->alias            = titleToKey($request->name);
        $cronJob->next_run         = Carbon::parse($request->next_run)->toDateTimeString();
        $cronJob->cron_schedule_id = $request->cron_schedule_id;
        $cronJob->url              = $request->url;
        $cronJob->is_default       = Status::NO;
        $cronJob->save();

        $notify[] = ['success', 'Cron job updated successfully'];
        return to_route('admin.cron.index')->withNotify($notify);
    }

    public function cronJobUpdate(Request $request)
    {
        $request->validate([
            'id'               => 'required|integer',
            'name'             => 'required',
            'next_run'         => 'required',
            'cron_schedule_id' => 'required|integer',
        ]);

        $cronJob       = CronJob::findOrFail($request->id);
        $cronJob->name = $request->name;

        if (!$cronJob->is_default) {
            $request->validate(['url' => 'required|url']);
            $cronJob->url = $request->url;
            $cronJob->alias = titleToKey($request->name);
        }

        $cronJob->next_run         = Carbon::parse($request->next_run)->toDateTimeString();
        $cronJob->cron_schedule_id = $request->cron_schedule_id;
        $cronJob->save();

        $notify[] = ['success', 'Cron job update successfully'];
        return back()->withNotify($notify);
    }

    public function CronJobDelete($id)
    {
        $cronJob = CronJob::where('is_default', 0)->where('id', $id)->firstOrFail();
        $cronJob->delete();

        CronJobLog::where('cron_job_id', $id)->delete();

        $notify[] = ['success', 'Cron job deleted successfully'];
        return back()->withNotify($notify);
    }

    public function schedule()
    {
        $pageTitle = 'Cron Schedules';
        $schedules = CronSchedule::paginate(getPaginate());
        return view('admin.cron.schedule', compact('pageTitle', 'schedules'));
    }

    public function scheduleStore(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'interval' => 'required|integer|gt:0',
        ]);

        $id = $request->id ?? 0;

        if ($id) {
            $schedule = CronSchedule::findOrFail($id);
            $message  = "Cron schedule updated successfully";
        } else {
            $schedule = new CronSchedule();
            $message  = "Cron schedule added successfully";
        }
        $schedule->name     = $request->name;
        $schedule->interval = $request->interval;
        $schedule->status   = Status::ENABLE;
        $schedule->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function scheduleStatus($id)
    {
        return CronSchedule::changeStatus($id);
    }

    public function schedulePause($id)
    {

        return CronJob::changeStatus($id, 'is_running');
    }

    public function scheduleLogs($id)
    {
        $cronJob   = CronJob::findOrFail($id);
        $pageTitle = $cronJob->name . " Cron Schedule Logs";
        $logs      = CronJobLog::where('cron_job_id', $cronJob->id)->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('admin.cron.logs', compact('pageTitle', 'logs', 'cronJob'));
    }

    public function scheduleLogResolved($id)
    {
        $log        = CronJobLog::findOrFail($id);
        $log->error = null;
        $log->save();

        $notify[] = ['success', 'Cron log resolved successfully'];
        return back()->withNotify($notify);
    }

    public function logFlush($id)
    {
        $cronJob = CronJob::findOrFail($id);
        CronJobLog::where('cron_job_id', $cronJob->id)->delete();

        $notify[] = ['success', 'All logs flushed successfully'];
        return back()->withNotify($notify);
    }
}
