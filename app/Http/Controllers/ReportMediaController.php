<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportMedia;
use App\Http\Requests\StoreReportMediaRequest;
use App\Http\Requests\UpdateReportMediaRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ReportMediaController extends Controller
{
    /**
     * Store a newly uploaded media file for a report.
     */
    public function store(StoreReportMediaRequest $request, Report $report)
    {
        Gate::authorize('update', $report);

        $validated = $request->validated();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('reports/' . $report->id, 'public');

            $media = $report->media()->create([
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'original_filename' => $file->getClientOriginalName(),
                'sort_order' => $report->media()->count(),
            ]);

            return redirect()->back()
                ->with('success', __('Media file uploaded successfully.'));
        }

        return redirect()->back()
            ->with('error', __('No file was uploaded.'));
    }

    /**
     * Download a media file.
     */
    public function show(Report $report, ReportMedia $media)
    {
        Gate::authorize('view', $report);

        // Verify media belongs to this report
        if ($media->report_id !== $report->id) {
            abort(404);
        }

        return Storage::disk('public')->download(
            $media->file_path,
            $media->original_filename
        );
    }

    /**
     * Update media file order.
     */
    public function update(UpdateReportMediaRequest $request, Report $report, ReportMedia $media)
    {
        Gate::authorize('update', $report);

        // Verify media belongs to this report
        if ($media->report_id !== $report->id) {
            abort(404);
        }

        $validated = $request->validated();
        $media->update($validated);

        return redirect()->back()
            ->with('success', __('Media updated successfully.'));
    }

    /**
     * Remove media file from report.
     */
    public function destroy(Report $report, ReportMedia $media)
    {
        Gate::authorize('update', $report);

        // Verify media belongs to this report
        if ($media->report_id !== $report->id) {
            abort(404);
        }

        // Delete file from storage
        Storage::disk('public')->delete($media->file_path);

        // Delete database record
        $media->delete();

        return redirect()->back()
            ->with('success', __('Media file deleted successfully.'));
    }
}
