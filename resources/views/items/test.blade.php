<div class="row">
    <div class="col-md-12 bold-labels">
        <!-- Default box -->


        <form method="post" action="http://localhost:8000/admin/position">
            <input type="hidden" name="_token" value="wsMHzOfPp4zlMLh3iZIjSzH0wZs7IVVapsnq2Uy6">

            <!-- load the view from the application if it exists, otherwise load the one in the package -->
            <input type="hidden" name="http_referrer" value="http://localhost:8000/admin/position">


            <div class="card">
                <div class="card-body row">
                    <!-- load the view from type and view_namespace attribute if set -->

                    <!-- text input -->
                    <div class="form-group col-sm-12 required">
                        <label>Name</label>

                        <input type="text" name="name" value="" class="form-control">


                    </div>















                    <!-- load the view from type and view_namespace attribute if set -->

                    <!-- checkbox field -->

                    <div class="form-group col-sm-12">
                        <div class="checkbox">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" data-init-function="bpFieldInitCheckbox" id="status_checkbox">
                            <label class="form-check-label font-weight-normal" for="status_checkbox">Status</label>


                        </div>
                    </div>








                </div>
            </div>





            <div id="saveActions" class="form-group">

                <input type="hidden" name="save_action" value="save_and_back">
                <div class="btn-group" role="group">

                    <button type="submit" class="btn btn-success">
                        <span class="la la-save" role="presentation" aria-hidden="true"></span> &nbsp;
                        <span data-value="save_and_back">Save and back</span>
                    </button>

                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span><span class="sr-only">▼</span></button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="javascript:void(0);" data-value="save_and_edit">Save and edit this item</a>
                            <a class="dropdown-item" href="javascript:void(0);" data-value="save_and_new">Save and new item</a>
                        </div>

                    </div>
                </div>
                <a href="http://localhost:8000/admin/position" class="btn btn-default"><span class="la la-ban"></span> &nbsp;Cancel</a>

            </div>

        </form>
    </div>
</div>
