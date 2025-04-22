@push('script')
    <script>
        "use strict";
        (function($) {
            let gsCurText = "{{ __(gs()->cur_text) }}";

            $('[name=country_id]').on('change', function() {
                initializeFields();
                setCallingCodes();
                showOperatorsModal($(this).val());
            });

            @if (old('country_id'))
                $('[name=country_id]').trigger("change");
            @endif

            $(document).on('change', '[name=suggested_amount]', function() {
                $('[name=amount]').val($(this).val());
            });

            $('.confirmOperatorBtn').on('click', function() {
                let modal = $('#operatorsModal');
                let operator = modal.find('[name=country_operator_id]:checked').data();
                updateSelectedOperator(operator);
                modal.modal('hide');
            });

            $(document).on('click', '.changeOperatorBtn', function() {
                showOperatorsModal($(this).data('country_id'));
            });

            function initializeFields() {
                $('.operatorDiv').find('.operator-wrapper').html('');
                hideElement('operatorDiv');

                @if (!old('mobile_number'))
                    resetVal('mobileNumber');
                @endif

                $('.fixed-amount-input-wrapper').empty();
                hideElement('fixed-amounts-wrapper');
                $(".suggested-amounts").empty();
                hideElement('suggested-amounts-wrapper');

                $('.topupLimit').empty();
                hideElement('topupLimit');
            }

            function setCallingCodes() {
                let callingCodes = $("[name=country_id]").find(":selected").data('calling_codes');
                var options = '';

                if (Array.isArray(callingCodes)) {
                    $.each(callingCodes, function(i, elem) {
                        options += `<option value="${elem}">${elem}</option>`;
                    });
                } else {
                    options += `<option value="${callingCodes}">${callingCodes}</option>`;
                }

                $('[name=calling_code]').html(options);
            }

            function showOperatorsModal(countryId) {
                if (!countryId) {
                    return false;
                }

                let oldOperatorId = "{{ old('operator_id') }}";
                let oldOperator = null;

                let modal = $('#operatorsModal');
                modal.find('.modal-preloader').removeClass('d-none');
                modal.modal('show');

                var allOperators = '';
                var onlyRechargeOperators = '';
                var bundleOperators = '';
                var dataOperators = '';
                var pinOperators = '';

                $.ajax({
                    type: "GET",
                    url: "{{ route('user.airtime.country.operators', '') }}/" + countryId,
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {
                            $.each(response.operators, function(index, operator) {
                                if (oldOperatorId && operator.id == oldOperatorId) {
                                    oldOperator = operator;
                                    return;
                                }
                                var dataProperties = makeDataKeyValuePair(operator);
                                var html = `<div class="single-operator">
                                    <input name="country_operator_id"  type="radio" value="${operator.id}" ${dataProperties}>
                                    <div class="logo">
                                        <img src="${operator.logo_urls[0]}" alt="${operator.name}">
                                    </div>
                                    <div class="desc-wrapper">
                                        <h4 class="title">${operator.name}</h4>
                                    </div>
                                </div>`;


                                allOperators += html;

                                if (operator.bundle == 1) {
                                    bundleOperators += html;
                                }
                                if (operator.data == 1) {
                                    dataOperators += html;
                                }

                                if (operator.pin == 1) {
                                    pinOperators += html;
                                }

                                if (operator.bundle == 0 && operator.data == 0 && operator.pin == 0) {
                                    onlyRechargeOperators += html;
                                }

                            });

                            if (oldOperator) {
                                updateSelectedOperator(oldOperator);
                                return true;
                            }

                            modal.find('.operator-wrapper.all').html(allOperators);

                            updateOperatorTabPanel(onlyRechargeOperators, 'recharge');
                            updateOperatorTabPanel(bundleOperators, 'bundle');
                            updateOperatorTabPanel(dataOperators, 'data');
                            updateOperatorTabPanel(pinOperators, 'pin');

                            modal.find('.nav-link.all').tab('show');
                            modal.find('.modal-preloader').addClass('d-none');
                        }
                    }
                });
            }

            function updateOperatorTabPanel(html, target) {
                let modal = $('#operatorsModal');
                if (html == '') {
                    modal.find(`.nav-item.${target}`).addClass('d-none');
                    modal.find(`.tab-pane.${target}`).addClass('d-none');
                } else {
                    modal.find(`.nav-item.${target}`).removeClass('d-none');
                    modal.find(`.tab-pane.${target}`).removeClass('d-none');
                    modal.find(`.operator-wrapper.${target}`).html(html);
                }
            }

            function setElementByDenominationType(data) {
                if (data.denomination_type == 'FIXED') {
                    resetVal('amount');
                    $('[name=amount]').removeAttr('required');
                    hideElement('amount-wrapper');

                    $('.topupLimit').text('');
                    hideElement('topupLimit');

                    setFixedAmountInputs();
                } else {
                    $('.fixed-amount-input-wrapper').html('');
                    hideElement('fixed-amounts-wrapper');
                    showElement('amount-wrapper');
                    $('[name=amount]').attr('required', true);
                    showTopUpLimit();
                }
            }

            function showTopUpLimit() {
                var data = $('[name=operator_id]:checked').data();
                var minAmount = data.min_amount;
                var maxAmount = data.max_amount;

                if (minAmount && maxAmount) {
                    $('.topupLimit').text(`(@lang('Minimum amount') ${minAmount} ${gsCurText} & @lang('Maximum amount') ${maxAmount} ${gsCurText})`);
                    showElement('topupLimit');
                }

            }

            function showSuggestedAmounts(local = false) {
                var data = $('[name=operator_id]:checked').data();

                if (data.suggested_amounts.length < 1) {
                    $('.suggested-amounts').html('');
                    hideElement('suggested-amounts-wrapper');
                    return false;
                }
                var html = '';

                $.each(data.suggested_amounts, function(key, value) {
                    var amount = value;

                    html += `<div class="suggested-amount-item">
                        <input type="radio" name="suggested_amount" id="uid-${key}" value="${amount}">
                        <label class="amount" for="uid-${amount}">${amount} ${gsCurText}</label>
                    </div>`;
                });

                $('.suggested-amounts').html(html);
                showElement('suggested-amounts-wrapper');
            }

            function setFixedAmountInputs(local = false) {
                var data = $('[name=operator_id]:checked').data();

                if (data.denomination_type != 'FIXED') {
                    $('.fixed-amount-input-wrapper').html('');
                    hideElement('fixed-amounts-wrapper');
                    return false;
                }

                var fixedAmounts = local ? (jQuery.isEmptyObject(data.local_fixed_amounts_descriptions) ? data.local_fixed_amounts : data.local_fixed_amounts_descriptions) : (jQuery.isEmptyObject(data.fixed_amounts_descriptions) ? data.fixed_amounts : data.fixed_amounts_descriptions);


                var hasDesc = !jQuery.isEmptyObject(local ? data.local_fixed_amounts_descriptions : data.fixed_amounts_descriptions);

                if (typeof fixedAmounts === "object" && !Array.isArray(fixedAmounts)) {
                    if (Object.keys(fixedAmounts).length < 1) {
                        $('.fixed-amount-input-wrapper').html('');
                        hideElement('fixed-amounts-wrapper');
                        return false;
                    }
                } else if (Array.isArray(fixedAmounts)) {
                    if (fixedAmounts.length < 1) {
                        $('.fixed-amount-input-wrapper').html('');
                        hideElement('fixed-amounts-wrapper');
                        return false;
                    }
                }

                let html = ``;
                $.each(fixedAmounts, function(key, value) {
                    html += `<div class="fixed-amount-item">
                        <input type="radio" name="amount" id="uid-${key}" value="${hasDesc ? key : value}" required>
                        <label class="amount" for="uid-${key}">${hasDesc ? key : value} ${gsCurText}</label>
                        ${hasDesc ? `<p class="description">${value}</p>` : ''}
                    </div>`;
                });

                $('.fixed-amount-input-wrapper').html(html);
                showElement('fixed-amounts-wrapper');
            }

            function updateSelectedOperator(operator) {
                var dataProperties = makeDataKeyValuePair(operator);
                $(".operatorDiv").find('.operator-wrapper').html(`
                    <div class="single-operator">
                        <input name="operator_id" type="radio" value="${operator.id}" ${dataProperties} checked>
                        <div class="logo">
                            <img src="${operator.logo_urls[0]}" alt="${operator.name}">
                        </div>
                        <div class="description d-flex justify-content-between align-items-center">
                            <div class="title">${operator.name}</div>
                            <button type="button" class="btn btn--base btn--sm btn-sm changeOperatorBtn" data-country_id="${operator.country_id}">@lang('Change')</button>
                        </div>
                    </div>`);

                @if (!old('amount'))
                    resetVal('amount');
                @endif

                showElement('operatorDiv');
                hideElement('summary');

                setElementByDenominationType(operator);

                var hasSuggestedAmount = 0;
                if (operator.suggested_amounts != null) {
                    if (operator.suggested_amounts.length) {
                        hasSuggestedAmount = 1;
                    }
                } else {
                    hasSuggestedAmount = 0;
                }

                if (!hasSuggestedAmount) {
                    $(".suggested-amounts").html('');
                    hideElement('suggested-amounts-wrapper');
                } else {
                    showSuggestedAmounts();
                }
            }

            function makeDataKeyValuePair(obj) {
                let data = '';
                delete obj.created_at;
                delete obj.updated_at;

                for (const key in obj) {
                    if (typeof(obj[key]) == 'object') {
                        var value = JSON.stringify(obj[key]).replace(/"/g, '&quot;');
                        data += `data-${key}="${value}" `;
                    } else {
                        data += `data-${key}="${obj[key]}" `;
                    }

                }

                return data;
            }

            function resetVal(elem) {
                $(`.${elem}`).val('');
            }

            function hideElement(elem) {
                $(`.${elem}`).addClass('d-none');
            }

            function showElement(elem) {
                $(`.${elem}`).removeClass('d-none');
            }

            let operatorsModal = document.getElementById('operatorsModal');
            operatorsModal.addEventListener('hidden.bs.modal', function(event) {
                if (!$('[name=operator_id]').val()) {
                    $('[name=country_id]').val('').select2();
                }
            });

        })(jQuery)
    </script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
