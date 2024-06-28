<div class="modal-header">
    <h5 class="modal-title h4" id="exampleModalLabel">Member Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form method="post" action="{{ route('all_member_export') }}" id="export_member">
   @csrf
    
   <div class="modal-body">
      <div class="row member-grid-box">
			<div class="col-12">
				<div class="member-boxes">
					<input type="checkbox" id="select-all" class="form-check-input">
					<label for="select-all">Select/Deselect All</label>
				</div>
			</div>
			<div class="col-12"><br></div>

			<div class="col-md-12">
				<div class="member-boxes">
					<h5>{{ __('Personal Details') }}</h5>
				</div>
			</div>

			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="M.No" class="form-check-input">
					<label for="form-check-label pl-2">M.No</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Registration No" class="form-check-input">
					<label for="form-check-label pl-2">Registration No</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox"name="columns[]" value="Name" class="form-check-input">
					<label for="form-check-label pl-2">Name</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Profile Picture" class="form-check-input">
					<label for="form-check-label pl-2">Profile Picture</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Gender" class="form-check-input">
					<label for="form-check-label pl-2">Gender</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox"name="columns[]" value="Email" class="form-check-input">
					<label for="form-check-label pl-2">Email</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Birth Date" class="form-check-input">
					<label for="form-check-label pl-2">Birth Date</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Mobile No" class="form-check-input">
					<label for="form-check-label pl-2">Mobile No</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]"value="Whatsapp No" class="form-check-input">
					<label for="form-check-label pl-2">Whatsapp No</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Current Address" class="form-check-input">
					<label for="form-check-label pl-2">Current Address</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Parmenant Address" class="form-check-input">
					<label for="form-check-label pl-2">Parmenant Address</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Signature" class="form-check-input">
					<label for="form-check-label pl-2">Signature</label>
				</div>
			</div>
			<div class="col-12"><br></div>

			<div class="col-md-12">
				<div class="member-boxes">
					<h5>{{ __('Work Details') }}</h5>
				</div>
			</div>

			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Department" class="form-check-input">
					<label for="form-check-label pl-2">Department</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Company" class="form-check-input">
					<label for="form-check-label pl-2">Company</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Designation" class="form-check-input">
					<label for="form-check-label pl-2">Designation</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Salary" class="form-check-input">
					<label for="form-check-label pl-2">Salary</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="DA" class="form-check-input">
					<label for="form-check-label pl-2">DA</label>
				</div>
			</div>
			<div class="col-12"><br></div>

			<div class="col-md-12">
				<div class="member-boxes">
					<h5>{{ __('Document Details') }}</h5>
				</div>
			</div>

			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Aadhar Card No" class="form-check-input">
					<label for="form-check-label pl-2">Aadhar Card No</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Aadhar card" class="form-check-input">
					<label for="form-check-label pl-2">Aadhar card</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="PAN No" class="form-check-input">
					<label for="form-check-label pl-2">PAN No</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="PAN Card" class="form-check-input">
					<label for="form-check-label pl-2">PAN Card</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Departmental ID Proof" class="form-check-input">
					<label for="form-check-label pl-2">Departmental ID Proof</label>
				</div>
			</div>
			<div class="col-12"><br></div>

			<div class="col-md-12">
				<div class="member-boxes">
					<h5>{{ __('Other Details') }}</h5>
				</div>
			</div>

			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Nominee Name" class="form-check-input">
					<label for="form-check-label pl-2">Nominee Name</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Nominee Relation" class="form-check-input">
					<label for="form-check-label pl-2">Nominee Relation</label>
				</div>		
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Witness Signature" class="form-check-input">
					<label for="form-check-label pl-2">Witness Signature</label>
				</div>
			</div>
			<div class="col-12"><br></div>

			<div class="col-md-12">
				<div class="member-boxes">
					<h5>{{ __('Bank Details') }}</h5>
				</div>
			</div>

			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Saving Account No" class="form-check-input">
					<label for="form-check-label pl-2">Saving Account No</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Bank Name" class="form-check-input">
					<label for="form-check-label pl-2">Bank Name</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="IFSC code" class="form-check-input">
					<label for="form-check-label pl-2">IFSC code</label>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-6">
				<div class="member-boxes">
					<input type="checkbox" name="columns[]" value="Branch Address" class="form-check-input">
					<label for="form-check-label pl-2">Branch Address</label>
				</div>		
			</div>
			<div class="col-12"><br></div>

			<div id="error-message" class="text-danger"></div>
      </div>
   </div>


   <div class="modal-footer">
      <button type="button" class="btn btn-secondary m-0" data-bs-dismiss="modal">Close</button>
      <button type="submit" id="submitBtn1" class="btn btn-primary btn-md mx-3">{{ __('Member Detail Export') }}</button>
      <div id="loading1" style="display: none;" class="btn btn-outline-light btn-md  mx-3 disabled">{{ __('Loading...') }}</div>
   </div>
</form>

