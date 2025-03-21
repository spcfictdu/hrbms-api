<?php

namespace App\Http\Controllers\CashierSession;

use App\Http\Controllers\Controller;

use App\Http\Requests\CashierSession\{IndexCashierSessionRequest, CreateCashierSessionRequest, ShowCashierSessionRequest, UpdateCashierSessionRequest, DeleteCashierSessionRequest};
use App\Models\CashierSession\CashierSession;
use App\Repositories\CashierSession\{IndexCashierSessionRepository, CreateCashierSessionRepository, ShowCashierSessionRepository, UpdateCashierSessionRepository, DeleteCashierSessionRepository};
use Illuminate\Http\Request;

class CashierSessionController extends Controller
{
    protected $index, $create, $show, $update, $delete;

    // * CONSTRUCTOR INJECTION
    public function __construct(
        IndexCashierSessionRepository   $index,
        CreateCashierSessionRepository  $create,
        ShowCashierSessionRepository    $show,
        UpdateCashierSessionRepository  $update,
        DeleteCashierSessionRepository  $delete
    ) {
        $this->index   = $index;
        $this->create  = $create;
        $this->show    = $show;
        $this->update  = $update;
        $this->delete  = $delete;
    }

    protected function index(IndexCashierSessionRequest $request)
    {
        return $this->index->execute();
    }


    protected function create(CreateCashierSessionRequest $request)
    {
        return $this->create->execute($request);
    }


    protected function show(ShowCashierSessionRequest $request, $id)
    {
        return $this->show->execute($id);
    }


    protected function update(UpdateCashierSessionRequest $request, $id)
    {
        return $this->update->execute($request, $id);
    }


    protected function delete(DeleteCashierSessionRequest $request, $id)
    {
        return $this->delete->execute($id);
    }

    protected function toggle(Request $request, $id)
    {
        $cashierSession = CashierSession::find($id);

        $request->validate([
            'status' => 'required|in:INACTIVE,ACTIVE'
        ]);

        $cashierSession->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'succcess',
            'data' => $cashierSession
        ]);
    }
}
