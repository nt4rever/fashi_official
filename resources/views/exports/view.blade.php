<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Sales</th>
        <th>Profit</th>
        <th>Quantity</th>
        <th>Order</th>
    </tr>
    </thead>
    <tbody>
    @foreach($statistic as $item)
        <tr>
            <td>{{ $item->id_statistical }}</td>
            <td>{{ $item->order_date }}</td>
            <td>{{ $item->sales }}</td>
            <td>{{ $item->profit }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->total_order }}</td>
        </tr>
    @endforeach
    </tbody>
</table>