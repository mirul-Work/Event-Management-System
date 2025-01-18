<!-- Manual Entry Form -->
<form action="{{ route('attendees.addManual', $event->id) }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="email">Attendee Email:</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="seat_type">Seat Type:</label>
        <select name="seat_type" id="seat_type" class="form-control" required>
            <option value="regular">Regular</option>
            <option value="vip">VIP</option>
            <option value="vvip">VVIP</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Add Attendee</button>
</form>

<!-- CSV Upload Form -->
<form action="{{ route('attendees.uploadCSV', $event->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="file">Upload Attendees (CSV):</label>
        <input type="file" name="file" id="file" class="form-control" accept=".csv" required>
    </div>
    <button type="submit" class="btn btn-primary">Upload</button>
</form>
