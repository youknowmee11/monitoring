<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataLahan;
use App\Models\Notifikasi;
use App\Models\Sensor;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
    public function store(Request $request)
    {
        try {
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
            Sensor::create($request->all());

            $ph1 = number_format($request->ph1, 1);
            $ph2 = number_format($request->ph2, 1);

            $previousData = Sensor::where('code_alat', $request->code_alat)->latest()->first();

            $previousPh1 = number_format($previousData->ph1, 1);
            $previousPh2 = number_format($previousData->ph2, 1);

            $id_petani = DataLahan::where('code_alat', $request->code_alat)->first()->id_user;

            // if ($ph1 != $previousPh1 || $ph2 != $previousPh2) {
            if (($ph1 >= 5.6 && $ph2 >= 5.6) && ($ph2 <= 6.2 && $ph2 <= 6.2)) {
                $notif = new Notifikasi();
                $notif->id_user = $id_petani;
                $notif->message = 'Nutrisi tanah seimbang';
                $notif->type = 'primary';
                $notif->url = '/data_lahan';
                $notif->save();

                ob_flush();
                flush();
            } elseif ($ph1 < 5.6 && $ph2 < 5.6) {
                $notif = new Notifikasi();
                $notif->id_user = $id_petani;
                $notif->message = 'Nutrisi Tanah kehilangan kalsium(ca), magnesium(mg)';
                $notif->type = 'danger';
                $notif->url = '/data_lahan';
                $notif->save();

                ob_flush();
                flush();
            } elseif ($ph1 > 6.2 && $ph2 > 6.2) {
                $notif = new Notifikasi();
                $notif->id_user = $id_petani;
                $notif->message = 'Nutrisi Tanah kehilangan fosfor (p), mangan (mn)';
                $notif->type = 'danger';
                $notif->url = '/data_lahan';
                $notif->save();

                ob_flush();
                flush();
            }
            // }

            $response = [
                'Success' => 'New Data Created',
            ];
            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            $error = [
                'error' => $e->getMessage()
            ];
            return response()->json($error, Response::HTTP_UNPROCESSABLE_ENTITY);
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
