import kopybara_logo from "../../img/kopybara-logo.png";

export default function RegistrationBlock() {
    return(
        <section className="merkai">
            <div className="merkai-embleme">
                <img
                    src={kopybara_logo}
                    className="!merkai-block !merkai-mx-auto"/>
            </div>
            <div id="merkai_auth_block" className="merkai-max-w-4xl merkai-mx-auto merkai-mb-4">
                <div className="merkai_login_form visible">
                    <h2 className="merkai-text-center">Please, login before contunue!</h2>
                    <p className="merkai-mb-8 merkai-text-center">Not registered? <a className="form-toggle-a">Sign up!</a>
                    </p>
                    <form name="loginform" id="merkai_loginform" action="/wp-login.php" method="post">
                        <div className="row">
                            <div className="col">
                                <label htmlFor="log">Login</label>
                                <input type="text" name="log" className="merkai_input" id="merkai_user_login"/>
                            </div>
                            <div className="col">
                                <label htmlFor="pwd">Password</label>
                                <input type="password" name="pwd" className="merkai_input"
                                       id="merkai_user_pass"/>
                            </div>
                        </div>
                        <p className="row">
                            <label><input name="rememberme" type="checkbox" id="merkai_rememberme"
                                          value="forever"/> Remember me</label>
                        </p>
                        <p className="row">
                            <input type="submit" name="wp-submit" id="merkai_wp-submit"
                                   className="merkai_button" value="Log in"/>
                            <input type="hidden" name="cfps_cookie" value="1"/>
                        </p>
                    </form>
                </div>

                <div className="merkai_register_form">
                    <h2 className="merkai-text-center">Join Us for your convenience!</h2>
                    <p className="merkai-text-center merkai-mb-8">Already registered? <a className="form-toggle-a">Log
                        in!</a></p>
                    <form name="registerform" id="registerform" noValidate="novalidate" action={'/wp-login.php?action=register'} method={'post'}>
                        <div className="row">
                            <div className="col">
                                <label htmlFor="user_login" className="for_input">Username</label><br/>
                                <input type="text" name="user_login" id="user_login" className="merkai_input"
                                       value="" autoCapitalize="off" autoComplete="username" required="required" />
                            </div>
                            <div className="col">
                                <label htmlFor="user_email" className="for_input">Email</label><br/>
                                <input type="email" name="user_email" id="user_email" className="merkai_input"
                                       value="" autoComplete="email" required="required" />
                            </div>
                        </div>
                        <p id="reg_passmail" className="row">
                            Registration confirmation will be emailed to you.
                        </p>
                            <p className="submit row">
                                <input type="submit" name="wp-submit" id="wp-submit" className="merkai_button"
                                       value="Register" />
                            </p>
                    </form>
                </div>
            </div>
        </section>
    );
}