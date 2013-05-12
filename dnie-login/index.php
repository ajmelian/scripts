<?php

function getDNI() {
  $dn_piece=preg_split('/\//',$_SERVER['SSL_CLIENT_S_DN']);
  foreach($dn_piece as $x => $tkv){
    list($key,$value)=preg_split('/=/',$tkv);
    if($key=="serialNumber")
      return $value;
  }
  return null;
}

function getApellidos() {
  $dn_piece=preg_split('/\//',$_SERVER['SSL_CLIENT_S_DN']);
  foreach($dn_piece as $x => $tkv){
    list($key,$value)=preg_split('/=/',$tkv);
    if($key=="CN") {
      list($key2,$value2)=preg_split('/,/',$value);
      return $key2;
    }
  }
  return null;
}

function verify() {
	$a = rand(1000,99999);
	$dir="/tmp/dnicert_";
	$cert_ca="/etc/apache2/sslkeys/autoridad-dnie/ACRAIZ-SHA2.pem";
	file_put_contents($dir.$a.'cert_c.pem',$_SERVER['SSL_CLIENT_CERT']);

	$output = shell_exec('openssl x509 -in '.$dir.$a.'cert_c.pem'.' -issuer -noout');
	if (strpos($output,"CN=AC DNIE 001"))
	$issuer_cert = "/etc/apache2/sslkeys/autoridad-dnie/ACDNIE001-SHA2.pem";
	else if (strpos($output,"CN=AC DNIE 002"))
	$issuer_cert = "/etc/apache2/sslkeys/autoridad-dnie/ACDNIE002-SHA2.pem";
	else if (strpos($output,"CN=AC DNIE 003"))
	$issuer_cert = "/etc/apache2/sslkeys/autoridad-dnie/ACDNIE003-SHA2.pem";

	$output = shell_exec('openssl ocsp -CAfile '.$cert_ca.' -issuer '.$issuer_cert.' -cert '.$dir.$a.'cert_c.pem -url http://ocsp.dnie.es');
	shell_exec('rm -f '.$dir.$a.'cert_c.pem');

	$output_parsed = preg_split('/[\r\n]/', $output);
	$output_parsed2 = preg_split('/: /', $output_parsed[0]);
	$ocsp = $output_parsed2[1];

	return $ocsp;
}

$ocsp=verify();

#echo "Estado del certificado: ".$ocsp; #good,revoked,unknown
#echo "<p>Pais: ". $_SERVER['SSL_CLIENT_S_DN_C']."</p>";
#echo "<p>DNI: ". getDNI()."</p>";
#echo "<p>Nombre: ".$_SERVER['SSL_CLIENT_S_DN_G']." ".getApellidos()."</p>";
#echo "<p>Autoridad: ".$_SERVER['SSL_CLIENT_I_DN_O']." (".$_SERVER['SSL_CLIENT_I_DN_OU'].") [".$_SERVER['SSL_CLIENT_I_DN_CN']."]</p>";
#echo "<p>Numero de serie: ". $_SERVER['SSL_CLIENT_M_SERIAL']."</p>";
#echo "<p>Valido desde: ". $_SERVER['SSL_CLIENT_V_START']." hasta: ".$_SERVER['SSL_CLIENT_V_END']."</p>";
#echo "Dias restantes de validez: ". $_SERVER['SSL_CLIENT_V_REMAIN'];
#echo "<p><b>Certificado</b><br/><textarea cols=\"80\" rows=\"10\">".$_SERVER['SSL_CLIENT_CERT']."</textarea></p>";

?>
<script type="text/javascript">
var IE=0;

var CAPICOM_STORE_OPEN_READ_ONLY = 0;

var CAPICOM_CURRENT_USER_STORE = 2;

var CAPICOM_CERTIFICATE_FIND_SHA1_HASH = 0;

var CAPICOM_CERTIFICATE_FIND_EXTENDED_PROPERTY = 6;

var CAPICOM_CERTIFICATE_FIND_TIME_VALID = 9;

var CAPICOM_CERTIFICATE_FIND_KEY_USAGE = 12;

var CAPICOM_DIGITAL_SIGNATURE_KEY_USAGE = 0x00000080;

var CAPICOM_AUTHENTICATED_ATTRIBUTE_SIGNING_TIME = 0;

var CAPICOM_INFO_SUBJECT_SIMPLE_NAME = 0;

var CAPICOM_ENCODE_BASE64 = 0;

var CAPICOM_E_CANCELLED = -2138568446;

var CERT_KEY_SPEC_PROP_ID = 6;

var CAPICOM_VERIFY_SIGNATURE_ONLY = 0;

var CAPICOM_VERIFY_SIGNATURE_AND_CERTIFICATE = 1;



var CAPICOM_CERTIFICATE_INCLUDE_CHAIN_EXCEPT_ROOT=0;

var CAPICOM_CERTIFICATE_INCLUDE_WHOLE_CHAIN=1;

var CAPICOM_CERTIFICATE_INCLUDE_END_ENTITY_ONLY=2;





var CAPICOM_NON_REPUDIATION_KEY_USAGE = 0x00000040;


function isCAPICOMInstalled()

{

	if (typeof(oCAPICOM) == "object")

    {

		if (oCAPICOM.object != null)

        {

                    return true;

        }

        else

        {

                    

                    return false;

        }

    }

    else

    {

            

             return false;

    }

}







function Firmar(pStrDatos, pIntNivelAnidamiento, pBolIncluirDatos){



		var lObjStore = new ActiveXObject("CAPICOM.Store");

		var lObjDatos = new ActiveXObject("CAPICOM.SignedData");

		var lObjSigner = new ActiveXObject("CAPICOM.Signer");

		var lObjCertificados = new ActiveXObject("CAPICOM.Certificates");

		var lObjCertificado = new ActiveXObject("CAPICOM.Certificate");

		var lObjCertBueno = new ActiveXObject("CAPICOM.Certificate");



		try{

			lObjStore.Open(CAPICOM_CURRENT_USER_STORE, "My", CAPICOM_STORE_OPEN_READ_ONLY);

			

			var filteredCertificates = lObjStore.Certificates

        .Find(CAPICOM_CERTIFICATE_FIND_TIME_VALID)

	.Find(CAPICOM_CERTIFICATE_FIND_KEY_USAGE,

              CAPICOM_NON_REPUDIATION_KEY_USAGE)

        .Find(CAPICOM_CERTIFICATE_FIND_EXTENDED_PROPERTY,

              CERT_KEY_SPEC_PROP_ID);



			lObjCertificados = filteredCertificates.Select();

			

			lObjCertBueno = lObjCertificados.Item(1);



			lObjSigner.Certificate = lObjCertBueno;



			lObjDatos.Content = pStrDatos;



			var lStrFirma

                        lStrFirma = lObjDatos.Sign(lObjSigner, pBolIncluirDatos, CAPICOM_ENCODE_BASE64);



                        document.forms[0].datosFirmados.value = lStrFirma;

                        alert("Operación de Firma realizada correctamente. Certificado de Firma verificado correctamente.");

                        return true;

		}

		catch(e)

		{

			

                        alert("No se ha podido generar una firma válida.");

                        return true;

		}

	}   



	function verifySignature(dataTextField, signatureTextField)

{

    var signedData = new ActiveXObject('CAPICOM.SignedData');



    try

    {

		signedData.Content=dataTextField.value;

        signedData.Verify(signatureTextField.value, true, CAPICOM_VERIFY_SIGNATURE_ONLY);

    }

    catch (e) {
        window.alert(e.description);
        return false;
    }

    window.alert("Signature verified");
    return true;
}

function firmartexto(textofirma){
	var firmado ;

	if (textofirma == ""){
		alert("No se pueden firmar textos en blanco.");
		return "";
	}

	if ( obtenernavegador() == 1){
            if (isCAPICOMInstalled()) {
            return Firmar(textofirma ,0 , false );
            }
            else {
                alert("Para poder realizar el processo de firma necesita instalar las librerias criptográficas CAPICOM. ");
            }
	} else {
	  return firmarMozilla(textofirma) ;
	}
}

function firmarMozilla(textoafirmar) {
      var signedText = window.crypto.signText(textoafirmar , "ask");

      if (signedText.substring(0,5) =="error") {
        alert("Su navegador no ha podido generar una firma valida.");
        return true;
      }
      else if (signedText =="no generada") {
        alert("Su navegador no ha podido generar una firma valida.");
        return true;
      } else {
        document.forms[0].datosFirmados.value = signedText;
        alert("Operación de Firma realizada correctamente. Certificado de Firma verificado correctamente.");   
    }
}

function obtenernavegador() {
  if (navigator.appName=="Microsoft Internet Explorer") { IE=1; } else { IE=0; }
  return(IE);
}
</script>
<table>
<tr>
<td><?php
if ($ocsp == "good") { 
  echo "<img src=\"/imagenes/on.png\" alt=\"good\"/>"; }
else {
  echo "<img src=\"/imagenes/off.png\" alt=\"Certificate revoked or state unknown\"/>";
}
?></td>
<td>
<?php
echo "<p>DNI: ".getDNI()."</p>";
echo "<p>Nombre: ".$_SERVER['SSL_CLIENT_S_DN_G']." ".getApellidos()."</p>";
?>
</td>
</tr>
</table>
<input type="text" name="datos"/>
<input type="button" onclick="firmartexto(this.form.datos.value)" value="Firmar"/>
<br/><br/><br/>
Resultado de firma:<br/>
<textarea id="datosFirmados" cols="80" rows="15"></textarea>
