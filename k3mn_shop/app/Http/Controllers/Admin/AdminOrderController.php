<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminOrderController extends Controller
{
    //
    public function listConfirmOrder(Request $request) {
        $keywork = $request->q;
        if( $keywork ) {
            $order = Order::find($keywork);
            if( $order ) {
                return redirect()->route('detailOrder', $order->id);
            }
        }
        return view('admin.order.confirmOrder', 
                [
                    'listConfirmOrder' => Order::where('status_order_id', '1')
                                                ->orderBy('created_at', 'desc')
                                                ->paginate(10),
                ]);
    }

    public function listShippedOrder() {
        return view('admin.order.shippedOrder', 
                [
                    'listShippedOrder' => Order::where('status_order_id', '2')
                                                ->orderBy('shipped_date', 'desc')
                                                ->paginate(10),
                ]);
    }

    public function listCompletedOrder() {
        return view('admin.order.completedOrder', 
                [
                    'listCompletedOrder' => Order::where('status_order_id', '3')
                                                ->orderBy('completed_date', 'desc')
                                                ->paginate(10),
                ]);
    }

    public function detailOrder(Request $request, $id) {
        return view('admin.order.detailOrder',
                [
                    'order' => Order::findOrFail($id)
                ]);
    }

    // Các hàm xử lý việc cập nhật trạng thái đơn đặt hàng
    public function confirmOrder($id) {
        Order::findOrFail($id)
                ->update([
                    'status_order_id' => 2,
                    'shipped_date' => Carbon::now('Asia/Ho_Chi_Minh'),
                ]);
        return redirect()->route('listConfirmOrder');
    }

    public function completedOrder($id) {
        Order::findOrFail($id)
                ->update([
                    'status_order_id' => 3,
                    'completed_date' => Carbon::now('Asia/Ho_Chi_Minh'),
                ]);
        return redirect()->route('listShippedOrder');
    }
}
