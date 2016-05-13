<?php

/**
 * This is email configuration file.
 *
 * Use it to configure email transports of Cake.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 2.0.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * In this file you set up your send email details.
 *
 * @package       cake.config
 */

/**
 * Email configuration class.
 * You can specify multiple configurations for production, development and testing.
 *
 * transport => The name of a supported transport; valid options are as follows:
 * 		Mail 		- Send using PHP mail function
 * 		Smtp		- Send using SMTP
 * 		Debug		- Do not send the email, just return the result
 *
 * You can add custom transports (or override existing transports) by adding the
 * appropriate file to app/Network/Email.  Transports should be named 'YourTransport.php',
 * where 'Your' is the name of the transport.
 *
 * from =>
 * The origin email. See CakeEmail::from() about the valid values
 *
 */
class EmailConfig {

    public $default = array(
        'transport' => 'Mail',
        'from' => 'you@localhost',
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
    );
    public $passwordRecovery = array(
        'transport' => 'Smtp',
        'from' => 'contato@trueone.com.br',
        'subject' => 'Recuperação de Senha',
        'host' => 'mail.trueone.com.br',
        'port' => 587,
        'timeout' => 30,
        'username' => 'contato@trueone.com.br',
        'password' => 'one88true',
        'client' => null,
        'log' => false,
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
    );
    public $companyLogin = array(
        'transport' => 'Smtp',
        'from' => 'contato@trueone.com.br',
        'subject' => 'Login e Senha de sua empresa',
        'host' => 'mail.trueone.com.br',
        'port' => 587,
        'timeout' => 30,
        'username' => 'contato@trueone.com.br',
        'password' => 'one88true',
        'client' => null,
        'log' => false,
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
    );
    public $userLogin = array(
        'transport' => 'Smtp',
        'from' => 'contato@trueone.com.br',
        'subject' => 'Login e Senha',
        'host' => 'mail.trueone.com.br',
        'port' => 587,
        'timeout' => 30,
        'username' => 'contato@trueone.com.br',
        'password' => 'one88true',
        'client' => null,
        'log' => false,
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
    );
    public $testeAdventa = array(
        'transport' => 'Smtp',
        'from' => 'frasecontato@frasedeserie.esy.es',
        'subject' => 'Mudança de status',
        'host' => 'mx1.hostinger.com.br',
        'port' => 2525,
        'timeout' => 50,
        'username' => 'frasecontato@frasedeserie.esy.es',
        'password' => 'teste1000',
        'client' => null,
        'log' => false,
    );
    public $smtp = array(
        'transport' => 'Smtp',
        'from' => array('site@localhost' => 'My Site'),
        'host' => 'localhost',
        'port' => 25,
        'timeout' => 30,
        'username' => 'user',
        'password' => 'secret',
        'client' => null,
        'log' => false,
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
    );
    public $fast = array(
        'from' => 'you@localhost',
        'sender' => null,
        'to' => null,
        'cc' => null,
        'bcc' => null,
        'replyTo' => null,
        'readReceipt' => null,
        'returnPath' => null,
        'messageId' => true,
        'subject' => null,
        'message' => null,
        'headers' => null,
        'viewRender' => null,
        'template' => false,
        'layout' => false,
        'viewVars' => null,
        'attachments' => null,
        'emailFormat' => null,
        'transport' => 'Smtp',
        'host' => 'localhost',
        'port' => 25,
        'timeout' => 30,
        'username' => 'user',
        'password' => 'secret',
        'client' => null,
        'log' => true,
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
    );

}
