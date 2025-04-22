 <!-- Modal -->
 <div class="modal fade" id="cuModal">
     <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
         <div class="modal-content">
             <form action="{{ route('admin.plans.fdr.save') }}" method="POST" id="abc">
                 @csrf
                 <div class="modal-header">
                     <h5 class="modal-title">@lang('Add Plan')</h5>
                     <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                         <i class="las la-times"></i>
                     </button>
                 </div>
                 <div class="modal-body">
                     <div class="row">

                         <div class="form-group col-lg-6">
                             <label>@lang('Name')</label>
                             <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                         </div>

                         <div class="form-group col-lg-6">
                             <label>@lang('Interest Rate')</label>
                             <div class="input-group">
                                 <input type="number" step="any" name="interest_rate" value="{{ old('interest_rate') }}" class="form-control">
                                 <span class="input-group-text">@lang('%')</span>
                             </div>
                         </div>

                         <div class="form-group col-lg-6">
                             <label>@lang('Installment Interval')</label>
                             <div class="input-group">
                                 <input type="number" name="installment_interval" value="{{ old('installment_interval') }}" class="form-control">
                                 <span class="input-group-text">@lang('Days')</span>
                             </div>
                         </div>

                         <div class="form-group col-lg-6">
                             <label>@lang('Locked Days')</label>
                             <div class="input-group">
                                 <input type="number" name="locked_days" value="{{ old('locked_days') }}" class="form-control">
                                 <span class="input-group-text">@lang('Days')</span>
                             </div>
                         </div>

                         <div class="form-group col-lg-6">
                             <label>@lang('Minimum Amount')</label>
                             <div class="input-group">
                                 <input type="number" step="any" class="form-control" name="minimum_amount" value="{{ old('minimum_amount') }}" required />
                                 <span class="input-group-text"> {{ __(gs()->cur_text) }} </span>
                             </div>
                         </div>

                         <div class="form-group col-lg-6">
                             <label>@lang('Maximum Amount')</label>
                             <div class="input-group">
                                 <input type="number" step="any" class="form-control" name="maximum_amount" value="{{ old('maximum_amount') }}" required />
                                 <span class="input-group-text"> {{ __(gs()->cur_text) }} </span>
                             </div>
                         </div>
                     </div>

                     <div class="final-amount text-center d-none">
                         <h6>
                             <i class="fa fa-info-circle text--primary"></i>
                             @lang('Users will reveive a minimum amount of')
                             <span class="text--primary fw-bold" id="minAmount"></span>
                             @lang('to a maximum amount of')
                             <span class="text--primary fw-bold" id="maxAmount"></span>
                             @lang('every') <span class="text--primary fw-bold" id="perInterval"></span> @lang('days')
                         </h6>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                 </div>
             </form>
         </div>
     </div>
 </div>
