<?php

use App\Card;
use App\Column;
use App\Http\Requests\GetCardsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\DbDumper\Databases\MySql;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('access')->group( function () {
    Route::get('/columns', function (Request $request) {
        return Column::with('cards')->get();
    });

    Route::put('/columns', function (Request $request) {
        try {
            foreach ($request->all() as $column) {
                if (isset($column['cards'])) {
                    foreach ($column['cards'] as $index => $card) {
                        $cardModel = Card::findOrFail($card['id']);
                        if ($card['column_id'] != $column['id']) {
                            $cardModel->column_id = $column['id'];
                        }
                        $cardModel->order = $index;
                        $cardModel->save();
                    }
                }

            }

            return Column::with('cards')->get();
        } catch (Exception $e) {
        }
    });

    Route::post('/columns', function (Request $request) {
        return Column::create([
            'name' => $request->get('name')
        ])->load('cards');
    });

    Route::put('/card', function (Request $request) {
        $card = Card::findOrFail($request->get('id'));
        $card->title = $request->get('title');
        $card->description = $request->get('description');
        $card->save();
        return $card;
    });

    Route::post('/card', function (Request $request) {
        return Card::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'column_id' => $request->get('column'),
            'order' => 0,
        ]);
    });

    Route::delete('/column/{column}', function (Column $column) {
        $column->cards()->delete();
        return $column->delete();
    });

    Route::get('/export', function (Request $request) {
        MySql::create()
            ->setDbName(config('database.connections.mysql.database'))
            ->setUserName(config('database.connections.mysql.username'))
            ->setPassword(config('database.connections.mysql.password'))
            ->dumpToFile(storage_path('app/dump.sql'));

        return response()->download(storage_path('app/dump.sql'));
    });

    Route::get('list-cards', function (GetCardsRequest $request) {
        $query = Card::query();

        $request
            ->whenFilled('date', function ($date) use ($query) {
                $query->where('created_at', '<', $date);
            })
            ->whenFilled('status', function ($status) use ($query) {
                if ( (bool) $status) {
                    return response()->json($query->get());
                } else {
                    $query->withTrashed()->whereNotNull('deleted_at');
                }x
            });

            return response()->json($query->get());
    });
});
