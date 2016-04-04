<html>
<body><?php

require_once('nusoap.php');
//$soapclient = new soapclient('http://www.metastash.com/webservices/stashit.asmx?WSDL','wsdl');
$soapclient = new soapclient('http://www.metastash.com/webservices/stashit.asmx');
//$soap_proxy = $soapclient->getProxy();
$params = array (
	'strUserName'    => 'kemmott',
	'strPassword'    => 'asbestos'
);

//$a = $soap_proxy->getBookmarks($params);
$a = $soapclient->call('getBookmarks',$params,'nu','http://tempuri.org/getBookmarks');

echo 'Request: <xmp>'.$soapclient->request.'</xmp>';
echo 'Response: <xmp>'.$soapclient->response.'</xmp>';
echo 'Debug log: <pre>'.$soapclient->debug_str.'</pre>';

print_r($a);


?>
</body>
</html>