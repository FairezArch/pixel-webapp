<div class="dashboard__{{ $name }}-wrapper">
    <div class="dashboard__{{ $name }}-header">
        <span>
            {{ $statisticTitle }}
        </span>
    </div>
    <div class="dashboard__{{ $name }}-body">
        @foreach ($data as $list)
            <x-dashboard.statistic-details :name="$name" :listType="$listType" :list="$list" />
        @endforeach
    </div>
</div>
