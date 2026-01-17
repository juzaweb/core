<div class="modal fade" id="add-folder-modal" tabindex="-1" role="dialog" aria-labelledby="add-folder-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.media.folders.store', [$websiteId]) }}" method="post" class="form-ajax" data-success="add_folder_success">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-folder-modal-label">
                        {{ trans(('Add Folder')) }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('core::translation.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ Field::text(__('core::translation.name'), 'name') }}

                    <input type="hidden" name="folder_id" value="{{ $folderId }}">
                    <input type="hidden" name="disk" value="public">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times"></i> {{ trans('core::translation.close') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-plus"></i> {{ trans('core::translation.add_folder') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
