



            <!-- Breadcrumbs -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">My Dashboard</li>
            </ol>

            <!-- Icon Cards -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 mb-3">
                    <button class="btn btn-block" onclick="fetchFromApi(this)">Fetch From Api</button>
                </div>
                <div class="col-xl-3 col-sm-6 mb-3">
                    <button class="btn btn-block" onclick="addProperty()">Add Directly</button>
                </div>

            </div>

            <!-- Example Tables Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Data Table Example
                </div>
                <div class="card-block">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" id="properties" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Property Type</th>
                                    <th>Type</th>
                                    <th>Address</th>
                                    <th>Bedrooms</th>
                                    <th>Bathrooms</th>
                                    <th>Price</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Property Type</th>
                                    <th>Type</th>
                                    <th>Address</th>
                                    <th>Bedrooms</th>
                                    <th>Bathrooms</th>
                                    <th>Price</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            <tbody>
<!--                                <tr>-->
<!--                                    <td>Tiger Nixon</td>-->
<!--                                    <td>System Architect</td>-->
<!--                                    <td>Edinburgh</td>-->
<!--                                    <td>61</td>-->
<!--                                    <td>2011/04/25</td>-->
<!--                                    <td>$320,800</td>-->
<!--                                </tr>-->

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer small text-muted">
                    Updated yesterday at 11:59 PM
                </div>
            </div>
            <!-- Modal HTML -->
            <div id="addPropertyModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Property</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p class="error" id="addPropertyError"></p>
                            <form method="POST" action="" enctype="multipart/form-data" id="propertyForm">
                                <input type="hidden" name="action" value="add">
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-2 col-form-label">County</label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" value="" placeholder="" name="county" maxlength="45">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-2 col-form-label">Country</label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" value="" placeholder="" name="country" maxlength="45">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-2 col-form-label">town</label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" value="" placeholder="" name="town" maxlength="45">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-2 col-form-label">Postal Code</label>
                                    <div class="col-10">
                                        <input class="form-control" type="email" value="" placeholder="" name="postal_code" maxlength="45">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-2 col-form-label">Description</label>
                                    <div class="col-10">
                                        <textarea class="form-control" name="description"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-2 col-form-label">Displayable Address</label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" value="" placeholder="" name="address" maxlength="255">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="example-text-input" class="col-2 col-form-label">Number of bedrooms</label>
                                    <div class="col-10">
                                        <select class="form-control" name="num_bedrooms" required>
                                            <?php
                                            for ($i = 1 ; $i < $maxBedroom ; $i++) {
                                                echo "<option value='".$i."'>".$i."</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="example-text-input" class="col-2 col-form-label">Number of bathrooms</label>
                                    <div class="col-10">
                                        <select class="form-control" name="num_bathrooms" required>
                                            <?php
                                            for ($i = 1 ; $i < $maxBathroom ; $i++) {
                                                echo "<option value='".$i."'>".$i."</option>";
                                            } ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-2 col-form-label">Price</label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" value="" placeholder="" name="price">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-2 col-form-label">Property Type</label>
                                    <div class="col-10">
                                        <select class="form-control" name="property_type_id" required>
                                            <?php
                                            foreach ($propertyTypes as $v) {
                                                echo "<option value='".$v["id"]."'>".$v["title"]."</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-4 col-form-label">For Sale</label>
                                    <div class="col-2">
                                        <input class="form-control" type="radio" value="sale" placeholder="" name="type">
                                    </div>
                                    <label for="example-text-input" class="col-4 col-form-label">For Rent</label>
                                    <div class="col-2">
                                        <input class="form-control" type="radio" value="rent" placeholder="" name="type">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-2 col-form-label">Image</label>
                                    <div class="col-10">
                                        <input class="form-control" type="file" value="" placeholder="" name="image_full">
                                    </div>
                                </div>

<!--                                <div class="form-group row">-->
<!--                                    <div class="col-10">-->
<!--                                        <input type="submit" class="btn btn-primary">-->
<!--                                    </div>-->
<!--                                </div>-->


                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="addFormData(this)">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal HTML -->
            <div id="displayPropertyModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <img src="" class="img-fluid" id="image_full">
                            </div>
                            <div class="row">
                                <div class="col-md-4">property type</div>
                                <div class="col-md-8"><span id="property_type_id"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">county</div>
                                <div class="col-md-8"><span id="county"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">country</div>
                                <div class="col-md-8"><span id="country"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">town</div>
                                <div class="col-md-8"><span id="town"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">description</div>
                                <div class="col-md-8"><span id="description"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">address</div>
                                <div class="col-md-8"><span id="address"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">latitude</div>
                                <div class="col-md-8"><span id="latitude"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">longitude</div>
                                <div class="col-md-8"><span id="longitude"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">bedrooms</div>
                                <div class="col-md-8"><span id="num_bedrooms"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">bathrooms</div>
                                <div class="col-md-8"><span id="num_bathrooms"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">price</div>
                                <div class="col-md-8"><span id="price"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">type</div>
                                <div class="col-md-8"><span id="type"></span></div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
