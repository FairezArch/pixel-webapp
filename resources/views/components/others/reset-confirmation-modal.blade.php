<div class="modal fade" id="resetConfirmationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetConfirmationTitle">Konfirmasi Pengaturan Ulang Perangkat
                    {{ $name }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin akan mengatur ulang perangkat {{ $name }} ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Reset</button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(document).ready(() => {
            $('body').on('click', '#btnReset', function() {
                let id = $(this).data('id')

                $('#resetConfirmationModal button[type=submit]').data('id', id)
            })

            $('#resetConfirmationModal button[type=submit]').on('click', function(e) {
                e.preventDefault()

                $(this).prop('disabled', true)
                let id = $(this).data('id')

                axios.put(`/{{ $url }}/${id}/reset-device`).then((res) => {
                    location.href = '/user'
                })
            })
        })
    </script>
@endpush
