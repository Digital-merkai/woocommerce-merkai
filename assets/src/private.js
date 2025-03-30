import './public.css';

import Modal from './modal'
import './topUpFormProcess'
import setTopUpBonuses from "./js/setTopUpBonuses";

(( $ ) => {

    let rewardingRules;
    let commissionPercentage;
    let commissionCoefficient;
    let commissionFixed;
    let conversionRate;

    $.ajax({
        url: merkai_object.ajaxurl,
        global: false,
        type: 'GET',
        data: {
            'action': 'merkai_ajax_get_env_structure',
        },
        success: function (wallet_info) {
            console.log(wallet_info);
            commissionFixed = wallet_info.response.wallet_fixed_commission;
            commissionPercentage = wallet_info.response.wallet_percentage_commission / 100;
            commissionCoefficient = 1 - commissionPercentage;

            // Проверяем, существует ли structure
            if (wallet_info.response.structure) {
                conversionRate = wallet_info.response.structure.bonus_conversion_rate;
                rewardingRules = wallet_info.response.structure.rewarding_group.rewarding_rules;
            } else {
                // Значения по умолчанию, если structure === mull
                conversionRate = 1; // или другое значение по умолчанию
                rewardingRules = []; // пустой массив как значение по умолчанию
            }
        },
        error: function(error) {
            console.error('Ошибка AJAX-запроса:', error);
            commissionFixed = 0;
            commissionPercentage = 0;
            commissionCoefficient = 1;
            conversionRate = 1;
            rewardingRules = [];
        }
    });

    /**
     * Adapt rewarding rules to sum values of the same rules
     * @param {array} data
     * @return {*[]}
     */
    const transformRewardingRules = (data) => {
        const result = [];

        if(data) {
            data.forEach(item => {
                let existing = result.find(el =>
                    el.operation_type === item.operation_type &&
                    el.min_amount === item.min_amount &&
                    el.max_amount === item.max_amount
                );

                if (existing) {
                    existing.value += item.value;
                } else {
                    result.push({ ...item });
                }
            });
            return result;
        }
        return null;
    };

    /**
     * Find eligible Operations
     * @param {object} obj
     * @param {number} num
     * @param {string} operationType
     * @return {*}
     */
    const getCurrentRewardRule = (obj, num, operationType) => {
        let totalValue = 0;
        let minAmount = Infinity;
        let maxAmount = -Infinity;
        let value_type;
        let conversion_rate = 1;

        if(obj) {
            obj.forEach(item => {
                if (item.operation_type === operationType && num >= item.min_amount && num <= item.max_amount) {
                    totalValue += item.value;
                    value_type = item.value_type;
                    conversion_rate = item.conversion_rate;
                    if (item.min_amount < minAmount) {
                        minAmount = item.min_amount;
                    }
                    if (item.max_amount > maxAmount) {
                        maxAmount = item.max_amount;
                    }
                }
            });
        }
        return {
            totalValue: value_type === 'percentage' ? totalValue / conversion_rate : totalValue,
            minAmount,
            maxAmount,
            value_type,
            conversion_rate,
        };
    };

    const reducedRules = transformRewardingRules(rewardingRules);

    const calculateReward = (amount, rules, type) => {
        const total_value = getCurrentRewardRule(rules, amount, type).totalValue;
        const value_type = getCurrentRewardRule(rules, amount, type).value_type;
        return value_type === 'percentage' ? Math.floor(amount * (total_value / 100)) : total_value;
    };

    /* Calculating commission amount and bonuses for topup with commission */
    const calculateCommissionAndBonuses = (amount, serv_calculation, operation_type, wallet_balance_check) => {
        getStructureCalculation(amount, operation_type, wallet_balance_check);
    }

    /*Calculating sum for topup with commission*/
    const calculateSumWithCommission = (amount, serv_calculation, operation_type, wallet_balance_check) => {
        if (serv_calculation && serv_calculation === true && operation_type) {
            getStructureCalculation(amount, operation_type, wallet_balance_check);
        } else {
            let sumWithCommission = ( sum * parseFloat(commissionCoefficient) - parseFloat(commissionFixed) );
            sumWithCommission = (Math.round(sumWithCommission * 100) / 100);
            return sumWithCommission;
        }
    }

    const calculateCommission = (sum) => {
        let commission = ( sum * parseFloat(commissionPercentage) + parseFloat(commissionFixed) );
        commission = (Math.round(commission * 100) / 100);
        return commission;
    }
    
    function setBalance (balance, bonus) {

        const balance_containers = $('.merkai-balance-value');
        const bonus_containers = $('.merkai-bonus-value');
        if(balance_containers) {
            balance_containers.html(balance);
        }
        if(bonus_containers) {
            bonus_containers.html(bonus);
        }
    }

    /**
     * Wallet Activation function
     * @param evt
     * @param path
     */
    const topUpWallet = (evt) => {

        if(!$('#top_up_amount').val()) {
            $('.topUpModal .message').html('Please enter amount!');
            return;
        }
        $(evt.target).addClass('merkai-disabled')

        $(`#${evt.target.id} .merkai-spinner`).removeClass('merkai-hidden');

        $.ajax({
            url: merkai_object.ajaxurl,
            type: 'POST',
            data: {
                'action': 'merkai_ajax_top_up',
                'ajax-top-up-nonce': $('#ajax-top-up-nonce').val(),
                'amount': $('#top_up_amount').val(),
                'redirect_url': window.location.href,
            },
            success: function(data){
                if (data.response.status_code === 200 && JSON.parse(data.response.response).schemas.url){
                    window.location.replace(JSON.parse(data.response.response).schemas.url)
                } else {
                    $('.topUpModal .message').html(JSON.parse(data.response.response).schemas.message);
                }
            }
        })
            .error((error) => alert(error.response.response))
            .always(function() {
                $(`#${evt.target.id} .merkai-spinner`).addClass('merkai-hidden');
                $(evt.target).removeClass('merkai-disabled')
            });
    }

    /**
     * Withdraw from Wallet
     */

    const withdrawWallet = (evt) => {

        if(!$('#withdraw_amount').val()) {
            $('.withdrawModal .message').html('Please enter amount!');
            return;
        }
        $(evt.target).addClass('merkai-disabled')

        $(`#${evt.target.id} .merkai-spinner`).removeClass('merkai-hidden');

        const amount = parseFloat($('#withdraw_amount').val());
        const current_balance = parseFloat($('#witdrawForm .merkai-balance-value').text());

        if (current_balance < amount) {
            $('#withdraw_message').text('Sorry, can\'t do ;)');
            $(evt.target).removeClass('merkai-disabled')
            $(`#${evt.target.id} .merkai-spinner`).addClass('merkai-hidden');
        } else {
            $.ajax({
                url: merkai_object.ajaxurl,
                type: 'POST',
                data: {
                    'action': 'merkai_ajax_withdraw',
                    'ajax-withdraw-nonce': $('#ajax-withdraw-nonce').val(),
                    'amount' : parseFloat($('#withdraw_amount').val()),
                },
                success: function(data){
                    if (data.response.status_code === 200){
                        $('#withdraw_amount').val('');
                        $('.withdrawModal .message').text('Success!');
                        updateWalletBalance();
                        updateOrderButtonState();
                        $('.withdrawModal').delay(1000).fadeOut('fast')
                        $('body').removeClass('merkai-modal-open');
                    } else {
                        $('.withdrawModal .message').text('An error occurred. Please reload page and try again!');
                    }
                }
            })
                .error((error) => console.log(error))
                .always(function() {
                    $(`#${evt.target.id} .merkai-spinner`).addClass('merkai-hidden');
                    $(evt.target).removeClass('merkai-disabled')
                });
        }
    }

    /**
     * Get Structure Calculation
     * @param evt
     * @param path
     */
    const getStructureCalculation = (amount, operation_type, wallet_balance_check) => {
        $.ajax({
            url: merkai_object.ajaxurl,
            type: 'GET',
            data: {
                'action': 'merkai_ajax_get_structure_calculation',
                'amount': parseFloat(amount),
                'operation_type': operation_type,
                'wallet_balance_check': wallet_balance_check,
            },
            success: function(data) {
                //console.log(data);
                if (!data.response.error) {
                    let commission = data.response.operations_data[0].commission_amount;
                    let sumWithCommission = data.response.operations_data[0].full_amount;
                    let bonusesToAdd = data.response.operations_data[0].bonuses_amount;

                    if (operation_type == "payment_operation_add_money") {
                        if (bonusesToAdd > 0) {
                            $('#topup_message').html('Your balance will be replenished by $<span id="replenishAmount">' + sumWithCommission + '</span>, commission is $<span id="commissionAmount">' + commission + '</span>. You will get <span id="bonusesCounter">' + bonusesToAdd + '</span> bonuses.');
                        } else {
                            $('#topup_message').html('Your balance will be replenished by $<span id="replenishAmount">' + sumWithCommission + '</span>, commission is $<span id="commissionAmount">' + commission + '</span>.');
                        }
                        $('#topup_message').removeClass('loading');
                    } else if (operation_type == "payment_operation_withdraw") {
                        $('#withdraw_message').html('You will receive a withdrawal for $<span id="withdrawal_amount">' + sumWithCommission + '</span>, commission is $<span id="withdrawal_commission_amount">' + commission + '</span>.');
                        $('#withdraw_message').removeClass('loading');
                    } else if (operation_type == "payment_operation_for_services") {
                        //console.log(data.response);
                        $('#merkai_payment_bonuses').html('You will get additional ' + bonusesToAdd + ' bonuses for this purchase.');
                        $('#merkai_payment_bonuses').removeClass('loading');
                    }
                } else {
                    if (operation_type == "payment_operation_add_money") {
                        $('#topup_message').html('An error has occurred');
                    } else if (operation_type == "payment_operation_withdraw") {
                        $('#withdraw_message').html('An error has occurred');
                    } else if (operation_type == "payment_operation_for_services") {
                        $('#merkai_payment_bonuses').html('An error has occurred');
                    }
                }
            }
        }).error((error) => console.log(error));
    }

    /**
     * Get Structure Calculations without mutation
     * @param amount
     * @param operation_type
     * @param wallet_balance_check
     */
    const getImmatableStructureCalculation = (amount, operation_type, wallet_balance_check) => {
        return $.ajax({
            url: merkai_object.ajaxurl,
            type: 'GET',
            data: {
                'action': 'merkai_ajax_get_structure_calculation',
                'amount': parseFloat(amount),
                'operation_type': operation_type,
                'wallet_balance_check': wallet_balance_check,
            }}).error((error) => console.log(error));
    }

    /**
     * Change inner text for element with sum of rewarding bonuses
     * @param sum
     * @param element
     * @return {Promise<void>}
     */
    async function mutateBonusesValues(sum, element) {
        if(sum && element) {
            let operation_type = element.data('reward') ? element.data('reward') : "payment";
            let rewards_response;
            let bonuses_amount = 0;
            try {
                 if (operation_type === "topup") {
                    rewards_response =  await getImmatableStructureCalculation(sum, 'payment_operation_add_money', false);
                    bonuses_amount = +rewards_response.response.operations_data[0].bonuses_amount;
                } else if (operation_type === "sum") {
                    rewards_response =  await getImmatableStructureCalculation(sum, 'payment_operation_add_money', false);
                    const payment_response = await getImmatableStructureCalculation(sum, 'payment_operation_for_services', false);

                     const bonuses_for_topup = +rewards_response.response.operations_data[0].bonuses_amount;
                     const bonuses_for_payment = +payment_response.response.operations_data[0].bonuses_amount;
                     bonuses_amount = bonuses_for_topup + bonuses_for_payment;
                } else {
                     rewards_response = await getImmatableStructureCalculation(sum, 'payment_operation_for_services', false);
                     bonuses_amount = +rewards_response.response.operations_data[0].bonuses_amount;
                 }
                if (rewards_response.response.status === 200) {
                    if(bonuses_amount > 0) {
                        element.html(bonuses_amount);
                    } else {
                        element.closest('.merkai-bonuses-calculation').remove();
                    }
                } else {
                    element.closest('.merkai-bonuses-calculation').remove();
                }
            } catch(err) {
                element.closest('.merkai-bonuses-calculation').remove();
                console.log(err);
            }
        } else {
            element.closest('.merkai-bonuses-calculation').remove();
        }
    }

    /**
     * Set Wallet Status
     * @param evt
     * @param path
     */
    const setWalletStatus = (evt, status) => {
        $(evt.target).addClass('merkai-disabled')

        $(`.merkai-profile-actions .merkai-spinner`).removeClass('merkai-hidden');

        $.ajax({
            url: merkai_object.ajaxurl,
            type: 'POST',
            data: {
                'action': 'merkai_ajax_set_status',
                'ajax-status-nonce': $('#ajax-status-nonce').val(),
                'status': status,
            },
            success: () => $(window.location.reload())
        })
            .error((error) => alert(error))
            .always(() => $(`.merkai-profile-actions .merkai-spinner`).addClass('merkai-hidden'));
    }

    /**
     * Delete Wallet from User meta
     * @param evt
     * @param path
     */
    const deleteWallet = (evt) => {
        $(evt.target).addClass('merkai-disabled')

        $(`#${evt.target.id} .merkai-spinner`).removeClass('merkai-hidden');

        $.ajax({
            url: merkai_object.ajaxurl,
            type: 'POST',
            data: {
                'action': 'merkai_ajax_delete_wallet',
                'ajax-delete-nonce': $('#ajax-delete-nonce').val(),
            },
            success: (data) => $(window.location.reload())
        })
            .error((error) => console.log(error));
    }

    /**
     * Wallet Balance checker
     */
    function updateWalletBalance() {
        $.ajax({
            url: merkai_object.ajaxurl,
            type: 'POST',
            data: {
                'action': 'merkai_ajax_check_balance',
            },
            success: function(data){
                setBalance(data.response.balance, data.response.bonuses)
            }
        })
    }

    /**
     * Balance polling
     */
    if($('body').hasClass('logged-in')){
        setInterval(() => updateWalletBalance(), 1000)
    }

    function updateOrderButtonState() {
        const place_orderButton = $('#place_order');
        const hidden = ($('.payment_box.payment_method_merkai').is(":hidden"));
        if(place_orderButton && !hidden) {
            $(document.body).trigger('update_checkout');
        }
    }

    function getParameterByName(name, url = window.location.href) {
        name = name.replace(/[\[\]]/g, '\\$&');
        const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    function debounce(func, debounseTime) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), debounseTime);
        };
    }

    const debounceTime = 500;
    const debouncedTopupFunction = debounce((value,balance,card_balance_limit) => {
        if (parseFloat(value) + balance > card_balance_limit) {
            $('#topup_message').html('When topping up the amount $' + value + ' the balance limit will exceed the set value $' + card_balance_limit);
            $('#top_up_button').attr('disabled','true').addClass('disabled');
            $('#topup_message').removeClass('loading');
        } else {
            if (value < parseFloat($('#top_up_amount').attr('min'))) {
                $('#top_up_button').attr('disabled','true').addClass('disabled');
                $('#topup_message').html('Please enter amount more than minimum top up amount.');
                $('#topup_message').removeClass('loading');
            } else {
                $('#top_up_button').removeAttr('disabled').removeClass('disabled');
                calculateCommissionAndBonuses(value, true, 'payment_operation_add_money', false);
            }
        }
    }, debounceTime);

    const debouncedWithdrawFunction = debounce((value,balance,minWithdrawAmount) => {
        if (parseFloat(value) > balance) {
            $('#withdraw_message').html('Insufficient funds. Please check the wallet balance.');
            $('#withdraw_button').attr('disabled','true').addClass('disabled');
            $('#withdraw_message').removeClass('loading');
        } else {
            if (value < minWithdrawAmount) {
                $('#withdraw_button').attr('disabled','true').addClass('disabled');
                $('#withdraw_message').html('Please enter amount more than minimum withdrawal amount.');
                $('#withdraw_message').removeClass('loading');
            } else {
                $('#withdraw_button').removeAttr('disabled').removeClass('disabled');
                calculateCommissionAndBonuses(value, true, 'payment_operation_withdraw', false);
            }
        }
    }, debounceTime);

    // Payment calculations
    const debouncedPaymentCalculation = debounce((total, bonuses, conversion_rate) => {
        let max_bonuses;
        let max_bonuses_in_money;
        let discount;
        let newprice;
        let newpricefield = $('#merkai_after_discount');
        let discountfield = $('#merkai_discount');

        let merkai_payment_bonuses = $('#merkai_payment_bonuses');

        if(bonuses * conversion_rate < total) {
            max_bonuses = bonuses;
            max_bonuses_in_money = bonuses * conversion_rate;
        } else {
            max_bonuses = total / conversion_rate;
            max_bonuses_in_money = total;
        }

        discount = (max_bonuses * conversion_rate * 100 / total).toFixed(2);
        newprice = (total - max_bonuses_in_money).toFixed(2);

        newpricefield.html(newprice);
        discountfield.html(discount);

        calculateCommissionAndBonuses(newprice, true, 'payment_operation_for_services', false);

        if (newprice != 0) {
            merkai_payment_bonuses.show();
        } else {
            merkai_payment_bonuses.hide();
        }

        if (newprice == total) {
            $('.merkai_before_discount').hide();
            $('.merkai_discount').hide();
        } else {
            $('.merkai_before_discount').show();
            $('.merkai_discount').show();
        }
    }, debounceTime);

    $(document).ready(function() {
        Modal.initElements();

        //initiateWebSocket();

        const topUpButton = $("#top_up_button");
        const withdrawButton = $("#withdraw_button");
        const suspendButton = $("#suspend_button");
        const activateButton = $("#reactivate_button");
        const blockButton = $("#block_button");
        const deleteButton = $("#delete_button");

        topUpButton.click((evt) => topUpWallet(evt))
        withdrawButton.click((evt) => withdrawWallet(evt))
        suspendButton.click((evt) => setWalletStatus(evt, 'SUSPEND'))
        activateButton.click((evt) => setWalletStatus(evt, 'ACTIVE'))
        blockButton.click((evt) => setWalletStatus(evt, 'BLOCKED'))
        deleteButton.click((evt) => deleteWallet(evt))

        /**
         * Trigger update checkout when payment method changed
         */
        $('form.checkout').on('change', 'input[name="payment_method"]', function(){
            $(document.body).trigger('update_checkout');
        });

        $('#top_up_amount').on('keyup change', (evt) => {
            let card_balance_limit = parseFloat($('#card_balance_limit').text());
            let balance = parseFloat($('.merkai-balance-value').first().text());
            let value = parseFloat(evt.target.value);
            $('#topup_message').addClass('loading');
            debouncedTopupFunction(value,balance,card_balance_limit);
        })

        $('.top-up-variants > a').click(function() {
            let amount = $(this).get(0).id.replace('variant_','');
            let card_balance_limit = parseFloat($('#card_balance_limit').text());
            let balance = parseFloat($('.merkai-balance-value').first().text());
            $('#top_up_amount').val(amount);
            $('#topup_message').addClass('loading');
            debouncedTopupFunction(amount,balance,card_balance_limit);
        });

        $('#withdraw_amount').on('keyup change', (evt) => {
            let minWithdrawAmount = 1;
            let balance = parseFloat($('.merkai-balance-value').first().text());
            let value = parseFloat(evt.target.value);
            $('#withdraw_message').addClass('loading');
            debouncedWithdrawFunction(value,balance,minWithdrawAmount);
        })

        /**
         * WOOCOMMERCE CHECKOUT SCRIPT
         */

        $(document).on( "updated_checkout", function() {

            Modal.initElements();

            const topUpButton = $("#top_up_button");
            //const withdrawButton = $("#withdraw_button");

            topUpButton.click((evt) => topUpWallet(evt))
            //withdrawButton.click((evt) => withdrawWallet(evt))

            updateWalletBalance();

            // Registration complete congratz //
            const ans = getParameterByName('ans');
            if (ans) {
                $('.woocommerce-notices-wrapper:first-child').prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>')
            }

            // Topup calculations //
            $('#top_up_amount').on('keyup change', (evt) => {
                let card_balance_limit = parseFloat($('#card_balance_limit').text());
                let balance = parseFloat($('.merkai-balance-value').first().text());
                let value = parseFloat(evt.target.value);
                $('#topup_message').addClass('loading');
                debouncedTopupFunction(value,balance,card_balance_limit);
            })
            $('.top-up-variants > a').click(function() {
                let amount = $(this).get(0).id.replace('variant_','');
                $('#top_up_amount').val(amount);
                $('#topup_message').addClass('loading');
                calculateCommissionAndBonuses(amount, true, 'payment_operation_add_money', false);
            });

            // Conversion rate value picker
            const value = $('#bonuses-value');
            const input = $('#bonuses-input');
            const order_total = parseFloat($('.woocommerce-Price-amount').last().text().replace('$', ''));
            //console.log(order_total);
            value.val(input.val());

            $(document).ready(function () {
                $('#merkai_payment_bonuses').addClass('loading');
                debouncedPaymentCalculation(parseFloat(order_total), input.val(), parseFloat(conversionRate));
            });

            input.on('change', function() {
                value.val(input.val());
                $('#merkai_payment_bonuses').addClass('loading');
                debouncedPaymentCalculation(parseFloat(order_total), input.val(), parseFloat(conversionRate));
                checkBalance();
            })
            value.on('change', function() {
                input.val(value.val());
                $('#merkai_payment_bonuses').addClass('loading');
                debouncedPaymentCalculation(parseFloat(order_total), input.val(), parseFloat(conversionRate));
                checkBalance();
            })
            $('input[type=range]').on('input', function () {
                $(this).trigger('change');
            });

            $('.toggle-autodeposit').click(function () {
                $(this).toggleClass('checked');
                if ($(this).hasClass('checked')) {
                    $('input#autodeposit').attr('value','1');
                } else {
                    $('input#autodeposit').attr('value','0');
                };
            });

            //$('#source-card').attr('value',$('.current-card').id);

            $('.card-var').click(function () {
                $('.card-variants').toggleClass('clicked');
                $('.clicked .card-var').click(function() {
                    $('.card-var').removeClass('current-card');
                    $(this).addClass('current-card');
                    $('#source-card').attr('value',$(this).attr('data-pan'));
                });
            });

            /**
             * Hide Place order button if no money
             * @type {define.amd.jQuery|HTMLElement|*}
             */
            function checkBalance() {
                const place_orderButton = $('#place_order');
                const hidden = ($('.payment_box.payment_method_merkai').is(":hidden"));

                if(place_orderButton && !hidden) {
                    const balance_value = parseFloat($('.merkai-balance-value').first().attr('data-balance'));
                    const bonus_value = parseFloat($('.merkai-bonus-value').first().attr('data-bonus'));
                    const order_total = parseFloat($('.woocommerce-Price-amount').last().text().replace('$', ''));
                    const inputed_bonuses_value = parseFloat($('#bonuses-value').val());

                    if ((balance_value + bonus_value * conversionRate) < order_total) {
                        place_orderButton.addClass('merkai-disabled')
                        place_orderButton.text('Please TopUp your Wallet')
                    } else if ((balance_value + bonus_value * conversionRate) >= order_total && (inputed_bonuses_value * conversionRate + balance_value) < order_total) {
                        place_orderButton.addClass('merkai-disabled')
                        place_orderButton.text('Please TopUp your Wallet or use your Bonuses')
                    } else {
                        place_orderButton.removeClass('merkai-disabled')
                        place_orderButton.text('Place order')
                    }
                }
            }

            checkBalance();

            $("#bonuses-value").on("change", function() {
                checkBalance();
            });

            $('input[type="range"].slider-progress').each(function() {
                $(this).css('--value', $(this).val());
                $(this).css('--min', $(this).attr('min') == '' ? '0' : $(this).attr('min'));
                $(this).css('--max', $(this).attr('max') == '' ? '0' : $(this).attr('max'));
                $(this).on('input', function () {
                    $(this).css('--value', $(this).val())
                    $( "#bonuses-value" ).trigger( "change" );
                });
            });

            $('#top_up_amount').on('keyup change', (evt) => {
                let reward = 0;
                let value = parseFloat(evt.target.value);
                if (calculateReward(value, reducedRules, 'payment_operation_add_money') > 0) {
                    reward = calculateReward(value, reducedRules, 'payment_operation_add_money');
                }
                let replenishAmount = calculateSumWithCommission(value);
                let commissionAmount = calculateCommission(value);
                //setTopUpBonuses(evt.target.value, reward, replenishAmount, commissionAmount);

                let card_balance_limit = parseFloat($('#card_balance_limit').text());
                let balance = parseFloat($('.merkai-balance-value').first().text());

                if (parseFloat(evt.target.value) + balance > card_balance_limit) {
                    $('#topup_message').html('When topping up the amount $' + value + ' the balance limit will exceed the set value $' + card_balance_limit);
                    $('#top_up_button').attr('disabled','true').addClass('disabled');
                } else {
                    if (evt.target.value < parseFloat($('#top_up_amount').attr('min'))) {
                        $('#top_up_button').attr('disabled','true').addClass('disabled');
                        $('#topup_message').html('Please enter amount more than minimum top up amount.');
                    } else {
                        $('#top_up_button').removeAttr('disabled').removeClass('disabled');
                        if (reward > 0) {
                            $('#topup_message').html('Your balance will be replenished by $<span id="replenishAmount">' + replenishAmount + '</span>, commission is $<span id="commissionAmount">' + commissionAmount + '</span>. You will get <span id="bonusesCounter">' + reward + '</span> bonuses.');
                        } else {
                            $('#topup_message').html('Your balance will be replenished by $<span id="replenishAmount">' + replenishAmount + '</span>, commission is $<span id="commissionAmount">' + commissionAmount + '</span>.');
                        }
                    }
                }
            })

            // Run recalculation again
            triggerBonusesCalculationCheck();

        });
        // WOOCOMMERCE CHECKOUT SCRIPT END

        // WOOCOMMERCE CARTUPDATE TRIGGER START
        $(document).on( "updated_wc_div", function() {
            triggerBonusesCalculationCheck();
        });

        $('#show_mini_modal').on('click', function() {
            $('.topup_mini_form').toggle();
            $(this).toggleClass('active');
            if ($(this).hasClass('active')) {
                $(this).css('transform','rotate(45deg)');
            } else {
                $(this).css('transform','rotate(0deg)');
            }
        });

        /**
         * Function to run the calculation of the Bonuses
         */
        function triggerBonusesCalculationCheck() {
            $('.calculate_merkai_bonuses').each(function () {
                mutateBonusesValues($(this).data('price'), $(this));
            })
        }

        // Run it on Ready
        triggerBonusesCalculationCheck();

    });
    // READY END

})(jQuery);
