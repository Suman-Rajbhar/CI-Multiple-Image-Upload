<div class="section section-breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Create Slider</h1>
            </div>
        </div>
    </div>
</div>
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>            
            <!-- Blog Post Excerpt -->
            <div class="col-sm-12">

                <form class="form-horizontal" role="form" action="<?php echo base_url(); ?>admin_dashboard/create_multiple_image" enctype="multipart/form-data"  method="post">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Blog Image</label>
                        <div class="col-sm-10 p1">
                            <input type="file" name="slide_img[]" class="form-control" id="inputEmail3">
                        </div>
                        <p><br><br><label class="btn btn-default pull-right" id="btn1">Add More</label></p>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Save Slider</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
