@extends('main')
@section('content')

<style>
    .modal-center {
        margin-right: 20%;
    }
</style>

<div class="page-wrapper">
    <div class="page-content">

        <!-- Modal -->
        <!-- Modal -->
        <div class="modal fade modal-center" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formModalLabel">Tambah Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3 needs-validation" novalidate="">
                            <div class="col-md-6">
                                <label for="bsValidation1" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="bsValidation1" placeholder="First Name" value="Jhon" required="">
                                <div class="valid-feedback">
                                    Looks good!
                                  </div>
                            </div>
                            <div class="col-md-6">
                                <label for="bsValidation2" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="bsValidation2" placeholder="Last Name" value="Deo" required="">
                                <div class="valid-feedback">
                                    Looks good!
                                  </div>
                            </div>
                            <div class="col-md-12">
                                <label for="bsValidation3" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="bsValidation3" placeholder="Phone" required="">
                                <div class="invalid-feedback">
                                    Please choose a username.
                                  </div>
                            </div>
                            <div class="col-md-12">
                                <label for="bsValidation4" class="form-label">Email</label>
                                <input type="email" class="form-control" id="bsValidation4" placeholder="Email" required="">
                                <div class="invalid-feedback">
                                    Please provide a valid email.
                                  </div>
                            </div>
                            <div class="col-md-12">
                                <label for="bsValidation5" class="form-label">Password</label>
                                <input type="password" class="form-control" id="bsValidation5" placeholder="Password" required="">
                                <div class="invalid-feedback">
                                    Please choose a password.
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="bsValidation6" name="radio-stacked" required="">
                                        <label class="form-check-label" for="bsValidation6">Male</label>
                                      </div>
                                      <div class="form-check">
                                        <input type="radio" class="form-check-input" id="bsValidation7" name="radio-stacked" required="">
                                        <label class="form-check-label" for="bsValidation7">Female</label>
                                      </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="bsValidation8" class="form-label">DOB</label>
                                <input type="date" class="form-control" id="bsValidation8" placeholder="Date of Birth" required="">
                                <div class="invalid-feedback">
                                    Please select date.
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="bsValidation9" class="form-label">Country</label>
                                <select id="bsValidation9" class="form-select" required="">
                                    <option selected="" disabled="" value="">...</option>
                                    <option>One</option>
                                    <option>Two</option>
                                    <option>Three</option>
                                </select>
                                <div class="invalid-feedback">
                                   Please select a valid country.
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="bsValidation10" class="form-label">City</label>
                                <input type="text" class="form-control" id="bsValidation10" placeholder="City" required="">
                                <div class="invalid-feedback">
                                    Please provide a valid city.
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="bsValidation11" class="form-label">State</label>
                                <select id="bsValidation11" class="form-select" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    <option>One</option>
                                    <option>Two</option>
                                    <option>Three</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a valid State.
                                 </div>
                            </div>
                            <div class="col-md-2">
                                <label for="bsValidation12" class="form-label">Zip</label>
                                <input type="text" class="form-control" id="bsValidation12" placeholder="Zip" required="">
                                <div class="invalid-feedback">
                                    Please enter a valid Zip code.
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="bsValidation13" class="form-label">Address</label>
                                <textarea class="form-control" id="bsValidation13" placeholder="Address ..." rows="3" required=""></textarea>
                                <div class="invalid-feedback">
                                    Please enter a valid address.
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="bsValidation14" required="">
                                    <label class="form-check-label" for="bsValidation14">Agree to terms and conditions</label>
                                    <div class="invalid-feedback">
                                        You must agree before submitting.
                                      </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4">Submit</button>
                                    <button type="reset" class="btn btn-light px-4">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="card radius-10">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal">Tambah</button>
                        </h6>
                    </div>
                    <div class="dropdown ms-auto">
                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class="bx bx-dots-horizontal-rounded font-22 text-option"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:;">Action</a>
                            </li>
                            <li><a class="dropdown-item" href="javascript:;">Another action</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                            </li>
                        </ul>
                    </div>
                </div>
               </div>
            <div class="card-body">
            <div class="table-responsive">
              <table class="table align-middle mb-0">
               <thead class="table-light">
                <tr>
                  <th>Product</th>
                  <th>Photo</th>
                  <th>Product ID</th>
                  <th>Status</th>
                  <th>Amount</th>
                  <th>Date</th>
                  <th>Shipping</th>
                </tr>
                </thead>
                <tbody><tr>
                 <td>Iphone 5</td>
                 <td><img src="assets/images/products/01.png" class="product-img-2" alt="product img"></td>
                 <td>#9405822</td>
                 <td><span class="badge bg-gradient-quepal text-white shadow-sm w-100">Paid</span></td>
                 <td>$1250.00</td>
                 <td>03 Feb 2020</td>
                 <td><div class="progress" style="height: 6px;">
                       <div class="progress-bar bg-gradient-quepal" role="progressbar" style="width: 100%"></div>
                     </div></td>
                </tr>

                

                
               </tbody>
             </table>
             </div>
            </div>
        </div>
        

            </div>
        </div>


@endsection