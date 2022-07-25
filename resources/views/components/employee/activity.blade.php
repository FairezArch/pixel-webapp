<div class="activity-list">
    <div class="activity-list__image">
        <img src="{{ asset('/storage/employee/' . $photo) }}" alt="Employee Image">
    </div>
    <div class="activity-list__details">
        <div class="activity-list__header">
            <div class="activity-list__name">
                <span>{{ $name }}</span>
            </div>
            <div class="activity-list__time">
                <span>{{ \Carbon\Carbon::parse($time)->diffForHumans() }}</span>
            </div>
        </div>
        <div class="activity-list__body">
            <span>{{ $text }}</span>
        </div>
    </div>
</div>
