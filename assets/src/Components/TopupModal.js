import Modal from "./Modal";
import {__} from "@wordpress/i18n";
import icon_mc from "../../img/mc.png";
import icon_arr_d from "../../img/arr_d.png";
import icon_vs from "../../img/vs.png";
import icon_plus_b from "../../img/plus_b.png";
import icon_arr_rb from "../../img/arr_rb.png";
import icon_i_gr from "../../img/i-gr.png";
import React from "react";

export default function TopupModal({onClose}) {

    return (
        <Modal onClose={ onClose }>
            <Modal.Header onClose={ onClose }>{__('TopUp Wallet')}</Modal.Header>
            <Modal.Content><div className="">
                <p className="merkai-text-gray-500">
                    From
                </p>
                <div className="card-variants">
                    <div className="card-var current-card" data-pan="<?php echo $wallet['card_number']; ?>">
                        <div className="merkai-flex merkai-flex-row merkai-gap-x-4 merkai-items-center">
                            <img src={icon_mc} className="merkai-h-[30px] merkai-w-[30px] merkai-mr-1 merkai-inline-block"/>
                            <p>1356 5674 2352 2373</p>
                        </div>
                        <img src={icon_arr_d} className="merkai-h-[30px] merkai-w-[30px] merkai-inline-block"/>
                    </div>
                    <div className="card-var" data-pan="3727844328348156">
                        <div className="merkai-flex merkai-flex-row merkai-gap-x-4 merkai-items-center">
                            <img src={icon_vs} className="merkai-h-[30px] merkai-w-[30px] merkai-mr-1 merkai-inline-block"/>
                            <p>3727 8443 2834 8156</p>
                        </div>
                        <img src={icon_arr_d} className="merkai-h-[30px] merkai-w-[30px] merkai-inline-block"/>
                    </div>
                    <div className="card-var" data-pan="">
                        <div className="merkai-flex merkai-flex-row merkai-gap-x-4 merkai-items-center">
                            <img data-modal=".paymentMethodModal" src={icon_plus_b}
                                 className="merkai-h-[30px] merkai-w-[30px] merkai-mr-1 merkai-inline-block"/>
                            <p data-modal=".paymentMethodModal">Add new payment method</p>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="source-card" name="source-card" value=""/>

                <div className="top-up-amount-container merkai-mt-8 lg:merkai-mt-12 merkai-flex merkai-flex-row">
                    <div className="merkai-text-3xl">$</div>
                    <input type="number" step="0.01"
                           className="merkai-bg-white merkai-border-0 merkai-text-3xl !merkai-p-0 focus:!merkai-outline-none"
                           name="amount" id="top_up_amount" placeholder="0" value="1000"/>

                </div>
                <div
                    className="top-up-variants merkai-flex merkai-flex-row merkai-items-center merkai-mt-8 lg:merkai-mt-12 merkai-gap-x-2">
                    <a className="merkai-bg-gray-100 merkai-px-4 merkai-py-3 merkai-rounded-lg merkai-text-xl merkai-cursor-pointer"
                       id="variant_1000">
                        $1000
                    </a>
                    <a className="merkai-bg-gray-100 merkai-px-4 merkai-py-3 merkai-rounded-lg merkai-text-xl merkai-cursor-pointer"
                       id="variant_2000">
                        $2000
                    </a>
                    <a className="merkai-bg-gray-100 merkai-px-4 merkai-py-3 merkai-rounded-lg merkai-text-xl merkai-cursor-pointer"
                       id="variant_5000">
                        $5000
                    </a>
                    <a className="merkai-bg-gray-100 merkai-px-4 merkai-py-3 merkai-rounded-lg merkai-text-xl merkai-cursor-pointer"
                       id="variant_10000">
                        $10 000
                    </a>
                    <a className="merkai-bg-gray-100 merkai-px-4 merkai-py-3 merkai-rounded-lg merkai-text-xl merkai-cursor-pointer"
                       id="variant_15000">
                        $15 000
                    </a>
                </div>
                <p className="merkai-flex merkai-flex-row merkai-items-center merkai-mt-4 merkai-gap-x-0.5">
                    The sending bank may charge a fee.<a href="#">Here's how to avoid it.</a>
                    <img src={icon_arr_rb}
                         className="merkai-h-[18px] merkai-w-[18px] merkai-inline-block"/>
                </p>

                <div className="autodeposit merkai-flex merkai-flex-row merkai-items-center merkai-mt-8 lg:merkai-mt-12 merkai-gap-x-2">
                    <img src={icon_i_gr}
                         className="merkai-h-[18px] merkai-w-[16px] merkai-mr-1 merkai-inline-block"/>
                    Autodeposit
                    <div className="toggle-autodeposit">
                        <p>ON</p>
                        <div className="toggler"></div>
                        <p>OFF</p>
                    </div>
                    <input className="hidden" value="0" name="autodeposit" id="autodeposit"/>
                </div>

                <div>
                    <button id="top_up_button"
                            type="button"
                            className="merkai-btn-primary">
                        Top up
                        <svg
                            className="merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle className="merkai-opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path className="merkai-opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            </Modal.Content>
        </Modal>
    );
}