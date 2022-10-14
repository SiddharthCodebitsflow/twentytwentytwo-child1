<form action="" method="post">
    <div class="row justify-content-center">
        <!-- <div id="error1"><?= $_POST['error1']; ?></div> -->
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">Registration</div>
                <div class="card-body">
                    <div class="form-group pb-2">
                        <input class="form-control" onkeyup="jQuery('.fst_name_error').html('')" type="text" id="fst_name" name="fst_name" placeholder="Enter Your First Name">
                        <div class="fst_name_error text-danger"></div>
                    </div>
                    <div class="form-group pb-2">
                        <input class="form-control" onkeyup="jQuery('.lst_name_error').html('')" type="text" id="lst_name" name="lst_name" placeholder="Enter Your Last Name">
                        <div class="lst_name_error text-danger"></div>
                    </div>
                    <div class="form-group pb-2">
                        <input class="form-control" onkeyup="jQuery('.mobile_error').html('')" type="tel" id="mobile_number" name="mobile_number" placeholder="Enter Your Mobile Number">
                        <div class="mobile_error text-danger"></div>
                    </div>
                    <div class="form-group pb-2">
                        <input class="form-control" onkeyup="jQuery('.email_error').html('')" type="email" name="email" id="email" placeholder="Enter Your Email">
                        <div class="text-danger email_error"></div>
                    </div>

                    <div class="form-group pb-2">
                        <input class="form-control" onkeyup="jQuery('.password_error').html('')" type="password" id="password" name="password" placeholder="Enter Your Password">
                        <div class="text-danger password_error"></div>
                    </div>
                    <div class="form-group pb-2">
                        <button type="button" class="px-3 py-1 text-decoration-none border border-white rounded-pill text-white register">submit</button>
                    </div>
                </div>
            </div>
        </div>
</form>
