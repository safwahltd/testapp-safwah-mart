<div id="edit-modal" class="modal" tabindex="-1">
    <div class="modal-dialog">

        <form action="" id="edit_form" method="post" class="form-horizontal">

            @csrf
            @method('PUT')


            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="blue bigger"><i class="fas fa-edit"></i> Edit Category </h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">



                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2">
                                    @csrf

                                    <!-- Name -->
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">Name <sup
                                                    class="text-danger">*</sup></span>
                                            <input type="text" class="form-control edit-name" name="name" required
                                                value="{{ old('name') }}" placeholder="Category Name">
                                        </div>

                                        @error('name')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Slug -->
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">Slug <sup
                                                    class="text-danger">*</sup></span>
                                            <input type="text" class="form-control slug edit-slug" name="slug" required
                                                value="{{ old('slug') }}" placeholder="Slug">
                                        </div>

                                        @error('slug')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="btn-group btn-corner">
                        <button class="btn btn-sm" data-dismiss="modal">
                            <i class="ace-icon fa fa-times"></i>
                            Cancel
                        </button>
                        <button class="btn btn-sm btn-success"><i class="fa fa-edit"></i>
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
