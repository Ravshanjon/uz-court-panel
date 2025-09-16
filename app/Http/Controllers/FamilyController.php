<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Judges;
use App\Models\Parents;
use App\Services\OCRFamilyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function analyze(Judges $judge)
    {
        $relativePath = 'docs/family/example.docx';

        $members = OCRFamilyService::extractFromDocx($relativePath, $judge->id);

        if (isset($members['error'])) {
            return back()->with('error', $members['error']);
        }

        // 🔍 AI'dan олинган натижани тозалаш
        Family::where('judge_id', $judge->id)->delete();

        $spouseFound = false;
        $spouseParentId = Parents::where('name', 'Турмуш ўртоғи')->first()?->id;


        foreach ($members as $data) {
            $relation = $data['relation'] ?? null;
            $name = $data['name'] ?? null;
            $birthPlace = $data['birth_place'] ?? null;
            $workingPlace = $data['working_place'] ?? null;

            if ($relation === 'Турмуш ўртоғи' || $data['parents_id'] == $spouseParentId) {
                // 🔎 Матнда "никоҳдан ажратилган" борми?
                $allText = strtolower(($birthPlace ?? '') . ' ' . ($workingPlace ?? ''));

                if (str_contains($allText, 'никоҳдан ажратилган')) {
                    $spouseAnnulled = true;
                }
            }

            // Saqlash
            Family::create([
                'judge_id' => $judge->id,
                'relation' => $relation,
                'name' => $name,
                'birth_date' => $data['birth_date'] ?? null,
                'parents_id' => Parents::where('name', $relation)->first()?->id,
            ]);
        }

        // ✅ Агар турмуш ўртоғи топилмаса — ажрашган деб белгилаймиз
        $judge->marriage_annulled = !$spouseFound;
        $judge->annulment_note = $spouseFound ? null : 'AI орқали аниқланди: турмуш ўртоғи топилмади';
        $judge->save();

        return back()->with('success', '✅ Qarindoshlar AI орқали сақланди!');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Family $family)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Family $family)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Family $family)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Family $family)
    {
        //
    }
}
