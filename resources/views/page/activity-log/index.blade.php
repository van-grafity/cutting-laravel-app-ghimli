@foreach ($activities as $activity)
    <div>
        <p>Description: <strong>{{ ucfirst($activity->description) }}</strong></p>
        <p>Timestamp: {{ $activity->created_at->diffForHumans() }}</p>
        <p>Properties: {{ $activity->properties }}</p>
        <p>Causer: {{ $activity->causer->name }}</p>
    </div>
    <hr>
@endforeach