@extends('layouts.admin')

@section('title', 'Employee Details')

@section('content')
    <section class="employee-show">
        <div class="employee-show__wrapper">
            <div class="employee-show__box">
                <div class="employee-show__image">
                    @if ($user->filename)
                        <img src="{{ asset('/storage/employee/' . $user->filename) }}" alt="Employee Image">
                    @else
                        <img src="{{ asset('assets/images/image-default.png') }}" alt="Employee Image">
                    @endif
                </div>
                <div class="employee-show__name">
                    <span>{{ $user->name }}</span>
                </div>
                <div class="employee-show-email">
                    <span>{{ $user->email }}</span>
                </div>
            </div>
            <div class="employee-show__biodata">
                @component('components.employee.double-panel', ['names' => ['No. Telp', 'Regional'], 'icons' =>
                    ['phone-icon', 'regional-panel-icon'], 'texts' => [$user->mobile, $user->store->channel->region->name]])
                @endcomponent
                @component('components.employee.double-panel', ['names' => ['Toko Utama', 'Status'], 'icons' =>
                    ['channel-panel-icon', 'status-panel-icon'], 'texts' => [$user->store->channel->name, $user->status ?
                    'Aktif' : 'Tidak Aktif']])
                @endcomponent
            </div>
        </div>

        <div class="employee-show__activity">
            <ul class="nav nav-tabs" id="activityTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="timer-tab" data-toggle="tab" href="#timer" role="tab"
                        aria-controls="timer" aria-selected="true">Aktivitas Timer</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="sale-input-tab" data-toggle="tab" href="#sale-input" role="tab"
                        aria-controls="sale-input" aria-selected="false">Aktivitas Input Jualan</a>
                </li>
            </ul>
            <div class="tab-content" id="activityContent">
                <div class="tab-pane fade show active" id="timer" role="tabpanel" aria-labelledby="timer-tab">
                    @forelse ($attendance as $list)
                        @component('components.employee.activity', ['name' => $list->user->name, 'photo' =>
                            $list->user->filename, 'time' => $list->clock_in, 'text' => 'Clock in, memulai pekerjaan'])
                        @endcomponent
                        @if ($list->clock_out)
                            @component('components.employee.activity', ['name' => $list->user->name, 'photo' =>
                                $list->user->filename, 'time' => $list->clock_out, 'text' => 'Clock out, mengakhiri pekerjaan'])
                            @endcomponent
                        @endif
                    @empty
                        <div class="empty-list">
                            <span>Tidak ada Aktivitas.</span>
                        </div>
                    @endforelse
                </div>
                <div class="tab-pane fade" id="sale-input" role="tabpanel" aria-labelledby="sale-input-tab">
                    @forelse ($sales as $list)
                        @component('components.employee.activity', ['name' => $list->user->name, 'photo' =>
                            $list->user->filename, 'time' => $list->created_at, 'text' => 'Menjual ' . $list->quantity . ' buah
                            ' . $list->product->name])
                        @endcomponent
                    @empty
                        <div class="empty-list">
                            <span>Tidak ada Aktivitas.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
