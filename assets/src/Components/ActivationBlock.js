import debit_card_example from "../../img/debit_card_example.png";
import icon_tickets from "../../img/icon_tickets.svg";
import icon_dollar from "../../img/icon_dollar.svg";
import icon_coupon from "../../img/icon_coupon.svg";
import icon_magic from "../../img/icon_magic.svg";

const ActivationBlock = () => {
    return (
        <section className="merkai">
            <div className="article-body merkai-max-w-4xl merkai-mx-auto merkai-mt-4">
                <div className="merkai-mb-10 lg:merkai-mb-20">
                    <img
                        className="merkai-block !merkai-mx-auto"
                        src={debit_card_example}
                        alt=""/>
                </div>
                <div className="merkai-grid merkai-grid-cols-[40px_1fr] merkai-gap-x-6 merkai-gap-y-12 merkai-mb-10">
                    <div>
                        <img
                            src={icon_tickets}
                            alt=""/>
                    </div>
                    <div>
                        <h2 className="merkai-h2 merkai-mb-0">Fly on the right day. Always.</h2>
                        <p className="merkai-text-base">We will book a ticket and notify you in advance if there is a seat
                            available on the plane. Refund the money for the ticket if you do not fly.</p>
                    </div>
                    <div>
                        <img
                            src={icon_dollar}
                            alt=""/>
                    </div>
                    <div>
                        <h2 className="merkai-h2 merkai-mb-0">Ultimate cashback</h2>
                        <p className="merkai-text-base">Make 3 purchases and get an increased cashback on everything</p>
                    </div>
                    <div>
                        <img
                            src={icon_coupon}
                            alt=""/>
                    </div>
                    <div>
                        <h2 className="merkai-h2 merkai-mb-0 merkai-tab-selector">Pay with bonuses</h2>
                        <p className="merkai-text-base">Make 5 purchases and get 500 bonuses that can be spent on
                            flights.</p>
                    </div>
                    <div>
                        <img
                            src={icon_magic}
                            alt=""/>
                    </div>
                    <div>
                        <h2 className="merkai-h2 merkai-mb-0">Unique card design from AI</h2>
                        <p className="merkai-text-base">Our AI will generate your individual unique map design for
                            you.</p>
                    </div>
                </div>
                <div className="merkai-flex merkai-justify-center merkai-mb-10">
                    <button id="merkai_activation_button"
                            type="button"
                            className="merkai-btn-primary">
                        Activate Merkai.Pay
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

                <div className="merkai-pl-10 merkai-pb-6 merkai-text-center">
                    <p className="merkai-text-slate-500">I agree to <a href="#"
                                                                     className="merkai-text-slate-500 merkai-underline">Merkai
                        Terms & Conditions</a> and <a href="#" className="merkai-text-slate-500 merkai-underline">Rules of
                        Merkai.Pay Priority program</a></p>
                </div>
            </div>
        </section>
    );
}

export default ActivationBlock;