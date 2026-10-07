<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\config;
use App\Models\Guide;
use App\Models\produits;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GuideController extends Controller
{
    public function create(Request $request)
    {
        $user = null;
        $appointmentModel = null;

        if ($request->client) {
            $user = User::find($request->client);
        }

        if ($request->appointment) {
            $appointmentModel = Appointment::find($request->appointment);

            // Priorité au user du rendez-vous
            if ($appointmentModel && $appointmentModel->user) {
                $user = $appointmentModel->user;
            }
        }

        $produits = produits::all();

        return view('admin.guides.index', [
            'produits' => $produits,
            'user' => $user,
            'client' => $user,
            'appointment' => $appointmentModel
        ]);
    }

    public function store(Request $request)
    {
        try {
        $request->validate([
            'id_appointment' => 'nullable|exists:appointments,id',

            'client_id'        => 'nullable|integer|exists:users,id',
            'skin_type'        => 'required|string',
            'skin_concern'     => 'nullable|array',
            'skin_condition'   => 'nullable|array',
            // weekly
            'weekly_exfoliator'    => 'nullable|array',
            'weekly_face_mask'     => 'nullable|array',
            'weekly_wash_mask'     => 'nullable|array',

            'skin_concern.*'   => 'string',
            'skin_condition.*' => 'string',

            // morning = arrays (produits multiselect)
            'morning.cleanser'         => 'nullable|array',
            'morning.toner'            => 'nullable|array',
            'morning.serum'            => 'nullable|array',
            'morning.eye_cream'        => 'nullable|array',
            'morning.cream'            => 'nullable|array',
            'morning.spf'              => 'nullable|array',
            'morning.others'           => 'nullable|array',

            // night
            'night.first_cleanser'     => 'nullable|array',
            'night.second_cleanser'    => 'nullable|array',
            'night.toner'              => 'nullable|array',
            'night.serum'              => 'nullable|array',
            'night.eye_cream'          => 'nullable|array',
            'night.cream'              => 'nullable|array',
            'night.spot_treatment'     => 'nullable|array',
            'night.others'             => 'nullable|array',
        ]);

        // 🟣 CONCAT Others (Skin Concern)
            $concerns   = $request->input('skin_concern', []);
            $conditions = $request->input('skin_condition', []);

            if (in_array('others', $concerns, true) && $request->filled('skin_concern_other')) {
                $concerns[] = $request->skin_concern_other;
            }

            if (in_array('others', $conditions, true) && $request->filled('skin_condition_other')) {
                $conditions[] = $request->skin_condition_other;
            }
            // Helper
            $convert = fn($arr) => $arr ? implode(',', $arr) : null;
        // 1️⃣ Créer le Guide
        $guide = Guide::create([
            'id_appointment' =>$request->id_appointment,
            'user_id'        =>$request->client_id,   // ✅ CORRECT
            'skin_type'      => $request->skin_type,
            'skin_concern'   => implode(',', $concerns),
            'skin_condition' => implode(',', $conditions),
            // 🆕 WEEKLY ROUTINE
            'weekly_exfoliator'  => $convert($request->weekly_exfoliator ?? []),
            'weekly_face_mask'   => $convert($request->weekly_face_mask ?? []),
            'weekly_wash_mask'   => $convert($request->weekly_wash_mask ?? []),
        ]);

        // 🟠 UTILITAIRE pour convertir les arrays en string
        $convert = function ($array) {
            return isset($array) ? implode(',', $array) : null;
        };

        // 2️⃣ Créer MORNING ROUTINE
        $guide->routines()->create([
            'type'              => 'morning',
            'cleanser'          => $convert($request->morning['cleanser'] ?? []),
            'toner'             => $convert($request->morning['toner'] ?? []),
            'serum'             => $convert($request->morning['serum'] ?? []),
            'eye_cream'         => $convert($request->morning['eye_cream'] ?? []),
            'cream'             => $convert($request->morning['cream'] ?? []),
            'spf'               => $convert($request->morning['spf'] ?? []),
            'others'            => $convert($request->morning['others'] ?? []),
        ]);

        // 3️⃣ Créer NIGHT ROUTINE
        $guide->routines()->create([
            'type'              => 'night',
            'second_cleanser'   => $convert($request->night['second_cleanser'] ?? []),
            'cleanser'    => $convert($request->night['first_cleanser'] ?? []),
            'toner'             => $convert($request->night['toner'] ?? []),
            'serum'             => $convert($request->night['serum'] ?? []),
            'eye_cream'         => $convert($request->night['eye_cream'] ?? []),
            'cream'             => $convert($request->night['cream'] ?? []),
            'spot_treatment'    => $convert($request->night['spot_treatment'] ?? []),
            'others'            => $convert($request->night['others'] ?? []),
        ]);

        return back()->with('success', 'Guide et routines créés avec succès !');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            // 🔥 Afficher le message d’erreur SQL ou Laravel
            return back()->with('error', $e->getMessage());
        }
    }
    public function edit(Guide $guide)
    {
        $produits = produits::all();

        return view('admin.guides.edit', compact('guide', 'produits'));
    }


    public function update(Request $request, Guide $guide)
    {
        try {

            // -------------------------
            // 1️⃣ VALIDATION
            // -------------------------
            $request->validate([
                'skin_type'        => 'required|string',
                'skin_concern'     => 'nullable|array',
                'skin_condition'   => 'nullable|array',
                'skin_concern.*'   => 'string',
                'skin_condition.*' => 'string',
                // weekly
                'weekly_exfoliator'    => 'nullable|array',
                'weekly_face_mask'     => 'nullable|array',
                'weekly_wash_mask'     => 'nullable|array',

                // morning routine
                'morning.cleanser'      => 'nullable|array',
                'morning.toner'         => 'nullable|array',
                'morning.serum'         => 'nullable|array',
                'morning.eye_cream'     => 'nullable|array',
                'morning.cream'         => 'nullable|array',
                'morning.spf'           => 'nullable|array',
                'morning.others'        => 'nullable|array',

                // night routine
                'night.first_cleanser'  => 'nullable|array',
                'night.second_cleanser' => 'nullable|array',
                'night.toner'           => 'nullable|array',
                'night.serum'           => 'nullable|array',
                'night.eye_cream'       => 'nullable|array',
                'night.cream'           => 'nullable|array',
                'night.spot_treatment'  => 'nullable|array',
                'night.others'          => 'nullable|array',
            ]);
            $concerns = $request->input('skin_concern', []);
            $conditions = $request->input('skin_condition', []);
            // -------------------------
            // 2️⃣ OTHER VALUES (concern/condition)
            // -------------------------
            if (in_array('others', $concerns, true) && $request->filled('skin_concern_other')) {
                $concerns[] = $request->skin_concern_other;
            }

// Skin Condition
            if (in_array('others', $conditions, true) && $request->filled('skin_condition_other')) {
                $conditions[] = $request->skin_condition_other;
            }

            // Convert helper
            $convert = fn($arr) => $arr ? implode(',', $arr) : null;


            // -------------------------
            // 3️⃣ UPDATE GUIDE
            // -------------------------
            $guide->update([
                'skin_type'      => $request->skin_type,
                'skin_concern'   => implode(',', $concerns),
                'skin_condition' => implode(',', $conditions),
                // 🆕 UPDATE WEEKLY
                'weekly_exfoliator'  => $convert($request->weekly_exfoliator ?? []),
                'weekly_face_mask'   => $convert($request->weekly_face_mask ?? []),
                'weekly_wash_mask'   => $convert($request->weekly_wash_mask ?? []),
            ]);


            // -------------------------
            // 4️⃣ UPDATE ROUTINES
            // -------------------------

            // → Morning
            $morning = $guide->routines()->where('type', 'morning')->first();
            if ($morning) {
                $morning->update([
                    'cleanser'       => $convert($request->morning['cleanser'] ?? []),
                    'toner'          => $convert($request->morning['toner'] ?? []),
                    'serum'          => $convert($request->morning['serum'] ?? []),
                    'eye_cream'      => $convert($request->morning['eye_cream'] ?? []),
                    'cream'          => $convert($request->morning['cream'] ?? []),
                    'spf'            => $convert($request->morning['spf'] ?? []),
                    'others'         => $convert($request->morning['others'] ?? []),
                ]);
            }

            // → Night
            $night = $guide->routines()->where('type', 'night')->first();
            if ($night) {
                $night->update([
                    'cleanser'   => $convert($request->night['first_cleanser'] ?? []),
                    'second_cleanser'  => $convert($request->night['second_cleanser'] ?? []),
                    'toner'            => $convert($request->night['toner'] ?? []),
                    'serum'            => $convert($request->night['serum'] ?? []),
                    'eye_cream'        => $convert($request->night['eye_cream'] ?? []),
                    'cream'            => $convert($request->night['cream'] ?? []),
                    'spot_treatment'   => $convert($request->night['spot_treatment'] ?? []),
                    'others'           => $convert($request->night['others'] ?? []),
                ]);
            }


            // -------------------------
            // 5️⃣ SUCCESS
            // -------------------------
            return redirect()
                ->back()
                ->with('success', 'Guide mis à jour avec succès !');


        } catch (\Exception $e) {

            // -------------------------
            // ❌ ERREUR
            // -------------------------
            return redirect()
                ->back()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
    public function show()
    {
        $user = auth()->user();
        $config = Config::first();

        // Récupérer son guide
        $guide = Guide::where('user_id', $user->id)
            ->with('routines')
            ->first();

        if (!$guide) {
            return redirect()->back()->with('warning', 'Aucun guide skincare trouvé.');
        }

        // Séparer morning & night
        $morning = $guide->routines->where('type', 'morning')->first();
        $night   = $guide->routines->where('type', 'night')->first();

        // Fonction utilitaire pour convertir les IDs => produits
        $getProducts = function($stringIds) {
            if (!$stringIds) return [];
            $ids = explode(',', $stringIds);
            return \App\Models\produits::whereIn('id', $ids)->get();
        };

        return view('front.guide', [
            'config' => $config,
            'guide'   => $guide,
            'morning' => $morning,
            'night'   => $night,
            // produits convertis :
            'morningProducts' => [
                'cleanser'  => $getProducts($morning->cleanser),
                'toner'     => $getProducts($morning->toner),
                'serum'     => $getProducts($morning->serum),
                'eye_cream' => $getProducts($morning->eye_cream),
                'cream'     => $getProducts($morning->cream),
                'spf'       => $getProducts($morning->spf),
                'others'    => $getProducts($morning->others),
            ],
            'nightProducts' => [
                'cleanser'        => $getProducts($night->cleanser),
                'second_cleanser' => $getProducts($night->second_cleanser),
                'toner'           => $getProducts($night->toner),
                'serum'           => $getProducts($night->serum),
                'eye_cream'       => $getProducts($night->eye_cream),
                'cream'           => $getProducts($night->cream),
                'spot_treatment'  => $getProducts($night->spot_treatment),
                'others'          => $getProducts($night->others),
            ],
            // 🆕 WEEKLY ROUTINE
            'weeklyProducts' => [
                'exfoliator'  => $getProducts($guide->weekly_exfoliator),
                'face_mask'   => $getProducts($guide->weekly_face_mask),
                'wash_mask'   => $getProducts($guide->weekly_wash_mask),
            ],
        ]);
    }



}
