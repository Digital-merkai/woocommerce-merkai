import { useState, useEffect } from '@wordpress/element';
import { sprintf, __ } from '@wordpress/i18n';
import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { decodeEntities } from '@wordpress/html-entities';
import { getSetting } from '@woocommerce/settings';

import cashback_ill from "../img/cashback_ill.png"

import ActivationBlock from "./Components/ActivationBlock";
import RegistrationBlock from "./Components/RegistrationBlock";
import TopupModal from "./Components/TopupModal";

const settings = getSetting( 'merkai_data', {} );

const PaymentBlock = ({bonuses, setBonuses}) => {

    const [ isTopupModalOpen, setIsTopupModalOpen ] = useState( false );
    const [ isUser, setIsUser ] = useState( settings.user );

    let max_bonuses;
    if(settings.wallet.balance < settings.cart_total) {
        max_bonuses = settings.wallet.balance;
    } else {
        max_bonuses = settings.cart_total;
    }

    if(!isUser) {
        return <RegistrationBlock />
    }

    return (<div className="merkai-payment-block">
        <div className="merkai-grid merkai-grid-cols-[1fr_1fr] merkai-gap-x-8 merkai-items-stretch">
            <div className="merkai-card-simulator">
                <h3 className="!merkai-mb-0">Merkai.Pay</h3>
                <div className="merkai-flex merkai-flex-row merkai-items-center merkai-text-white merkai-gap-x-8 merkai-text-xl">
                    <div>
                        <p>Balance</p>
                        <p>$<span className="merkai-numbers merkai-balance-value">{settings.wallet.balance}</span></p>
                    </div>
                    <div>
                        <p>Bonuses</p>
                        <p><span className="merkai-numbers merkai-bonus-value">{settings.wallet.bonuses}</span></p>
                    </div>
                </div>

                <div className="merkai-flex merkai-flex-row merkai-items-center merkai-gap-x-2">
                    <a className="btn-blue" onClick={ () => setIsTopupModalOpen(true) } >Add money</a>
                    <a className="btn-white" >Withdraw</a>
                </div>
            </div>
            <div className="merkai-promo-badge">
                <div className={'merkai-grid merkai-grid-cols-2 merkai-gap-4'}>
                    <div>
                        <h3 className="!merkai-mb-0">Ultimate Cashback</h3>
                        <p>Make three purchases and get an increased cashback on everything!</p>
                        <a className="btn-white merkai-absolute merkai-bottom-4 merkai-left-4" href="#">Read more</a>
                    </div>
                    <img src={cashback_ill} className={'object-contain'}/>
                </div>
            </div>
        </div>
        {settings.wallet.bonuses &&
        <div className="merkai-conversion-rate merkai-mt-8">

            <h3>
                How much do you want to pay in bonuses?
            </h3>

            <input type="number" className="input-text short"
                   name="bonuses_value" id="bonuses-value" placeholder=""
                   onChange={(event) => setBonuses(event.target.value)}
                   value={bonuses}
            />
            <input
                type="range"
                name={'bonuses_input'}
                id={'bonuses_input'}
                min="0"
                max={max_bonuses}
                value={bonuses}
                onChange={(event => setBonuses(event.target.value))}/>

        </div>

        }

        <div className="merkai-flex merkai-flex-row merkai-gap-x-4 merkai-mt-8">
            <a href="#">Manage Cards</a>
            <a href="#">History</a>
            <a href="#">Support</a>
            <a href="#">Terms</a>
        </div>
        { isTopupModalOpen && <TopupModal onClose={() => setIsTopupModalOpen(false)} /> }
    </div>);
}

const defaultLabel = __(
    'Merkai Payment',
    'woocommerce-merkai'
);

const label = decodeEntities( settings.title ) || defaultLabel;
/**
 * Content component
 */
const Content = (props) => {
    const { eventRegistration, emitResponse } = props;
    const { onPaymentSetup } = eventRegistration;
    const [ bonuses, setBonus ] = useState(0);

    useEffect( () => {
        const unsubscribe = onPaymentSetup( async () => {
            // Here we can do any processing we need, and then emit a response.
            // For example, we might validate a custom field, or perform an AJAX request, and then emit a response indicating it is valid or not.
            const bonusesValue = bonuses;
            const customDataIsValid = !! bonusesValue.length;

            if ( customDataIsValid ) {
                return {
                    type: emitResponse.responseTypes.SUCCESS,
                    meta: {
                        paymentMethodData: {
                            bonusesValue,
                        },
                    },
                };
            }

            return {
                type: emitResponse.responseTypes.ERROR,
                message: 'There was an error',
            };
        } );
        // Unsubscribes when this component is unmounted.
        return () => {
            unsubscribe();
        };
    }, [
        emitResponse.responseTypes.ERROR,
        emitResponse.responseTypes.SUCCESS,
        onPaymentSetup,
        bonuses
    ] );
    return (
        <PaymentBlock bonuses={bonuses} setBonuses={setBonus} />
    )
};

const DummyContent = () => {
    return (
        <div>Best Payment method ever</div>
    );
}
/**
 * Label component
 *
 * @param {*} props Props from payment API.
 */
const Label = ( props ) => {
    const { PaymentMethodLabel } = props.components;
    return <PaymentMethodLabel text={ label } />;
};

/**
 * Merkai payment method config object.
 */
const Merkai = {
    name: "merkai",
    label: <Label />,
    content: <Content />,
    edit: <DummyContent />,
    canMakePayment: () => true,
    ariaLabel: label,
    supports: {
        features: settings.supports,
    },
};

registerPaymentMethod( Merkai );