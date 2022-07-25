@extends('layouts.admin')

@section('title', 'List Pekerjaan')

@section('content')
    <section class="job">
        <div class="job__header">
            <div class="job__title">
                <span>List Pekerjaan</span>
            </div>
            <div class="job__button">
                <a class="btn-add" href="{{ route('job.create') }}">Tambah Pekerjaan</a>
            </div>
            <div class="job__search">
                <div class="search__wrapper">
                    <input type="text" placeholder="Cari..." id="searchjob">
                    <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search Icon">
                </div>
            </div>
        </div>
        <div class="job__body">
            <div class="job__wrapper">
                @forelse ($jobs as $job)
                    <div class="job__list">
                        <div class="job__list-name">
                            <span>{{ $job->name }}</span>
                        </div>
                        <div class="job__list-button">
                            <a href="{{ route('job.edit', ['job' => $job->id]) }}">
                                <img src="{{ asset('assets/images/edit-icon.png') }}" alt="Edit Icon">
                            </a>
                            <a href="javascript:void(0)" data-id="{{ $job->id }}" data-toggle="modal"
                                data-target="#deleteConfirmationModal" id="btnDelete">
                                <img src="{{ asset('assets/images/delete-icon.png') }}" alt="Delete Icon">
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-list">
                        <span>Pekerjaan belum ditambahkan.</span>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection


@section('modal')
<x-others.delete-confirmation-modal name="Pekerjaan" url="job" />
@endsection

@section('script')
    @stack('script')
    <script>
        $('#searchjob').keyup(function() {
            let search = $(this).val().toLowerCase()
            let jobs = $('.job__list')

            Object.keys(jobs).forEach(key => {
                let job = $(jobs[key])
                let value = job.find('.job__list-name span').text().toLowerCase()
                if (!value.includes(search)) {
                    job.css('display', 'none')
                } else {
                    job.css('display', 'flex')
                }
            })
        })
    </script>
@endsection
