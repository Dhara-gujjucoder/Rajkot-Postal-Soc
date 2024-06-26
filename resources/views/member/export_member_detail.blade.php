<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Member Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form method="post" action="{{ route('all_member_export') }}" id="export_member">
    @csrf

    <div class="col-md-12">
        <input type="checkbox" id="select-all" class="form-check-input">
        <label for="select-all">Select/Deselect All</label>
    </div><br>

    <div class="col-md-12">
        <h5>{{ __('Personal Details') }}</h5>
    </div>

    <div class="col-md-12">
        <label for="form-check-label pl-2">M.No</label>
        <input type="checkbox" name="columns[]" value="M.No" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Registration No</label>
        <input type="checkbox" name="columns[]" value="Registration No" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Name</label>
        <input type="checkbox"name="columns[]" value="Name" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Profile Picture</label>
        <input type="checkbox" name="columns[]" value="Profile Picture" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Gender</label>
        <input type="checkbox" name="columns[]" value="Gender" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Email</label>
        <input type="checkbox"name="columns[]" value="Email" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Birth Date</label>
        <input type="checkbox" name="columns[]" value="Birth Date" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Mobile No</label>
        <input type="checkbox" name="columns[]" value="Mobile No" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Whatsapp No</label>
        <input type="checkbox" name="columns[]"value="Whatsapp No" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Current Address</label>
        <input type="checkbox" name="columns[]" value="Current Address" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Parmenant Address</label>
        <input type="checkbox" name="columns[]" value="Parmenant Address" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Signature</label>
        <input type="checkbox" name="columns[]" value="Signature" class="form-check-input">
    </div><br>

    <div class="col-md-12">
        <h5>{{ __('Work Details') }}</h5>
    </div>

    <div class="col-md-12">
        <label for="form-check-label pl-2">Department</label>
        <input type="checkbox" name="columns[]" value="Department" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Company</label>
        <input type="checkbox" name="columns[]" value="Company" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Designation</label>
        <input type="checkbox" name="columns[]" value="Designation" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Salary</label>
        <input type="checkbox" name="columns[]" value="Salary" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">DA</label>
        <input type="checkbox" name="columns[]" value="DA" class="form-check-input">
    </div><br>

    <div class="col-md-12">
        <h5>{{ __('Document Details') }}</h5>
    </div>

    <div class="col-md-12">
        <label for="form-check-label pl-2">Aadhar Card No</label>
        <input type="checkbox" name="columns[]" value="Aadhar Card No" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Aadhar card</label>
        <input type="checkbox" name="columns[]" value="Aadhar card" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">PAN No</label>
        <input type="checkbox" name="columns[]" value="PAN No" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">PAN Card</label>
        <input type="checkbox" name="columns[]" value="PAN Card" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Departmental ID Proof</label>
        <input type="checkbox" name="columns[]" value="Departmental ID Proof" class="form-check-input">
    </div><br>

    <div class="col-md-12">
        <h5>{{ __('Other Details') }}</h5>
    </div>

    <div class="col-md-12">
        <label for="form-check-label pl-2">Nominee Name</label>
        <input type="checkbox" name="columns[]" value="Nominee Name" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Nominee Relation</label>
        <input type="checkbox" name="columns[]" value="Nominee Relation" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Witness Signature</label>
        <input type="checkbox" name="columns[]" value="Witness Signature" class="form-check-input">
    </div><br>

    <div class="col-md-12">
        <h5>{{ __('Bank Details') }}</h5>
    </div>

    <div class="col-md-12">
        <label for="form-check-label pl-2">Saving Account No</label>
        <input type="checkbox" name="columns[]" value="Saving Account No" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Bank Name</label>
        <input type="checkbox" name="columns[]" value="Bank Name" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">IFSC code</label>
        <input type="checkbox" name="columns[]" value="IFSC code" class="form-check-input">
    </div>
    <div class="col-md-12">
        <label for="form-check-label pl-2">Branch Address</label>
        <input type="checkbox" name="columns[]" value="Branch Address" class="form-check-input">
    </div><br>

    <div id="error-message" class="text-danger"></div>


    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" id="submitBtn1" class="btn btn-outline-success btn-md float-start my-3">{{ __('Member Detail Export') }}</button>
        <div id="loading1" style="display: none;" class="btn btn-outline-light btn-md float-start my-3 disabled">{{ __('Loading...') }}</div>
    </div>
</form>

