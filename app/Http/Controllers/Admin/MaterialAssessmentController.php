<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use App\Models\Material;
use App\Models\MaterialAssessment;
use App\Models\TestAttempt;
use Illuminate\Http\Request;

class MaterialAssessmentController extends Controller
{
    public function edit(Material $material)
    {
        $material->load(['batch', 'assignments', 'tests']);

        $students = $material->batch->approvedPeserta;

        $assessments = MaterialAssessment::where('material_id', $material->id)
            ->get()->keyBy('user_id');

        $assignmentIds = $material->assignments->pluck('id');
        $testIds = $material->tests->pluck('id');

        $submissionGrades = AssignmentSubmission::whereIn('assignment_id', $assignmentIds)
            ->get()->groupBy('user_id');

        $testScores = TestAttempt::whereIn('test_id', $testIds)
            ->get()->groupBy('user_id');

        return view('admin.materials.assessments', compact(
            'material', 'students', 'assessments', 'submissionGrades', 'testScores'
        ));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'assessments'                     => 'required|array',
            'assessments.*.skor_penguasaan'   => 'nullable|in:A,B,C',
            'assessments.*.skor_tugas'        => 'nullable|in:A,B,C',
            'assessments.*.catatan_aktivitas' => 'nullable|string|max:2000',
            'assessments.*.skor_akhir'        => 'nullable|in:A,B,C',
        ]);

        foreach ($request->input('assessments') as $userId => $data) {
            MaterialAssessment::updateOrCreate(
                ['material_id' => $material->id, 'user_id' => $userId],
                [
                    'skor_penguasaan'   => $data['skor_penguasaan'] ?? null,
                    'skor_tugas'        => $data['skor_tugas'] ?? null,
                    'catatan_aktivitas' => $data['catatan_aktivitas'] ?? null,
                    'skor_akhir'        => $data['skor_akhir'] ?? null,
                    'assessed_by'       => auth()->id(),
                    'assessed_at'       => now(),
                ]
            );
        }

        return back()->with('success', 'Penilaian materi berhasil disimpan.');
    }
}
