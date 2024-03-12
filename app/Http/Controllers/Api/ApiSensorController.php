<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataLahan;
use App\Models\Notifikasi;
use App\Models\Sensor;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Laravel\Facades\Telegram;

class ApiSensorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $sensor = Sensor::all();
            return response()->json($sensor, Response::HTTP_OK);
        } catch (QueryException $e) {
            $error = [
                'error' => $e->getMessage()
            ];
            return response()->json($error, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**  
     * ##Daftar keterangan PH
     * 5,0 =  nitrogen tidak tersedia
     * 5,0 - 5,5 = phospor dan kalium tidak tersedia
     * 5,0 - 6,4 = Magnesium dan calsium tidak tersedia
     * 5,1 - 5,9 = nitrogen tidak memenuhi ,
     * 5,6 - 5,9 = Phospor tidak memenuhi
     * 5,6 - 5,9 = Kalium tidak memenuhi
     * 6,0 - 6,2 = nitrogen, kalium dan phosfor memenuhi penyerapan
     * 6,5 - 8,8 = magnesium dan kalsium memenuhi penyerapan
     * 
     * ## Selisih pemupukan
     * 1,0 = 18,3 Kg
     * 1,6 = 27,8 Kg
     * 
     */

    public function store(Request $request)
    {
        try {
            $request->merge(['ph1' => str_replace(',', '.', $request->ph1)]);
            $request->merge(['ph2' => str_replace(',', '.', $request->ph2)]);

            $validator = Validator::make($request->all(), [
                'code_alat' => 'required',
                'ph1' => 'required',
                'ph2' => 'required',
                'salinitas1' => 'required',
                'salinitas2' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            if (strpos($request->ph1, ',') !== false) {
                $ph1_origin = str_replace(',', '.', $request->ph1);
            } else {
                $ph1_origin = $request->ph1;
            }

            if (strpos($request->ph2, ',') !== false) {
                $ph2_origin = str_replace(',', '.', $request->ph2);
            } else {
                $ph2_origin = $request->ph2;
            }

            $previousData = Sensor::where('code_alat', $request->code_alat)->latest()->first();

            $previousPh1 =  number_format(floatval($previousData->ph1), 1);
            $previousPh2 = number_format(floatval($previousData->ph2), 1);

            Sensor::create($request->all());

            $ph1 = number_format($ph1_origin, 1);
            $ph2 = number_format($ph2_origin, 1);
            $salinitas1 = number_format(floatval($request->salinitas1), 1);
            $salinitas2 = number_format(floatval($request->salinitas2), 1);

            $selisihPh1 =  $previousPh1 - $ph1;
            $selisihPh2 =  $previousPh2 - $ph2;

            $id_petani = DataLahan::where('code_alat', $request->code_alat)->first()->id_user;

            $user = User::find($id_petani);

            //pengkondisian unsur hara
            if ($ph1 == 5.0 && $ph2 == 5.0) {
                // Nitrogen tidak tersedia
                $keterangan = 'Nitrogen tidak tersedia';
                $keterangan_json = '- Nitrogen tidak tersedia';
                $type = 'danger';
            } elseif (($ph1 >= 5.0 && $ph2 >= 5.0) && ($ph1 <= 5.5 && $ph2 <= 5.5)) {
                // Phosfor dan kalium tidak tersedia
                $keterangan = 'Phosfor tidak tersedia dan Kalium tidak tersedia';
                $keterangan_json = "- Phosfor tidak tersedia \n - Kalium tidak tersedia";
                $type = 'danger';
            } elseif (($ph1 >= 5.0 && $ph2 >= 5.0) && ($ph1 <= 6.4 && $ph2 <= 6.4)) {
                // Magnesium dan kalsium tidak tersedia
                $keterangan = 'Magnesium dan kalsium tidak tersedia';
                $keterangan_json = "Magnesium tidak tersedia \n Kalsium tidak tersedia";
                $type = 'danger';
            } elseif (($ph1 >= 5.1 && $ph2 >= 5.1) && ($ph1 <= 5.9 && $ph2 <= 5.9)) {
                // Nitrogen tidak memenuhi
                $keterangan = 'Nitrogen tidak memenuhi';
                $keterangan_json = "- Nitrogen tidak memenuhi";
                $type = 'warning';
            } elseif (($ph1 >= 5.6 && $ph2 >= 5.6) && ($ph1 <= 5.9 && $ph2 <= 5.9)) {
                // Phosfor tidak memenuhi
                // Kalium tidak memenuhi
                $keterangan = 'Phospor tidak memenuhi dan Kalium tidak memenuhi';
                $keterangan_json = "- Phospor tidak memenuhi \n -Kalium tidak memenuhi";
                $type = 'warning';
            } elseif (($ph1 >= 6.0 && $ph2 >= 6.0) && ($ph1 <= 6.2 && $ph2 <= 6.2)) {
                // Nitrogen, kalium, dan phosfor memenuhi penyerapan
                $keterangan = 'Nitrogen, Kalium, dan Phosfor memenuhi penyerapan';
                $keterangan_json = "- Nitrogen memenuhi penyerapan \n - Kalium memenuhi penyerapan \n - Phosfor memenuhi penyerapan";
                $type = 'success';
            } elseif (($ph1 >= 6.5 && $ph2 >= 6.5) && ($ph1 <= 8.8 && $ph2 <= 8.8)) {
                // Magnesium dan kalsium memenuhi penyerapan
                $keterangan = 'Magnesium dan Kalsium memenuhi penyerapan';
                $keterangan_json = "Magnesium memenuhi penyerapan \n Kalsium memenuhi penyerapan";
                $type = 'success';
            } elseif ($ph1 > 6.0 && $ph2 > 6.0) {
                // Magnesium dan Kalsium memenuhi penyerapan
                $keterangan = 'Nitrogen,phospor dan kalium tersedia';
                $keterangan_json = "Nitrogen,phospor dan kalium tersedia";
                $type = 'success';
            } else {
                $keterangan = 'tidak diketahui';
                $keterangan_json = '- tidak diketahui';
                $type = 'warning';
            }

            //kondisi saran pemupukan
            $pemupukan_json = ''; // Default value
            if (($ph1 < 5.6 && $ph2 < 5.6) || ($ph1 > 6.0 && $ph2 > 6.0)) {
                // Hanya jika range pH di bawah 5.6 dan di atas 6.0, pemupukan akan diperlukan
                $pemupukan_json = $this->Pemupukan($selisihPh1, $selisihPh2);
            }

            $notif = new Notifikasi();
            $notif->id_user = $id_petani;
            $notif->message = $keterangan;
            $notif->type = $type;
            $notif->url = '/data_lahan';
            if ($notif->save()) {
                if (($ph1 < 5.6 && $ph2 < 5.6) || ($ph1 > 6.0 && $ph2 > 6.0)) {

                    //telegram
                    $text =
                        "<b>Code Alat : </b>\n"
                        . $request->code_alat
                        // . "\n<b>Pemilik Alat : </b>\n"
                        // . $user->name . " (" . $user->email . ")"
                        . "\n\n<b>Data Sensor : </b>"
                        . "\n- PH 1 = " . $ph1 . "\n" . "- PH 2 = " . $ph2
                        // . "\n- Salinitas 1 = " . $salinitas1 . "\n" . "- Salinitas 2 = " . $salinitas2
                        . "\n\n<b>Status Tanah : </b>\n"
                        . $keterangan_json
                        . "\n\n<b>Saran Pemupukan : </b>\n"
                        . $pemupukan_json ?? '-'
                        . "\n\n<b>Selisih PH : </b>\n"
                        . $selisihPh1
                        . "\n\n https://mon-ph.mixdev.id";

                    //kirim notifikasi telegram
                    Telegram::sendMessage([
                        'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
                        'parse_mode' => 'HTML',
                        'text' => $text
                    ]);
                }
            }

            $response = [
                'Success' => 'New Data Created',
                'Telegram' => $text,
                'Keterangan' => $keterangan
            ];
            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            $error = [
                'error' => $e->getMessage()
            ];
            return response()->json($error, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    public function Pemupukan($selisihPh1, $selisihPh2)
    {
        $absoluteSelisihPh1 = abs($selisihPh1);
        $absoluteSelisihPh2 = abs($selisihPh2);

        if ($absoluteSelisihPh1 == 1.0 && $absoluteSelisihPh2 == 1.0) {
            return "Lakukan pemupukan sebanyak 91,5 gram pupuk";
        } elseif ($absoluteSelisihPh1 == 1.6 && $absoluteSelisihPh2 == 1.6) {
            return "Lakukan pemupukan sebanyak 93 gram pupuk";
        } elseif ($absoluteSelisihPh1 == 1.8 && $absoluteSelisihPh2 == 1.8) {
            return "Lakukan pemupukan sebanyak 94,7 gram pupuk";
        } elseif ($absoluteSelisihPh1 == 0.7 && $absoluteSelisihPh2 == 0.7) {
            return "Lakukan pemupukan sebanyak 97,1 gram pupuk";
        } elseif ($absoluteSelisihPh1 == 0.6 && $absoluteSelisihPh2 == 0.6) {
            return "Lakukan pemupukan sebanyak 100 gram pupuk";
        } elseif ($absoluteSelisihPh1 == 0.5 && $absoluteSelisihPh2 == 0.5) {
            return "Lakukan pemupukan sebanyak 104 gram pupuk";
        } elseif ($absoluteSelisihPh1 == 0.4 && $absoluteSelisihPh2 == 0.4) {
            return "Lakukan pemupukan sebanyak 110,6 gram pupuk";
        } elseif ($absoluteSelisihPh1 == 0.3 && $absoluteSelisihPh2 == 0.3) {
            return "Lakukan pemupukan sebanyak 121 gram pupuk";
        } elseif ($absoluteSelisihPh1 == 0.2 && $absoluteSelisihPh2 == 0.2) {
            return "Lakukan pemupukan sebanyak 141,25 gram pupuk";
        } elseif ($absoluteSelisihPh1 == 0.1 && $absoluteSelisihPh2 == 0.1) {
            return "Lakukan pemupukan sebanyak 205 gram pupuk";
        } else {
            return "Tidak diperlukan pemupukan";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $sensor = Sensor::findOrFail($id);
            $response = [
                $sensor
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $sensor = Sensor::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'code_alat' => 'required',
                'ph1' => 'required',
                'ph2' => 'required',
                'salinitas1' => 'required',
                'salinitas2' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['succeed' => false, 'Message' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $sensor->update($request->all());
            $response = [
                'Success' => 'data sensor Updated'
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'no result',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Sensor::findOrFail($id)->delete();
            return response()->json(['success' => 'Data deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], Response::HTTP_FORBIDDEN);
        }
    }
}
