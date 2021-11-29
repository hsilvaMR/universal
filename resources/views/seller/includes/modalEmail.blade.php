<div class="ModalEmailAlternativo">
  <div class="av-100 av-amarelo font16">
    <label class="margin-bottom0">{{ trans('site_v2.Validate_txt') }} <label class="av-tx-amarelo margin-bottom0">{{ $seller->email_alteracao }}</label>, {{ trans('site_v2.Validate_conclude_txt') }} <label class="resendEmail margin-bottom0">{{ trans('site_v2.Resend_Email_txt') }} <label class="av-here" onclick="resendValitionEmail();">{{ trans('site_v2.here') }}</label>.</label></label>
    <label class="margin-bottom0" onclick="cancelValitionEmail();">{{ trans('site_v2.Validate_change_email_txt') }} <label class="av-here">{{ trans('site_v2.here') }}</label>.</label>
  </div>
</div>
