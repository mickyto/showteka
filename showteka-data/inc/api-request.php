<?php
function sht_api_request($request_data_object, $action_name) {
  $stage_xml = '<?xml version="1.0" encoding="UTF-8"?>
  <Request>
  <RequestAuthorization>
  <UserId>10277</UserId>
  <Hash>b5744d1e326e866aac01ac17bffb3e5b</Hash>
  </RequestAuthorization>
  <RequestData>
  <RequestDataObject>'. $request_data_object .'</RequestDataObject>
  </RequestData>
  </Request>';

  try {
    $client = new SoapClient('http://api.zriteli.ru/index.php?wsdl', array("trace" => 1, "exception" => 0));
    $stage_response = $client->__soapCall($action_name, array($stage_xml));
    return simplexml_load_string($stage_response);
  } catch (SoapFault $exception) {
    return $exception->getMessage();
  }
}
?>
