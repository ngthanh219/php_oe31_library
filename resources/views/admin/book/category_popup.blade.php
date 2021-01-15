<div class="overlay"></div>
<div class="tab-content general">
    <div class="tab-pane active" id="settings">
        <form id="form" class="form-horizontal" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="inputName" class="col-sm-3 control-label">
                    {{ trans('category.parent_name') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" name="parent_name" id="parent_name" />
                    <div class="error" id="errorParent"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-3 control-label">
                    {{ trans('category.child_name') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" name="child_name" id="child_name" />
                    <div class="error" id="errorChild"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" id="add"
                        class="btn btn-danger">{{ trans('category.add_submit_button') }}</button>
                    <a class="btn btn-info" id="off-form">{{ trans('category.return') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('js/cate_popup_form.js') }}" defer></script>
