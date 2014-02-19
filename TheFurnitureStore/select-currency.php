<?php
include '../../../wp-load.php';


$returnURL 	= $_GET['return'];
$code		= $_GET['code'];

if(isset($code))
{
	session_start();
}

switch ($code) 
{
    case 'USD':
        $_SESSION['currency-code'] = 'USD';
		$_SESSION['currency-name'] = 'US Dollar';
		$_SESSION['currency-rate'] = 1;
        break;
    case 'BHD':
        $_SESSION['currency-code'] = 'BHD';
		$_SESSION['currency-name'] = 'Bahrani Dinar';
		$_SESSION['currency-rate'] = get_option('wps_exr_bhd');
        break;
    case 'EGP':
        $_SESSION['currency-code'] = 'EGP';
		$_SESSION['currency-name'] = 'Egyptian Pound';
		$_SESSION['currency-rate'] = get_option('wps_exr_egp');;
        break;
    case 'JOD':
        $_SESSION['currency-code'] = 'JOD';
		$_SESSION['currency-name'] = 'Jordanian Dinar';
		$_SESSION['currency-rate'] = get_option('wps_exr_jod');;
        break;
    case 'KWD':
        $_SESSION['currency-code'] = 'KWD';
		$_SESSION['currency-name'] = 'Kuwati Dinar';
		$_SESSION['currency-rate'] = get_option('wps_exr_kwd');;
        break;
    case 'LBP':
        $_SESSION['currency-code'] = 'LBP';
		$_SESSION['currency-name'] = 'Lebanese Pound';
		$_SESSION['currency-rate'] = get_option('wps_exr_lbp');;
        break;
    case 'QAR':
        $_SESSION['currency-code'] = 'QAR';
		$_SESSION['currency-name'] = 'Qatari Rial';
		$_SESSION['currency-rate'] = get_option('wps_exr_qar');;
        break;
    case 'SAR':
        $_SESSION['currency-code'] = 'SAR';
		$_SESSION['currency-name'] = 'Saudi Riyal';
		$_SESSION['currency-rate'] = get_option('wps_exr_sar');;
        break;
    case 'SYP':
        $_SESSION['currency-code'] = 'SYP';
		$_SESSION['currency-name'] = 'Syrian Pound';
		$_SESSION['currency-rate'] = get_option('wps_exr_syp');;
        break;
	case 'AED':
		$_SESSION['currency-code'] = 'AED';
		$_SESSION['currency-name'] = 'UAE Dirham';
		$_SESSION['currency-rate'] = get_option('wps_exr_aed');;
		break;
	case 'OMR':
		$_SESSION['currency-code'] = 'OMR';
		$_SESSION['currency-name'] = 'Omani Rial';
		$_SESSION['currency-rate'] = get_option('wps_exr_omr');;
		break;
}



header('Location: '.$returnURL);
?>