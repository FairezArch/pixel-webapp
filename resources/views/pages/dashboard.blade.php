@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <section class="dashboard">
        {{-- <x-dashboard.clock /> --}}

        <x-dashboard.line-chart chartTitle="Penjualan" name="sale" />
        <x-dashboard.line-chart chartTitle="Keramaian" name="busy" />
        <x-dashboard.bar-chart chartTitle="Produk" name="product" />

        <div class="dashboard__statistic-wrapper">
            @component('components.dashboard.statistic', ['statisticTitle' => 'Toko Terbaik', 'name' => 'store', 'listType'
                => 'dot', 'data' => $bestStores])
            @endcomponent
            @component('components.dashboard.statistic', ['statisticTitle' => 'Sales Terbaik', 'name' => 'promotor',
                'listType' => 'image', 'data' => $bestEmployees])
            @endcomponent
        </div>
    </section>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('script')
@endsection
