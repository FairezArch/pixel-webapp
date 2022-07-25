<div class="modal fade" id="deleteConfirmationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationTitle">Konfirmasi Penghapusan {{ $name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin akan menghapus {{ $name }} ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(document).ready(() => {
            $('body').on('click', '#btnDelete', function() {
                let id = $(this).data('id')

                $('#deleteConfirmationModal button[type=submit]').data('id', id)
            })

            $('#deleteConfirmationModal button[type=submit]').on('click', function(e) {
                e.preventDefault()

                $(this).prop('disabled', true)
                let id = $(this).data('id')

                axios.delete(`/{{ $url }}/${id}`).then((res) => {
                    location.reload()
                })
            })
        })
    </script>
@endpush
