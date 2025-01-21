<h3>Event Seat Summary</h3>
<table>
    <thead>
        <tr>
            <th>Category</th>
            <th>Total Seats</th>
            <th>Available Seats</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>VIP</td>
            <td>{{ $event->vip_seat_total }}</td>
            <td>{{ $event->vip_seat_available }}</td>
        </tr>
        <tr>
            <td>Regular</td>
            <td>{{ $event->regular_seat_total }}</td>
            <td>{{ $event->regular_seat_available }}</td>
        </tr>
        <tr>
            <td>VVIP</td>
            <td>{{ $event->vvip_seat_total }}</td>
            <td>{{ $event->vvip_seat_available }}</td>
        </tr>
        <tr>
            <td>Total</td>
            <td>{{ $event->total_seats }}</td>
            <td>{{ $event->available_seats }}</td>
        </tr>
    </tbody>
</table>
