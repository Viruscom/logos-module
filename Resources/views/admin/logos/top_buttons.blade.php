<div class="bg-grey top-search-bar">
    <div class="checkbox-all pull-left p-10 p-l-0">
        <div class="pretty p-default p-square">
            <input type="checkbox" id="checkboxAll-{{$formId}}" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="{{ __('admin.common.mark_demark_all_elements') }}" data-trigger="hover"/>
            <div class="state p-primary">
                <label></label>
            </div>
        </div>
    </div>
    <div class="search pull-left hidden-xs">
        <div class="input-group">
            <input type="text" name="search-{{$formId}}" class="form-control input-sm search-text" placeholder="{{ __('admin.common.search') }}">
            <span class="input-group-btn">
					<button class="btn btn-sm submit"><i class="fa fa-search"></i></button>
				</span>
        </div>
    </div>

    <div class="action-mass-buttons pull-right">
        <a href="{{ route('admin.logos.create', ['path' => Request::segment(4)]) }}" role="button" class="btn btn-lg tooltips green" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.common.create_new') }}">
            <i class="fas fa-plus"></i>
        </a>
        <a href="{{ route('admin.logos.active-multiple', ['active' => 0]) }}" class="btn btn-lg tooltips light-grey-eye mass-unvisible" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.common.deactivate_all_marked_elements') }}">
            <i class="far fa-eye-slash"></i>
        </a>
        <a href="{{ route('admin.logos.active-multiple', ['active' => 1]) }}" class="btn btn-lg tooltips grey-eye mass-visible" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.common.activate_all_marked_elements') }}">
            <i class="far fa-eye"></i>
        </a>
        <a href="{{ route('admin.logos.delete-multiple') }}" class="btn btn-lg red btn-delete-confirm tooltips" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.common.delete_all_marked_elements') }}">
            <i class="fas fa-trash-alt"></i>
        </a>
    </div>
</div>
<script>
    $('#checkboxAll-{{$formId}}').on("click", function (event) {
        if (this.checked) {
            $('table.table-{{$formId}} tbody .checkbox-row').each(function () {
                this.checked = true;
            });
        } else {
            $('table.table-{{$formId}} tbody .checkbox-row').each(function () {
                this.checked = false;
            });
        }
    });
    $(document).ready(function () {
        function filtrateGalleryRows(searched) {
            $('.table-{{$formId}} tbody tr.t-row').each(function () {
                var text = $(this).text().toLowerCase();
                if (text.indexOf(searched) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            if ($('.table-{{$formId}} tbody tr.t-row:visible').length === 0) {
                $('table.table-{{$formId}} tbody tr td.no-table-rows').parent().show();
            } else {
                $('table.table-{{$formId}} tbody tr td.no-table-rows').parent().hide();
            }
        }

        $('input[name="search-{{$formId}}"]').on('keyup', function () {
            var searched = $(this).val().toLowerCase();
            filtrateGalleryRows(searched);
        });
    });
</script>
