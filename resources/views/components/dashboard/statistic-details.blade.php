<div class="dashboard__{{ $name }}-list">
    <div class="dashboard__{{ $name }}-logo">
        @if ($listType == 'dot')
            <img src="{{ asset('assets/images/store-list-icon.png') }}" alt="Dot Icon">
        @else
            @if ($list['image'])
                <img src="{{ asset('storage/employee/' . $list['image']) }}" alt="Sales Photo">
            @else
                <img src="{{ asset('assets/images/promotor-default.png') }}" alt="Promotor Photo">
            @endif
        @endif
    </div>
    <div class="dashboard__{{ $name }}-details">
        <div class="dashboard__{{ $name }}-name">
            <span>
                {{ $list['name'] }}
            </span>
        </div>
        <div class="dashboard__{{ $name }}-revenue">
            <span>
                {{ $list['text'] }}
            </span>
        </div>
    </div>
</div>
