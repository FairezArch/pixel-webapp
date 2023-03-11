@extends('layouts.admin')

@section('title', 'Sale List')

@section('link')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <section class="sale">
        <div class="sale__header">
            <div class="sale__title">
                <span>List Penjualan</span>
            </div>
        </div>
        <div class="sale__body">
            <div class="sale__body-top">
                <div>
                    <select name="main_store">
                        <option value="" selected disabled>Pilih Toko Utama</option>
                        @foreach ($main_stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="branch_store" style="width: 100%">
                        <option value="" selected disabled>Pilih Toko Cabang</option>
                    </select>
                </div>
                <div>
                    <input type="date" id="minDateFilter"
                        value="{{ \Carbon\Carbon::today()->startOfMonth()->format('Y-m-d') }}">
                    <span>to</span>
                    <input type="date" id="maxDateFilter"
                        value="{{ \Carbon\Carbon::today()->startOfMonth()->copy()->endOfMonth()->format('Y-m-d') }}">
                </div>
                <div class="sale__search">
                    <div class="search__wrapper">
                        <input type="text" placeholder="Cari..." id="searchSales">
                        <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search Icon">
                    </div>
                </div>
            </div>
            <table id="salesTable"></table>
        </div>
    </section>
@endsection

@can('sales_list')
    @section('script')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(() => {
                let mainStoreSelect = $('select[name=main_store]')
                let branchStoreSelect = $('select[name=branch_store]')
                let minDateFilter = $('#minDateFilter')
                let maxDateFilter = $('#maxDateFilter')

                mainStoreSelect.select2({
                    allowClear: true,
                    placeholder: 'Pilih Toko Utama'
                });
                branchStoreSelect.select2({
                    allowClear: true,
                    placeholder: 'Pilih Toko Cabang'
                });

                $(mainStoreSelect).on('select2:select', async (e) => {
                    let channelID = e.target.value
                    const res = await axios.get(`/branch-store/${channelID}`)
                    const {
                        data
                    } = await res
                    const stores = data.data
                    console.log(stores);

                    $(branchStoreSelect).empty().trigger('change')

                    $(branchStoreSelect).select2({
                        placeholder: 'Pilih Toko Cabang',
                        data: stores,
                        allowClear: true
                    })

                    $(branchStoreSelect).val('').trigger('change')
                })

                $(mainStoreSelect).on('select2:clear', () => {
                    $(branchStoreSelect).empty().trigger('change')
                })

                const salesTable = $('#salesTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('sale.index') }}",
                        data: function(d) {
                            d.mainStore = mainStoreSelect.val()
                            d.branchStore = branchStoreSelect.val()
                            d.minDate = minDateFilter.val();
                            d.maxDate = maxDateFilter.val();
                        },
                    },
                    columns: [
                        {
                            data: 'user.name',
                            name: 'user.name',
                            title: 'Nama Sales'
                        },
                        {
                            data: null,
                            render: (data) => {
                                if(Object.keys(data.store).length === 0 && data.store.length === 0) {
                                    return '';
                                }else{
                                    return data.store.name;
                                }
                            },
                            name: 'store.name',
                            title: 'Toko Cabang'
                        },
                        {
                            data: null,
                            render: (data) => {
                                if((Object.keys(data.store).length === 0 && data.store.length === 0) || (Object.keys(data.store.channel).length === 0 && data.store.length.channel === 0)) {
                                    return '';
                                }else{
                                    return data.store.channel.region.name;
                                }
                            },
                            name: 'store.channel.region.name',
                            title: 'Daerah'
                        },
                        {
                            data: null,
                            render: (data) => {
                                if((Object.keys(data.store).length === 0 && data.store.length === 0) || (Object.keys(data.store.channel).length === 0 && data.store.length.channel === 0)) {
                                    return '';
                                }else{
                                    return data.store.channel.name;
                                }
                            },
                            name: 'store.channel.name',
                            title: 'Toko Utama'
                        },
                        {
                            data: 'nominal',
                            name: 'nominal',
                            title: 'Nominal'
                        },
                        {
                            data: 'quantity',
                            name: 'quantity',
                            title: 'Jumlah'
                        }
                    ]
                })

                mainStoreSelect.on('change', (e) => {
                    let value = e.target.value
                    salesTable.draw()
                })

                branchStoreSelect.on('change', (e) => {
                    let value = e.target.value
                    salesTable.draw()
                })

                $('#searchSales').keyup((e) => {
                    let value = e.target.value.toLowerCase()
                    salesTable.search(value).draw()
                })

                $(minDateFilter).on('change', (e) => {
                    let value = e.target.value
                    salesTable.draw()
                })

                $(maxDateFilter).on('change', (e) => {
                    let value = e.target.value
                    salesTable.draw()
                })
            })
        </script>
    @endsection
@endcan
