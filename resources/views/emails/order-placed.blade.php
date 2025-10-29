<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng mới</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .order-info {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #28a745;
        }
        .order-info h3 {
            margin-top: 0;
            color: #28a745;
        }
        .customer-info, .shipping-info {
            margin: 10px 0;
        }
        .customer-info p, .shipping-info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
        }
        th {
            background-color: #28a745;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .total-row {
            font-weight: bold;
            font-size: 1.1em;
            background-color: #f0f0f0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 0 0 5px 5px;
            font-size: 0.9em;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.9em;
            font-weight: bold;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🛒 ĐỚN HÀNG MỚI</h1>
    </div>

    <div class="content">
        <div class="order-info">
            <h3>Thông tin đơn hàng #{{ $order->id }}</h3>
            <p><strong>Mã đơn hàng:</strong> #{{ $order->id }}</p>
            <p><strong>Thời gian:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
            <p><strong>Trạng thái:</strong> <span class="badge badge-pending">{{ $order->status }}</span></p>
        </div>

        <div class="customer-info">
            <h3>👤 Thông tin khách hàng</h3>
            <p><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
        </div>

        <div class="shipping-info">
            <h3>📦 Địa chỉ giao hàng</h3>
            <p>{{ $order->customer_address }}</p>
        </div>

        @if($order->notes)
        <div class="order-info">
            <h3>📝 Ghi chú</h3>
            <p>{{ $order->notes }}</p>
        </div>
        @endif

        <h3>🛍️ Chi tiết đơn hàng</h3>
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th style="text-align: center;">Số lượng</th>
                    <th style="text-align: right;">Đơn giá</th>
                    <th style="text-align: right;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->product_price, 0, ',', '.') }}₫</td>
                    <td style="text-align: right;">{{ number_format($item->total, 0, ',', '.') }}₫</td>
                </tr>
                @endforeach
                <!-- Subtotal / Shipping / Tax breakdown -->
                <tr>
                    <td colspan="3" style="text-align: right;">Tạm tính:</td>
                    <td style="text-align: right;">{{ number_format($order->subtotal ?? 0, 0, ',', '.') }}₫</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Phí vận chuyển:</td>
                    <td style="text-align: right;">
                        @if(isset($order->shipping) && $order->shipping == 0)
                            <span style="color: #28a745; font-weight: bold;">Miễn phí</span>
                        @else
                            {{ number_format($order->shipping ?? 0, 0, ',', '.') }}₫
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Thuế:</td>
                    <td style="text-align: right;">{{ number_format($order->tax ?? 0, 0, ',', '.') }}₫</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">TỔNG CỘNG:</td>
                    <td style="text-align: right; color: #28a745;">{{ number_format($order->total, 0, ',', '.') }}₫</td>
                </tr>
            </tbody>
        </table>

        <div class="order-info">
            <p><strong>💳 Phương thức thanh toán:</strong> {{ $order->payment_method == 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản' }}</p>
            <p><strong>💰 Tình trạng thanh toán:</strong> {{ $order->payment_status == 'paid' ? '✅ Đã thanh toán' : '⏳ Chưa thanh toán' }}</p>
        </div>
    </div>

    <div class="footer">
        <p>Email này được gửi tự động từ hệ thống Ngọc Huyền Shop</p>
        <p>Vui lòng truy cập trang quản trị để xử lý đơn hàng: <a href="{{ config('app.url') }}/admin/orders">Quản lý đơn hàng</a></p>
        <p style="margin-top: 15px; font-size: 0.85em;">
            <strong>Ngọc Huyền Shop</strong><br>
            Website: <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
        </p>
    </div>
</body>
</html>
